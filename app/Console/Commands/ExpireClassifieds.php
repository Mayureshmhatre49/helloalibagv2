<?php

namespace App\Console\Commands;

use App\Services\ClassifiedService;
use Illuminate\Console\Command;

class ExpireClassifieds extends Command
{
    protected $signature = 'classifieds:expire';

    protected $description = 'Mark active marketplace items past their expiry date as expired';

    public function handle(ClassifiedService $service): int
    {
        $count = $service->expireOverdue();
        $this->info("Expired {$count} marketplace item(s).");

        return self::SUCCESS;
    }
}
