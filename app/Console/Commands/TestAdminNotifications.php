<?php

namespace App\Console\Commands;

use App\Models\Role;
use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestAdminNotifications extends Command
{
    protected $signature = 'admin:test-notifications
        {--email= : Only test this one admin email, instead of every admin}';

    protected $description = 'Send a real test email and create a test in-app notification for every admin, to verify the notification pipeline actually reaches their inbox.';

    public function handle(): int
    {
        $query = User::whereHas('role', fn ($q) => $q->where('slug', 'admin'));

        if ($email = $this->option('email')) {
            $query->where('email', $email);
        }

        $admins = $query->get();

        if ($admins->isEmpty()) {
            $this->error('No matching admin accounts found.');
            return self::FAILURE;
        }

        $this->info('Queue driver: ' . config('queue.default') . ' | Mail driver: ' . config('mail.default'));
        $this->newLine();

        foreach ($admins as $admin) {
            $this->line("→ {$admin->name} <{$admin->email}>");

            // 1) In-app notification (shows in the bell icon + /notifications)
            try {
                UserNotification::create([
                    'user_id' => $admin->id,
                    'type' => 'test',
                    'title' => 'Test notification',
                    'message' => 'This is a test notification sent at ' . now()->format('d M Y, h:i A') . ' to confirm the notification pipeline is working.',
                    'action_url' => route('notifications.index'),
                ]);
                $this->line('  ✓ in-app notification created');
            } catch (\Throwable $e) {
                $this->error('  ✗ in-app notification failed: ' . $e->getMessage());
            }

            // 2) Real email via the configured mailer
            try {
                Mail::raw(
                    "This is a test email sent at " . now()->format('d M Y, h:i A') . " to confirm Hello Alibaug's admin notifications are reaching your inbox.\n\nIf you received this, email delivery is working correctly for your account.",
                    function ($message) use ($admin) {
                        $message->to($admin->email)->subject('Hello Alibaug — Admin notification test');
                    }
                );
                $this->line('  ✓ test email sent (check inbox + spam)');
            } catch (\Throwable $e) {
                $this->error('  ✗ email failed: ' . $e->getMessage());
            }

            $this->newLine();
        }

        $pendingJobs = \Illuminate\Support\Facades\DB::table('jobs')->count();
        if ($pendingJobs > 0) {
            $this->warn("Note: {$pendingJobs} job(s) still queued in the 'jobs' table.");
            $this->warn("If QUEUE_CONNECTION is not 'sync', run `php artisan queue:work --stop-when-empty` to send them now.");
        }

        return self::SUCCESS;
    }
}
