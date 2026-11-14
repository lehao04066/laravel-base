<?php

namespace App\Http\Controllers;

use App\Models\Amenity;
use App\Models\MatchPost;
use App\Models\Venue;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExploreController extends Controller
{
    public function index(Request $request)
    {
        // 1. Validate GET query parameters with Vietnamese messages
        $validator = Validator::make($request->all(), [
            'q' => ['nullable', 'string', 'max:100'],
            'district' => ['nullable', 'string', 'max:100'],
            'min_price' => ['nullable', 'numeric', 'min:0'],
            'max_price' => [
                'nullable',
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) use ($request) {
                    if (
                        $request->filled('min_price') &&
                        is_numeric($request->min_price) &&
                        (float) $value < (float) $request->min_price
                    ) {
                        $fail('Giá tối đa phải lớn hơn hoặc bằng giá tối thiểu.');
                    }
                },
            ],
            'status' => ['nullable', 'string', 'in:all,open,closed'],
            'amenities' => ['nullable', 'array'],
            'amenities.*' => ['integer', 'exists:amenities,id'],
        ], [
            'q.max' => 'Từ khóa tìm kiếm không được vượt quá 100 ký tự.',
            'district.max' => 'Tên quận/huyện không được vượt quá 100 ký tự.',
            'min_price.numeric' => 'Giá tối thiểu phải là số hợp lệ.',
            'min_price.min' => 'Giá tối thiểu không được âm.',
            'max_price.numeric' => 'Giá tối đa phải là số hợp lệ.',
            'max_price.min' => 'Giá tối đa không được âm.',
            'status.in' => 'Trạng thái mở cửa không hợp lệ.',
            'amenities.array' => 'Danh sách tiện ích phải là mảng hợp lệ.',
            'amenities.*.integer' => 'Mã tiện ích phải là số nguyên.',
            'amenities.*.exists' => 'Tiện ích đã chọn không tồn tại trong hệ thống.',
        ]);

        $amenities = Amenity::orderBy('id')->get();
        $districts = Venue::where('is_active', true)
            ->whereNotNull('district')
            ->where('district', '!=', '')
            ->distinct()
            ->orderBy('district')
            ->pluck('district');
        $recentMatches = class_exists(MatchPost::class) 
            ? MatchPost::with(['user', 'venue'])->where('status', 'open')->latest()->take(3)->get()
            : collect();

        if ($validator->fails()) {
            // If validation fails, return empty paginated result and display errors
            $venues = Venue::whereRaw('1 = 0')->paginate(6)->withQueryString();

            return view('customer.explore', compact('venues', 'amenities', 'districts', 'recentMatches'))
                ->withErrors($validator);
        }

        // 2. Build multi-criteria query
        $query = Venue::with(['courts', 'amenities', 'pricingRules', 'operatingHours'])
            ->where('is_active', true);

        // Filter: Keyword (tên cụm sân hoặc địa chỉ)
        if ($request->filled('q')) {
            $keyword = trim($request->q);
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('address', 'like', "%{$keyword}%");
            });
        }

        // Filter: District (Quận/Huyện)
        if ($request->filled('district') && $request->district !== 'all') {
            $query->where('district', $request->district);
        }

        // Filter: Price range (Khoảng giá theo bảng giá hiện có của cơ sở)
        if ($request->filled('min_price') || $request->filled('max_price')) {
            $minPrice = $request->filled('min_price') ? (float) $request->min_price : null;
            $maxPrice = $request->filled('max_price') ? (float) $request->max_price : null;

            $query->whereHas('pricingRules', function ($q) use ($minPrice, $maxPrice) {
                if ($minPrice !== null) {
                    $q->where('price_per_hour', '>=', $minPrice);
                }
                if ($maxPrice !== null) {
                    $q->where('price_per_hour', '<=', $maxPrice);
                }
            });
        }

        // Filter: Operating Status (Đang mở / Đang đóng theo giờ hiện tại và ca qua nửa đêm)
        if ($request->filled('status') && in_array($request->status, ['open', 'closed'])) {
            $now = Carbon::now('Asia/Ho_Chi_Minh');
            $currentTime = $now->format('H:i:s');
            $currentDay = $now->dayOfWeek; // 0 = Sunday, 1 = Monday, ... 6 = Saturday
            $prevDay = ($currentDay + 6) % 7;

            $isOpenCallback = function ($q) use ($currentTime, $currentDay, $prevDay) {
                $q->whereHas('operatingHours', function ($oh) use ($currentTime, $currentDay, $prevDay) {
                    $oh->where('is_closed', false)
                       ->where(function ($sub) use ($currentTime, $currentDay, $prevDay) {
                           // Ca hoạt động của ngày hiện tại
                           $sub->where(function ($today) use ($currentTime, $currentDay) {
                               $today->where('day_of_week', $currentDay)
                                     ->where(function ($t) use ($currentTime) {
                                         // Ca trong ngày thông thường (open <= close)
                                         $t->where(function ($normal) use ($currentTime) {
                                             $normal->whereColumn('open_time', '<=', 'close_time')
                                                    ->where('open_time', '<=', $currentTime)
                                                    ->where('close_time', '>=', $currentTime);
                                         })
                                         // Ca qua nửa đêm mở từ hôm nay (open > close)
                                         ->orWhere(function ($cross) use ($currentTime) {
                                             $cross->whereColumn('open_time', '>', 'close_time')
                                                   ->where('open_time', '<=', $currentTime);
                                         });
                                     });
                           })
                           // Ca qua nửa đêm bắt đầu từ ngày hôm qua kéo dài sang rạng sáng hôm nay
                           ->orWhere(function ($yesterday) use ($currentTime, $prevDay) {
                               $yesterday->where('day_of_week', $prevDay)
                                         ->whereColumn('open_time', '>', 'close_time')
                                         ->where('close_time', '>=', $currentTime);
                           });
                       });
                })
                // Fallback nếu cụm sân chưa cấu hình bảng operating_hours
                ->orWhere(function ($fallback) use ($currentTime) {
                    $fallback->doesntHave('operatingHours')
                             ->where(function ($t) use ($currentTime) {
                                 $t->where(function ($normal) use ($currentTime) {
                                     $normal->whereColumn('opening_time', '<=', 'closing_time')
                                            ->where('opening_time', '<=', $currentTime)
                                            ->where('closing_time', '>=', $currentTime);
                                 })->orWhere(function ($cross) use ($currentTime) {
                                     $cross->whereColumn('opening_time', '>', 'closing_time')
                                           ->where(function ($c) use ($currentTime) {
                                               $c->where('opening_time', '<=', $currentTime)
                                                 ->orWhere('closing_time', '>=', $currentTime);
                                           });
                                 });
                             });
                });
            };

            if ($request->status === 'open') {
                $query->where($isOpenCallback);
            } elseif ($request->status === 'closed') {
                $query->whereNot($isOpenCallback);
            }
        }

        // Filter: Multi-amenities (Một cụm sân phải có đủ tất cả các tiện ích được chọn)
        if ($request->has('amenities') && is_array($request->amenities)) {
            $amenityIds = array_filter($request->amenities, function ($val) {
                return is_numeric($val) && (int)$val > 0;
            });
            foreach ($amenityIds as $amenityId) {
                $query->whereHas('amenities', function ($q) use ($amenityId) {
                    $q->where('amenities.id', (int) $amenityId);
                });
            }
        }

        // Server-side pagination, retaining query string
        $venues = $query->orderBy('rating_avg', 'desc')
                        ->orderBy('id', 'asc')
                        ->paginate(6)
                        ->withQueryString();

        return view('customer.explore', compact('venues', 'amenities', 'districts', 'recentMatches'));
    }
}
