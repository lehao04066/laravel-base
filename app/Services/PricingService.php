<?php

namespace App\Services;

use App\Models\Court;
use App\Models\CourtPricingRule;
use Carbon\Carbon;

class PricingService
{
    /**
     * Calculate price for a slot on a given court and date.
     */
    public function calculateSlotPrice(Court $court, string $date, string $startTime, string $endTime): array
    {
        $venue = $court->venue;
        $isWeekend = Carbon::parse($date)->isWeekend();

        $startFormatted = strlen($startTime) === 5 ? $startTime . ':00' : $startTime;
        $endFormatted = strlen($endTime) === 5 ? $endTime . ':00' : $endTime;

        $pricingRule = CourtPricingRule::where('venue_id', $venue->id)
            ->where(function ($q) use ($court) {
                $q->whereNull('court_type_id')
                  ->orWhere('court_type_id', $court->court_type_id);
            })
            ->where(function ($q) use ($isWeekend) {
                $q->where('day_type', 'all')
                  ->orWhere('day_type', $isWeekend ? 'weekend' : 'weekday');
            })
            ->where('start_time', '<=', $startFormatted)
            ->where('end_time', '>=', $endFormatted)
            ->orderByDesc('is_peak')
            ->first();

        $hourlyRate = $pricingRule ? (float)$pricingRule->price_per_hour : 150000.00;
        $isPeak = $pricingRule ? (bool)$pricingRule->is_peak : false;

        $durationMinutes = Carbon::parse($startFormatted)->diffInMinutes(Carbon::parse($endFormatted));
        $durationHours = $durationMinutes / 60.0;
        $amount = $hourlyRate * $durationHours;

        return [
            'hourly_rate' => $hourlyRate,
            'duration_minutes' => $durationMinutes,
            'amount' => $amount,
            'is_peak' => $isPeak,
            'rule_name' => $pricingRule ? $pricingRule->name : 'Giá cơ bản',
        ];
    }
}
