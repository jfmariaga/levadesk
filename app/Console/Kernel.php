<?php

namespace App\Console;

use App\Jobs\CheckAnsVencimiento;
use App\Jobs\GestionarAnsJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->job(new CheckAnsVencimiento)->everyMinute();
        $schedule->job(new GestionarAnsJob)->everyMinute();
        $schedule->command('usuarios:notificar-sin-area')->weeklyOn(1, '9:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
