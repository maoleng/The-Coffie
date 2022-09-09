<?php

namespace App\Console;

use App\Console\Commands\ResetCounterSystemMail;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        Commands\ResetCounterSystemMail::class,

    ];

    protected function schedule(Schedule $schedule)
    {
        $schedule->command(ResetCounterSystemMail::COMMAND)->daily();

    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
