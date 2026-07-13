<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('habibty:admin {email : The email address of an existing user}')]
#[Description('Grant Filament administrator access to an existing Habibty user.')]
class GrantAdminAccess extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $email = (string) $this->argument('email');

        $user = User::where('email', $email)->first();

        if (! $user) {
            $this->error("No user found for [{$email}]. Register the account first, then run this command again.");

            return self::FAILURE;
        }

        $user->forceFill([
            'is_admin' => true,
        ])->save();

        $this->info("{$user->name} can now access the Filament admin panel at /admin.");

        return self::SUCCESS;
    }
}
