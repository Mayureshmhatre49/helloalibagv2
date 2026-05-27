<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('areas', function (Blueprint $table) {
            $table->decimal('latitude', 10, 7)->nullable()->after('tagline');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
        });

        // Seed coordinates for the 10 known Alibaug areas
        $coordinates = [
            'mandwa'        => [18.7847, 72.8693],
            'kihim'         => [18.7283, 72.8639],
            'alibaug-town'  => [18.6414, 72.8722],
            'awas'          => [18.6883, 72.8628],
            'nagaon'        => [18.5944, 72.8758],
            'versoli'       => [18.6611, 72.8675],
            'zirad'         => [18.7036, 72.8606],
            'kashid'        => [18.4419, 72.9069],
            'sasawane'      => [18.7547, 72.8639],
            'dhokawade'     => [18.5719, 72.8956],
        ];

        foreach ($coordinates as $slug => [$lat, $lng]) {
            DB::table('areas')->where('slug', $slug)->update([
                'latitude' => $lat,
                'longitude' => $lng,
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('areas', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude']);
        });
    }
};
