<?php

namespace App\Console\Commands;

use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CreateAdminUser extends Command
{
    protected $signature = 'user:create-admin
        {email : Login email for the new admin}
        {name : Display name}
        {--password= : Set a specific password (omit to auto-generate and print it)}
        {--promote : If the email already exists, promote that account to admin instead of aborting}';

    protected $description = 'Create a new user with the admin role, or promote an existing account to admin with --promote. Safe to re-run — skips if the email already exists and --promote wasn\'t given.';

    public function handle(): int
    {
        $email = strtolower(trim($this->argument('email')));

        $existing = User::with('role')->where('email', $email)->first();
        if ($existing) {
            if (! $this->option('promote')) {
                $currentRole = $existing->role?->slug ?? 'no role';
                $this->warn("A user with email {$email} already exists (role: {$currentRole}) — no changes made.");
                $this->line("Re-run with --promote to grant this account admin, or use `php artisan user:2fa` / `php artisan user:set-password` to manage it instead.");
                return self::FAILURE;
            }

            if ($existing->role?->slug === 'admin') {
                $this->info("{$existing->name} <{$existing->email}> is already an admin — nothing to do.");
                return self::SUCCESS;
            }

            $adminRole = Role::getBySlug('admin');
            if (! $adminRole) {
                $this->error('No "admin" role found in the roles table — aborting.');
                return self::FAILURE;
            }

            $fromRole = $existing->role?->slug ?? 'no role';
            $existing->update(['role_id' => $adminRole->id]);
            $this->info("Promoted {$existing->name} <{$existing->email}> from '{$fromRole}' to admin.");

            return self::SUCCESS;
        }

        $adminRole = Role::getBySlug('admin');
        if (! $adminRole) {
            $this->error('No "admin" role found in the roles table — aborting.');
            return self::FAILURE;
        }

        $password = $this->option('password') ?: Str::password(14);

        $user = User::create([
            'name' => $this->argument('name'),
            'email' => $email,
            'password' => Hash::make($password),
            'role_id' => $adminRole->id,
        ]);

        // email_verified_at isn't mass-assignable (not in $fillable) — set it
        // directly so this account isn't blocked by any email-verification gate.
        $user->forceFill(['email_verified_at' => now()])->save();

        $this->info("Admin account created: {$user->name} <{$user->email}>");

        if (! $this->option('password')) {
            $this->newLine();
            $this->warn('Temporary password (share this once, ask them to change it after first login):');
            $this->line("  {$password}");
        }

        return self::SUCCESS;
    }
}
