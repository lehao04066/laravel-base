<?php

namespace App\Http\Controllers;

use App\Models\Court;
use App\Models\Venue;
use Illuminate\Http\Request;

class AdminCourtController extends Controller
{
    public function store(Request $request, int $venueId)
    {
        $venue = Venue::findOrFail($venueId);

        $validated = $request->validate([
            'court_number' => ['required', 'integer', 'min:1'],
            'name' => ['required', 'string', 'max:255'],
            'court_type_id' => ['nullable', 'exists:court_types,id'],
            'surface_type' => ['nullable', 'string', 'max:100'],
        ], [
            'court_number.required' => 'Vui lòng nhập số thứ tự sân.',
            'name.required' => 'Vui lòng nhập tên sân con.',
        ]);

        // Check unique court number in venue
        if (Court::where('venue_id', $venue->id)->where('court_number', $validated['court_number'])->exists()) {
            return back()->withErrors(['court_number' => "Số thứ tự sân {$validated['court_number']} đã tồn tại trong cụm sân này."])->withInput();
        }

        Court::create([
            'venue_id' => $venue->id,
            'court_number' => $validated['court_number'],
            'name' => $validated['name'],
            'court_type_id' => $validated['court_type_id'] ?? null,
            'surface_type' => $validated['surface_type'] ?? 'Yonex BWF Standard',
            'is_active' => true,
        ]);

        return back()->with('success', "Đã thêm sân con \"{$validated['name']}\" vào cụm sân!");
    }

    public function update(Request $request, int $id)
    {
        $court = Court::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'court_number' => ['required', 'integer', 'min:1'],
            'court_type_id' => ['nullable', 'exists:court_types,id'],
            'surface_type' => ['nullable', 'string', 'max:100'],
        ]);

        if (Court::where('venue_id', $court->venue_id)->where('court_number', $validated['court_number'])->where('id', '!=', $court->id)->exists()) {
            return back()->withErrors(['court_number' => "Số thứ tự sân {$validated['court_number']} đã bị trùng."])->withInput();
        }

        $court->update([
            'name' => $validated['name'],
            'court_number' => $validated['court_number'],
            'court_type_id' => $validated['court_type_id'] ?? null,
            'surface_type' => $validated['surface_type'] ?? $court->surface_type,
        ]);

        return back()->with('success', "Đã cập nhật sân \"{$court->name}\" thành công!");
    }

    public function toggleActive(int $id)
    {
        $court = Court::findOrFail($id);
        $court->is_active = !$court->is_active;
        $court->save();

        $statusStr = $court->is_active ? 'hoạt động' : 'ngừng hoạt động';
        return back()->with('success', "Đã chuyển sân \"{$court->name}\" sang trạng thái: {$statusStr}.");
    }

    public function destroy(int $id)
    {
        $court = Court::findOrFail($id);
        $name = $court->name;
        $court->delete();

        return back()->with('success', "Đã xóa sân con \"{$name}\"!");
    }
}
