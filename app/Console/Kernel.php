<?php

namespace App\Console;

use App\Providers\MessagesServiceProvider;
use App\Providers\UploadServiceProvider;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;


class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            MessagesServiceProvider::sendPendingMessages();
        })->everyFiveMinutes();

        /**
         * Remove files that are soft deleted for more than 30 min
         */
        $schedule->call(function () {
            UploadServiceProvider::hardDeleteSoftDeletedFiles();
        })->everyThirtyMinutes();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
