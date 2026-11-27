<?php

namespace App\Http\Controllers;

use App\Models\CourtPricingRule;
use App\Models\CourtType;
use App\Models\OperatingHour;
use App\Models\Venue;
use Illuminate\Http\Request;

class AdminPricingController extends Controller
{
    public function index(Request $request)
    {
        $venues = Venue::all();
        $venueId = $request->query('venue_id', $venues->first()?->id);
        $venue = Venue::with(['pricingRules', 'operatingHours'])->find($venueId) ?? $venues->first();
        $rules = $venue ? $venue->pricingRules : collect();
        $operatingHours = $venue ? $venue->operatingHours->sortBy('day_of_week') : collect();
        $courtTypes = CourtType::all();

        return view('admin.pricing', compact('venues', 'venue', 'rules', 'operatingHours', 'courtTypes'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'rules' => ['required', 'array'],
            'rules.*.id' => ['required', 'exists:court_pricing_rules,id'],
            'rules.*.price_per_hour' => ['required', 'numeric', 'min:0'],
        ]);

        foreach ($request->rules as $ruleData) {
            CourtPricingRule::where('id', $ruleData['id'])->update([
                'price_per_hour' => $ruleData['price_per_hour'],
            ]);
        }

        return back()->with('success', 'Cập nhật bảng giá theo giờ thành công!');
    }

    public function storeRule(Request $request)
    {
        $validated = $request->validate([
            'venue_id' => ['required', 'exists:venues,id'],
            'name' => ['required', 'string', 'max:255'],
            'day_type' => ['required', 'in:all,weekday,weekend'],
            'start_time' => ['required'],
            'end_time' => ['required', 'after:start_time'],
            'price_per_hour' => ['required', 'numeric', 'min:0'],
            'is_peak' => ['nullable', 'boolean'],
        ], [
            'name.required' => 'Vui lòng nhập tên khung giá.',
            'start_time.required' => 'Vui lòng chọn giờ bắt đầu.',
            'end_time.required' => 'Vui lòng chọn giờ kết thúc.',
            'end_time.after' => 'Giờ kết thúc phải sau giờ bắt đầu.',
            'price_per_hour.required' => 'Vui lòng nhập giá theo giờ.',
        ]);

        CourtPricingRule::create([
            'venue_id' => $validated['venue_id'],
            'name' => $validated['name'],
            'day_type' => $validated['day_type'],
            'start_time' => strlen($validated['start_time']) === 5 ? $validated['start_time'] . ':00' : $validated['start_time'],
            'end_time' => strlen($validated['end_time']) === 5 ? $validated['end_time'] . ':00' : $validated['end_time'],
            'price_per_hour' => $validated['price_per_hour'],
            'is_peak' => $request->boolean('is_peak'),
        ]);

        return back()->with('success', 'Đã thêm khung giá mới thành công!');
    }

    public function destroyRule(int $id)
    {
        $rule = CourtPricingRule::findOrFail($id);
        $rule->delete();

        return back()->with('success', 'Đã xóa khung giá!');
    }

    public function updateOperatingHours(Request $request)
    {
        $request->validate([
            'venue_id' => ['required', 'exists:venues,id'],
            'hours' => ['required', 'array'],
            'hours.*.id' => ['required', 'exists:operating_hours,id'],
            'hours.*.open_time' => ['required'],
            'hours.*.close_time' => ['required'],
        ]);

        $venue = Venue::findOrFail($request->venue_id);

        foreach ($request->hours as $item) {
            $isClosed = isset($item['is_closed']) && $item['is_closed'] == '1';
            OperatingHour::where('id', $item['id'])
                ->where('venue_id', $venue->id)
                ->update([
                    'open_time' => strlen($item['open_time']) === 5 ? $item['open_time'] . ':00' : $item['open_time'],
                    'close_time' => strlen($item['close_time']) === 5 ? $item['close_time'] . ':00' : $item['close_time'],
                    'is_closed' => $isClosed,
                ]);
        }

        // Also update venue default opening & closing time if day 1 (Monday) is updated
        $mondayHour = OperatingHour::where('venue_id', $venue->id)->where('day_of_week', 1)->first();
        if ($mondayHour) {
            $venue->update([
                'opening_time' => $mondayHour->open_time,
                'closing_time' => $mondayHour->close_time,
            ]);
        }

        return back()->with('success', 'Đã cập nhật giờ mở/đóng cửa cho cụm sân!');
    }
}
