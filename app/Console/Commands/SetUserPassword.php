<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class SetUserPassword extends Command
{
    protected $signature = 'user:set-password
        {email : The user\'s email address}
        {--password= : The new password (omit to be prompted without echoing)}';

    protected $description = 'Set a user\'s password from the console.';

    public function handle(): int
    {
        $user = User::where('email', $this->argument('email'))->first();

        if (! $user) {
            $this->error("No user found with email {$this->argument('email')}.");
            return self::FAILURE;
        }

        $password = $this->option('password');

        if (! $password) {
            $password = $this->secret('New password (min 8 characters, input hidden)');
            $confirm = $this->secret('Confirm password');

            if ($password !== $confirm) {
                $this->error('Passwords did not match.');
                return self::FAILURE;
            }
        }

        $validator = Validator::make(
            ['password' => $password],
            ['password' => ['required', 'string', 'min:8']]
        );

        if ($validator->fails()) {
            $this->error($validator->errors()->first('password'));
            return self::FAILURE;
        }

        // 'password' is a hashed cast on the model, but hash explicitly so this
        // is correct regardless of cast configuration.
        $user->forceFill(['password' => Hash::make($password)])->save();

        $this->info("Password updated for {$user->name} <{$user->email}>.");

        return self::SUCCESS;
    }
}
