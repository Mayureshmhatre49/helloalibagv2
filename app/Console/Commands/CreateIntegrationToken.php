<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

/**
 * Issues a least-privilege Sanctum token for a trusted integration partner
 * (e.g. the Alibag Tourism site pulling the public listings feed). The
 * token is scoped to a single ability and tied to a dedicated, non-human
 * "integration" user rather than any real admin/owner account.
 */
class CreateIntegrationToken extends Command
{
    protected $signature = 'integrations:create-token
        {name : A short slug identifying the consumer, e.g. alibag-tourism}
        {--ability=read:listings-public : The single Sanctum ability to grant}';

    protected $description = 'Create (or rotate) a scoped Sanctum personal access token for an external integration partner.';

    public function handle(): int
    {
        $name = Str::slug($this->argument('name'));
        $ability = $this->option('ability');

        $user = User::firstOrCreate(
            ['email' => "{$name}@integrations.local"],
            [
                'name' => "Integration: {$name}",
                'password' => Str::random(64),
                'role_id' => null,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // Idempotent: re-running rotates the token instead of piling up stale ones.
        $user->tokens()->where('name', $name)->delete();

        $plainTextToken = $user->createToken($name, [$ability])->plainTextToken;

        $this->info("Token created for '{$name}' with ability '{$ability}':");
        $this->line($plainTextToken);
        $this->warn('Store this now — it will not be shown again.');

        return self::SUCCESS;
    }
}
