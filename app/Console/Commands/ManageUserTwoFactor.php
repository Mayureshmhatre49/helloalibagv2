<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ManageUserTwoFactor extends Command
{
    protected $signature = 'user:2fa
        {email : The user\'s email address}
        {--disable : Turn 2FA off for this user (they will not be asked for a code)}
        {--enable : Turn 2FA back on (user will be prompted to set it up at next admin login)}
        {--reset : Keep 2FA required but clear the existing secret so they can re-enrol}
        {--status : Just show the current 2FA state}';

    protected $description = 'Enable, disable, or reset two-factor authentication for a user.';

    public function handle(): int
    {
        $user = User::where('email', $this->argument('email'))->first();

        if (! $user) {
            $this->error("No user found with email {$this->argument('email')}.");
            return self::FAILURE;
        }

        $describe = function (User $u): string {
            if (! $u->two_factor_enabled) {
                return 'DISABLED (not required)';
            }
            return $u->two_factor_secret
                ? 'ENABLED — secret set' . ($u->two_factor_confirmed_at ? ' & confirmed' : ', not yet confirmed')
                : 'ENABLED — no secret yet (will be prompted to set up)';
        };

        if ($this->option('status') || (! $this->option('disable') && ! $this->option('enable') && ! $this->option('reset'))) {
            $this->info("{$user->name} <{$user->email}> [{$user->role?->slug}]");
            $this->line('  2FA: ' . $describe($user));
            $this->newLine();
            $this->line('  Use --disable, --enable or --reset to change it.');
            return self::SUCCESS;
        }

        if ($this->option('disable')) {
            $user->update([
                'two_factor_enabled' => false,
                'two_factor_secret' => null,
                'two_factor_confirmed_at' => null,
            ]);
            $this->info("2FA disabled for {$user->email}. They can now sign in without a code.");
        } elseif ($this->option('enable')) {
            $user->update(['two_factor_enabled' => true]);
            $this->info("2FA re-enabled for {$user->email}." . ($user->two_factor_secret ? '' : ' They will be asked to set it up at next admin login.'));
        } elseif ($this->option('reset')) {
            $user->update([
                'two_factor_enabled' => true,
                'two_factor_secret' => null,
                'two_factor_confirmed_at' => null,
            ]);
            $this->info("2FA secret cleared for {$user->email}. They will re-enrol at next admin login.");
        }

        $this->line('  Now: ' . $describe($user->fresh()));

        return self::SUCCESS;
    }
}
