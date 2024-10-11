<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ClickAnalyticsService
{
    public static function getBasicAnalytics($urlId)
    {
        $totalVisits = DB::table('clicks')->where('url_id', $urlId)->count();

        $uniqueVisits = DB::table('clicks')
            ->where('url_id', $urlId)
            ->distinct('ip_address')
            ->count('ip_address');

        return [
            'total_visits' => $totalVisits,
            'unique_visits' => $uniqueVisits,
        ];
    }

    public static function getAverageVisits($urlId)
    {
        $query = "
                SELECT
                    AVG(daily_visits) as avg_daily_visits,
                    AVG(weekly_visits) as avg_weekly_visits,
                    AVG(monthly_visits) as avg_monthly_visits
                FROM (
                    SELECT
                        DATE(created_at) as date,
                        COUNT(*) as daily_visits,
                        COUNT(*) as weekly_visits,
                        COUNT(*) as monthly_visits
                    FROM clicks
                    WHERE url_id = ?
                    GROUP BY
                        DATE(created_at),
                        YEARWEEK(created_at),
                        DATE_FORMAT(created_at, '%Y-%m')
                ) as visit_stats
            ";

            $result = DB::select($query, [$urlId]);

            if (empty($result)) {
                return [
                    'avg_daily_visits' => 0,
                    'avg_weekly_visits' => 0,
                    'avg_monthly_visits' => 0
                ];
            }

            $stats = $result[0];

            return [
                'avg_daily_visits' => round($stats->avg_daily_visits ?? 0, 2),
                'avg_weekly_visits' => round($stats->avg_weekly_visits ?? 0, 2),
                'avg_monthly_visits' => round($stats->avg_monthly_visits ?? 0, 2)
            ];
    }

    public static function getAnalytics($urlId) {
        $basicAnalytics = self::getBasicAnalytics($urlId);
        $averageVisits = self::getAverageVisits($urlId);

        return array_merge($basicAnalytics, $averageVisits);
    }
}
