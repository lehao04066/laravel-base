<?php

namespace App\Http\Controllers;

use App\Models\Court;
use App\Models\CourtType;
use App\Models\OperatingHour;
use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminVenueController extends Controller
{
    public function index(Request $request)
    {
        $query = Venue::withCount(['courts', 'bookings'])->latest();

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($b) use ($q) {
                $b->where('name', 'like', "%{$q}%")
                  ->orWhere('address', 'like', "%{$q}%")
                  ->orWhere('district', 'like', "%{$q}%");
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $venues = $query->paginate(10)->withQueryString();

        return view('admin.venues.index', compact('venues'));
    }

    public function create()
    {
        return view('admin.venues.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'district' => ['required', 'string', 'max:100'],
            'city' => ['nullable', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:100'],
            'featured_image' => ['nullable', 'url', 'max:500'],
            'description' => ['nullable', 'string'],
            'opening_time' => ['required', 'date_format:H:i'],
            'closing_time' => ['required', 'date_format:H:i', 'after:opening_time'],
            'initial_courts_count' => ['nullable', 'integer', 'min:1', 'max:50'],
        ], [
            'name.required' => 'Vui lòng nhập tên cụm sân.',
            'address.required' => 'Vui lòng nhập địa chỉ cụm sân.',
            'district.required' => 'Vui lòng nhập quận / huyện.',
            'phone.required' => 'Vui lòng nhập số điện thoại liên hệ.',
            'opening_time.required' => 'Vui lòng nhập giờ mở cửa.',
            'closing_time.required' => 'Vui lòng nhập giờ đóng cửa.',
            'closing_time.after' => 'Giờ đóng cửa phải sau giờ mở cửa.',
        ]);

        $slug = Str::slug($validated['name']);
        $originalSlug = $slug;
        $counter = 1;
        while (Venue::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }

        $venue = Venue::create([
            'owner_id' => auth()->id(),
            'name' => $validated['name'],
            'slug' => $slug,
            'address' => $validated['address'],
            'district' => $validated['district'],
            'city' => $validated['city'] ?? 'Hà Nội',
            'phone' => $validated['phone'],
            'email' => $validated['email'] ?? null,
            'featured_image' => $validated['featured_image'] ?? 'https://images.unsplash.com/photo-1626224583764-f87db24ac4ea?w=1200&auto=format&fit=crop&q=80',
            'description' => $validated['description'] ?? null,
            'opening_time' => $validated['opening_time'] . ':00',
            'closing_time' => $validated['closing_time'] . ':00',
            'is_active' => true,
        ]);

        // Default operating hours (0 to 6)
        for ($day = 0; $day <= 6; $day++) {
            OperatingHour::create([
                'venue_id' => $venue->id,
                'day_of_week' => $day,
                'open_time' => $venue->opening_time,
                'close_time' => $venue->closing_time,
                'is_closed' => false,
            ]);
        }

        // Create default court pricing rules
        $defaultRules = [
            ['name' => 'Giờ thường ngày trong tuần', 'day_type' => 'weekday', 'start_time' => '06:00:00', 'end_time' => '17:00:00', 'price_per_hour' => 120000, 'is_peak' => false],
            ['name' => 'Giờ vàng tối trong tuần', 'day_type' => 'weekday', 'start_time' => '17:00:00', 'end_time' => '21:00:00', 'price_per_hour' => 200000, 'is_peak' => true],
            ['name' => 'Giờ đêm muộn', 'day_type' => 'weekday', 'start_time' => '21:00:00', 'end_time' => '23:00:00', 'price_per_hour' => 150000, 'is_peak' => false],
            ['name' => 'Khung giờ cuối tuần', 'day_type' => 'weekend', 'start_time' => '06:00:00', 'end_time' => '23:00:00', 'price_per_hour' => 190000, 'is_peak' => true],
        ];
        foreach ($defaultRules as $r) {
            $venue->pricingRules()->create($r);
        }

        // Optionally create initial courts
        $initialCourts = (int)($request->initial_courts_count ?? 4);
        for ($i = 1; $i <= $initialCourts; $i++) {
            Court::create([
                'venue_id' => $venue->id,
                'court_number' => $i,
                'name' => "Sân {$i} - Standard Pro",
                'surface_type' => 'Yonex BWF Standard',
                'is_active' => true,
            ]);
        }

        return redirect()->route('admin.venues.edit', $venue->id)->with('success', "Đã tạo cụm sân {$venue->name} và {$initialCourts} sân con thành công!");
    }

    public function edit(int $id)
    {
        $venue = Venue::with(['courts.courtType', 'operatingHours', 'pricingRules'])->findOrFail($id);
        $courtTypes = CourtType::all();

        return view('admin.venues.edit', compact('venue', 'courtTypes'));
    }

    public function update(Request $request, int $id)
    {
        $venue = Venue::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'district' => ['required', 'string', 'max:100'],
            'city' => ['nullable', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:100'],
            'featured_image' => ['nullable', 'url', 'max:500'],
            'description' => ['nullable', 'string'],
            'opening_time' => ['required'],
            'closing_time' => ['required'],
        ]);

        $venue->update([
            'name' => $validated['name'],
            'address' => $validated['address'],
            'district' => $validated['district'],
            'city' => $validated['city'] ?? $venue->city,
            'phone' => $validated['phone'],
            'email' => $validated['email'] ?? $venue->email,
            'featured_image' => $validated['featured_image'] ?? $venue->featured_image,
            'description' => $validated['description'] ?? $venue->description,
            'opening_time' => strlen($validated['opening_time']) === 5 ? $validated['opening_time'] . ':00' : $validated['opening_time'],
            'closing_time' => strlen($validated['closing_time']) === 5 ? $validated['closing_time'] . ':00' : $validated['closing_time'],
        ]);

        return back()->with('success', "Đã cập nhật thông tin cụm sân {$venue->name} thành công!");
    }

    public function toggleActive(int $id)
    {
        $venue = Venue::findOrFail($id);
        $venue->is_active = !$venue->is_active;
        $venue->save();

        $statusStr = $venue->is_active ? 'hoạt động' : 'ngừng hoạt động';
        return back()->with('success', "Đã chuyển trạng thái cụm sân {$venue->name} sang: {$statusStr}.");
    }
}
