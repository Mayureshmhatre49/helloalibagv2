<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WeatherService
{
    // Alibaug coordinates (Alibaug town centre, India)
    private const float LAT = 18.6414;
    private const float LNG = 72.8722;
    private const string TIMEZONE = 'Asia/Kolkata';
    private const int CACHE_TTL = 1800;         // 30 minutes on success
    private const int FALLBACK_CACHE_TTL = 300; // 5 minutes when the API is unreachable

    public function getForecast(): array
    {
        // Serve a cached forecast if we have one (success or fallback).
        $cached = Cache::get('weather.alibaug.forecast');
        if ($cached !== null) {
            return $cached;
        }

        try {
            $response = Http::timeout(5)->get('https://api.open-meteo.com/v1/forecast', [
                'latitude' => self::LAT,
                'longitude' => self::LNG,
                'current' => 'temperature_2m,relative_humidity_2m,apparent_temperature,is_day,weather_code,wind_speed_10m,wind_direction_10m',
                'daily' => 'weather_code,temperature_2m_max,temperature_2m_min,sunrise,sunset,uv_index_max,precipitation_sum,precipitation_probability_max,wind_speed_10m_max',
                'timezone' => self::TIMEZONE,
                'forecast_days' => 7,
            ]);

            if ($response->successful()) {
                $payload = $this->shape($response->json());
                Cache::put('weather.alibaug.forecast', $payload, self::CACHE_TTL);
                return $payload;
            }

            Log::warning('Open-Meteo non-success response', ['status' => $response->status()]);
        } catch (\Throwable $e) {
            // DNS error, timeout, TLS failure — never let the page break.
            Log::warning('Open-Meteo request threw', ['error' => $e->getMessage()]);
        }

        // Cache the fallback for a SHORTER window so we retry the real API soon.
        $fallback = $this->fallbackPayload();
        Cache::put('weather.alibaug.forecast', $fallback, self::FALLBACK_CACHE_TTL);
        return $fallback;
    }

    private function shape(array $raw): array
    {
        $current = $raw['current'] ?? [];
        $daily = $raw['daily'] ?? [];

        $todayIst = Carbon::now(self::TIMEZONE)->toDateString();
        $tomorrowIst = Carbon::now(self::TIMEZONE)->addDay()->toDateString();

        $days = [];
        foreach ($daily['time'] ?? [] as $i => $date) {
            $dayLabel = match ($date) {
                $todayIst => 'Today',
                $tomorrowIst => 'Tomorrow',
                default => Carbon::parse($date)->format('D, M j'),
            };

            $days[] = [
                'date' => $date,
                'day_label' => $dayLabel,
                'weather_code' => $daily['weather_code'][$i] ?? null,
                'condition' => $this->codeToCondition($daily['weather_code'][$i] ?? null),
                'icon' => $this->codeToIcon($daily['weather_code'][$i] ?? null),
                'temp_max' => round($daily['temperature_2m_max'][$i] ?? 0),
                'temp_min' => round($daily['temperature_2m_min'][$i] ?? 0),
                'sunrise' => isset($daily['sunrise'][$i]) ? Carbon::parse($daily['sunrise'][$i])->format('g:i A') : null,
                'sunset' => isset($daily['sunset'][$i]) ? Carbon::parse($daily['sunset'][$i])->format('g:i A') : null,
                'uv_index' => round($daily['uv_index_max'][$i] ?? 0),
                'precipitation_mm' => round($daily['precipitation_sum'][$i] ?? 0, 1),
                'rain_probability' => $daily['precipitation_probability_max'][$i] ?? 0,
                'wind_max' => round($daily['wind_speed_10m_max'][$i] ?? 0),
            ];
        }

        return [
            'current' => [
                'temperature' => round($current['temperature_2m'] ?? 0),
                'feels_like' => round($current['apparent_temperature'] ?? 0),
                'humidity' => $current['relative_humidity_2m'] ?? 0,
                'wind_speed' => round($current['wind_speed_10m'] ?? 0),
                'wind_direction' => $this->degreesToCompass($current['wind_direction_10m'] ?? 0),
                'is_day' => (bool) ($current['is_day'] ?? 1),
                'weather_code' => $current['weather_code'] ?? null,
                'condition' => $this->codeToCondition($current['weather_code'] ?? null),
                'icon' => $this->codeToIcon($current['weather_code'] ?? null, (bool) ($current['is_day'] ?? 1)),
                'updated_at' => Carbon::now(self::TIMEZONE)->format('g:i A T'),
            ],
            'days' => $days,
            'location' => 'Alibaug, Maharashtra',
            'source' => 'open-meteo',
            'fallback' => false,
        ];
    }

    private function fallbackPayload(): array
    {
        // Returned only when Open-Meteo is unreachable. Keep widget renderable.
        return [
            'current' => [
                'temperature' => 28,
                'feels_like' => 30,
                'humidity' => 75,
                'wind_speed' => 12,
                'wind_direction' => 'W',
                'is_day' => true,
                'weather_code' => 1,
                'condition' => 'Pleasant',
                'icon' => 'wb_sunny',
                'updated_at' => Carbon::now(self::TIMEZONE)->format('g:i A T'),
            ],
            'days' => [],
            'location' => 'Alibaug, Maharashtra',
            'source' => 'fallback',
            'fallback' => true,
        ];
    }

    /**
     * Map Open-Meteo WMO weather codes to human-readable conditions.
     * Reference: https://open-meteo.com/en/docs (WMO Weather interpretation codes)
     */
    private function codeToCondition(?int $code): string
    {
        return match (true) {
            $code === 0 => 'Clear sky',
            \in_array($code, [1, 2], true) => 'Mostly sunny',
            $code === 3 => 'Overcast',
            \in_array($code, [45, 48], true) => 'Foggy',
            \in_array($code, [51, 53, 55], true) => 'Drizzle',
            \in_array($code, [56, 57], true) => 'Freezing drizzle',
            \in_array($code, [61, 63, 65], true) => 'Rain',
            \in_array($code, [66, 67], true) => 'Freezing rain',
            \in_array($code, [71, 73, 75, 77], true) => 'Snow',
            \in_array($code, [80, 81, 82], true) => 'Rain showers',
            \in_array($code, [85, 86], true) => 'Snow showers',
            \in_array($code, [95, 96, 99], true) => 'Thunderstorm',
            default => 'Pleasant',
        };
    }

    private function codeToIcon(?int $code, bool $isDay = true): string
    {
        return match (true) {
            $code === 0 && $isDay => 'wb_sunny',
            $code === 0 && !$isDay => 'bedtime',
            \in_array($code, [1, 2], true) && $isDay => 'partly_cloudy_day',
            \in_array($code, [1, 2], true) && !$isDay => 'partly_cloudy_night',
            $code === 3 => 'cloud',
            \in_array($code, [45, 48], true) => 'foggy',
            \in_array($code, [51, 53, 55, 56, 57], true) => 'rainy_light',
            \in_array($code, [61, 63, 65, 66, 67, 80, 81, 82], true) => 'rainy',
            \in_array($code, [71, 73, 75, 77, 85, 86], true) => 'ac_unit',
            \in_array($code, [95, 96, 99], true) => 'thunderstorm',
            default => 'wb_sunny',
        };
    }

    private function degreesToCompass(float $degrees): string
    {
        $directions = ['N', 'NE', 'E', 'SE', 'S', 'SW', 'W', 'NW'];
        $index = (int) round($degrees / 45) % 8;
        return $directions[$index];
    }

    /**
     * Returns "Best Time to Visit Alibaug" content for the dedicated page.
     * Static data — not driven by API.
     */
    public function seasonalGuide(): array
    {
        return [
            [
                'season' => 'Winter',
                'months' => 'November – February',
                'temp_range' => '15–32°C',
                'rating' => 'Peak Season',
                'rating_color' => 'emerald',
                'icon' => 'wb_sunny',
                'description' => 'Cool mornings, warm afternoons, and pleasant breezy evenings. The most popular months for villa stays, weddings, and weekend getaways. Book accommodations 2–3 weeks ahead, especially around Christmas and New Year.',
                'best_for' => ['Beaches', 'Watersports', 'Villa stays', 'Outdoor dining'],
            ],
            [
                'season' => 'Summer',
                'months' => 'March – May',
                'temp_range' => '24–36°C',
                'rating' => 'Good',
                'rating_color' => 'amber',
                'icon' => 'wb_sunny',
                'description' => 'Warm but tolerable due to coastal breeze. Sea swims feel refreshing. Ideal for early morning beach walks, fresh seafood, and pool villas. Avoid mid-day outdoor activities (12–4 PM).',
                'best_for' => ['Pool villas', 'Seafood', 'Sunsets', 'Watersports'],
            ],
            [
                'season' => 'Monsoon',
                'months' => 'June – September',
                'temp_range' => '23–30°C',
                'rating' => 'Off-Season',
                'rating_color' => 'sky',
                'icon' => 'rainy',
                'description' => 'Dramatic landscapes, lush green surroundings, and discounted stays. The sea turns rough — swimming is restricted, but the moody atmosphere is perfect for romantic getaways and rainy-day reading.',
                'best_for' => ['Romantic stays', 'Greenery', 'Discounts', 'Photography'],
            ],
            [
                'season' => 'Post-Monsoon',
                'months' => 'October',
                'temp_range' => '22–33°C',
                'rating' => 'Excellent',
                'rating_color' => 'emerald',
                'icon' => 'partly_cloudy_day',
                'description' => 'The sweet spot — green landscapes meet clear skies. Fewer crowds than winter peak, all amenities open, and the sea is calm enough for activities. A favorite among locals and repeat visitors.',
                'best_for' => ['Photography', 'Watersports', 'Trekking', 'Beaches'],
            ],
        ];
    }
}
