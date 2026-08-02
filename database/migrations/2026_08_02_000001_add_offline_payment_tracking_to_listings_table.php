<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            // Real Estate is billed offline. Recording who confirmed payment and
            // when turns the admin-panel "collect payment" badge from a reminder
            // into an enforceable gate with an audit trail.
            $table->timestamp('payment_received_at')->nullable()->after('approved_at');
            $table->foreignId('payment_recorded_by')->nullable()->after('payment_received_at')
                ->constrained('users')->nullOnDelete();
            $table->string('payment_note')->nullable()->after('payment_recorded_by');
        });
    }

    public function down(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->dropConstrainedForeignId('payment_recorded_by');
            $table->dropColumn(['payment_received_at', 'payment_note']);
        });
    }
};
