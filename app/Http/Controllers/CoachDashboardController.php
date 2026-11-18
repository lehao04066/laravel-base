<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CoachDashboardController extends Controller
{
    public function __invoke(): View
    {
        $coaches = DB::table('coaches')
            ->select(['id', 'full_name', 'title', 'bio', 'hourly_rate', 'avatar', 'rating_avg', 'students_count'])
            ->orderByDesc('rating_avg')
            ->orderBy('full_name')
            ->get();

        $coachCount = $coaches->count();

        return view('auth.role-dashboard', [
            'dashboardType' => 'coach',
            'roleLabel' => 'Huấn luyện viên',
            'roleDescription' => 'Đội ngũ huấn luyện viên GoSport và thông tin giảng dạy.',
            'coaches' => $coaches,
            'coachCount' => $coachCount,
            'coachStudentCount' => number_format((int) $coaches->sum('students_count')),
            'coachAverageRating' => $coachCount > 0
                ? number_format((float) $coaches->avg('rating_avg'), 2)
                : '—',
            'coachAverageHourlyRate' => $coachCount > 0
                ? number_format((float) $coaches->avg('hourly_rate'), 0, ',', '.')
                : '—',
        ]);
    }
}
