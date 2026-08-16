<?php

namespace App\Services;

use App\Models\MapApiSetting;
use App\Models\MapApiUsageLog;
use Illuminate\Support\Facades\DB;

class MapApiService
{
    private ?MapApiSetting $settings = null;

    public const LABELS = [
        MapApiUsageLog::TYPE_MAP_LOAD => 'Map page loads',
        MapApiUsageLog::TYPE_LOCATION_SEARCH => 'Location searches',
    ];

    /**
     * The singleton settings row, cached per request (this service is bound
     * as a singleton in AppServiceProvider).
     */
    public function settings(): MapApiSetting
    {
        if ($this->settings === null) {
            $this->settings = MapApiSetting::first() ?? MapApiSetting::create([
                'enabled' => false,
                'monthly_free_limit_map_loads' => 10000,
                'monthly_free_limit_search' => 10000,
                'auto_disable_threshold_percent' => 95,
            ]);
        }

        return $this->settings;
    }

    public function isEnabled(): bool
    {
        $settings = $this->settings();

        return $settings->enabled && ! empty($settings->api_key);
    }

    public function apiKey(): ?string
    {
        return $this->settings()->api_key;
    }

    public function mapId(): ?string
    {
        return $this->settings()->map_id;
    }

    /**
     * Record one Google API call of the given type and auto-disable the
     * integration if this pushes month-to-date usage over the configured
     * safety threshold.
     */
    public function recordHit(string $type): void
    {
        DB::statement(
            'INSERT INTO map_api_usage_logs (usage_date, usage_type, hits, created_at, updated_at)
             VALUES (?, ?, 1, NOW(), NOW())
             ON DUPLICATE KEY UPDATE hits = hits + 1, updated_at = NOW()',
            [now()->toDateString(), $type]
        );

        $this->autoDisableIfOverThreshold();
    }

    public function monthToDateUsage(string $type): int
    {
        return (int) MapApiUsageLog::where('usage_type', $type)
            ->whereBetween('usage_date', [now()->startOfMonth()->toDateString(), now()->endOfMonth()->toDateString()])
            ->sum('hits');
    }

    /**
     * Used/limit/percent for each usage type, for the admin usage table.
     */
    public function usageSummary(): array
    {
        $settings = $this->settings();
        $limits = [
            MapApiUsageLog::TYPE_MAP_LOAD => $settings->monthly_free_limit_map_loads,
            MapApiUsageLog::TYPE_LOCATION_SEARCH => $settings->monthly_free_limit_search,
        ];

        return collect($limits)->map(function (int $limit, string $type) {
            $used = $this->monthToDateUsage($type);

            return [
                'type' => $type,
                'label' => self::LABELS[$type] ?? $type,
                'used' => $used,
                'limit' => $limit,
                'percent' => $limit > 0 ? min(100, (int) round($used / $limit * 100)) : 0,
            ];
        })->values()->all();
    }

    private function autoDisableIfOverThreshold(): void
    {
        $settings = $this->settings();

        if (! $settings->enabled) {
            return;
        }

        $limits = [
            MapApiUsageLog::TYPE_MAP_LOAD => $settings->monthly_free_limit_map_loads,
            MapApiUsageLog::TYPE_LOCATION_SEARCH => $settings->monthly_free_limit_search,
        ];

        foreach ($limits as $type => $limit) {
            if ($limit <= 0) {
                continue;
            }

            $used = $this->monthToDateUsage($type);

            if ($used >= $limit * $settings->auto_disable_threshold_percent / 100) {
                $settings->update([
                    'enabled' => false,
                    'auto_disabled_at' => now(),
                    'auto_disabled_reason' => sprintf(
                        'Reached %d%% of the free "%s" quota (%d of %d this month).',
                        $settings->auto_disable_threshold_percent,
                        self::LABELS[$type] ?? $type,
                        $used,
                        $limit
                    ),
                ]);

                break;
            }
        }
    }
}
