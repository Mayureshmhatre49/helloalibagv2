<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * The Alibaug locality is spelled "Varsoli", not "Versoli". Fix the display
     * name on the existing area row. The slug ("versoli") is left unchanged so
     * existing URLs / links keep working.
     */
    public function up(): void
    {
        DB::table('areas')->where('name', 'Versoli')->update(['name' => 'Varsoli']);
    }

    public function down(): void
    {
        DB::table('areas')->where('name', 'Varsoli')->update(['name' => 'Versoli']);
    }
};
