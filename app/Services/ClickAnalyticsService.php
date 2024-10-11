<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ClickAnalyticsService
{
    private static $cacheTime = 3600; // Cache for 1 hour
    public static function getBasicAnalytics($urlId)
    {
        return Cache::remember("basic_analytics:{$urlId}", self::$cacheTime, function () use ($urlId) {
            $totalVisits = DB::table('clicks')->where('url_id', $urlId)->count();
            $uniqueVisits = DB::table('clicks')->where('url_id', $urlId)->distinct('ip_address')->count('ip_address');

            return [
                'total_visits' => $totalVisits,
                'unique_visits' => $uniqueVisits,
            ];
        });
    }

    public static function getAverageVisits($urlId)
    {
        return Cache::remember("average_visits:{$urlId}", self::$cacheTime, function () use ($urlId) {
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
        });
    }

    public static function getDonutChartData($urlId)
    {
        return Cache::remember("donut_chart_data:{$urlId}", self::$cacheTime, function () use ($urlId) {
            $browsers = DB::table('clicks')
                ->where('url_id', $urlId)
                ->select('browser', DB::raw('count(*) as count'))
                ->groupBy('browser')
                ->orderByDesc('count')
                ->limit(5)
                ->get()
                ->toArray();

            $browserLabels = array_column($browsers, 'browser');
            $browserCounts = array_column($browsers, 'count');

            $platforms = DB::table('clicks')
                ->where('url_id', $urlId)
                ->select('device_type', DB::raw('count(*) as count'))
                ->groupBy('device_type')
                ->orderByDesc('count')
                ->limit(5)
                ->get()
                ->toArray();

            $platformLabels = array_column($platforms, 'device_type');
            $platformCounts = array_column($platforms, 'count');

            $cities = DB::table('clicks')
                ->where('url_id', $urlId)
                ->select('city', DB::raw('count(*) as count'))
                ->groupBy('city')
                ->orderByDesc('count')
                ->limit(5)
                ->get()
                ->toArray();

            $cityLabels = array_column($cities, 'city');
            $cityCounts = array_column($cities, 'count');

            return [
                'browsers' => [
                    'labels' => $browserLabels,
                    'counts' => $browserCounts,
                ],
                'platforms' => [
                    'labels' => $platformLabels,
                    'counts' => $platformCounts,
                ],
                'cities' => [
                    'labels' => $cityLabels,
                    'counts' => $cityCounts,
                ],
            ];
        });
    }

    public static function getAnalytics($urlId)
    {
        $basicAnalytics = self::getBasicAnalytics($urlId);
        $averageVisits = self::getAverageVisits($urlId);
        $donutChartData = self::getDonutChartData($urlId);

        return array_merge($basicAnalytics, $averageVisits, $donutChartData);
    }

    public static function invalidateCache($urlId)
    {
        Cache::forget("basic_analytics:{$urlId}");
        Cache::forget("average_visits:{$urlId}");
        Cache::forget("donut_chart_data:{$urlId}");
    }
}
