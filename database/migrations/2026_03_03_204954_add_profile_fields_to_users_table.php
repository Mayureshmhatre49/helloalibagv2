<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'instagram')) {
                $table->string('instagram')->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'facebook')) {
                $table->string('facebook')->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'user_website')) {
                $table->string('user_website')->nullable()->after('email');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['instagram', 'facebook', 'user_website']);
        });
    }
};
