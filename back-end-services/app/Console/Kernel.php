<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\RouterCommand::class,
        Commands\OCRCommand::class,
        Commands\HelperCommand::class,
        Commands\Stats\CFDAItemsAmountCommand::class,
        Commands\Stats\CFDASPUAmountCommand::class,
        Commands\SeedSearchDocumentsCommand::class,
        Commands\SearchDocumentsTestCommand::class,
        Commands\RefreshPrefixCoreSuffixCommand::class,
        Commands\Stats\CleanDataAmountCommand::class,
        Commands\CleanItemByAICommand::class,
        Commands\RebuildRoutersCommand::class,
        Commands\RebuildDictCommand::class,
        Commands\FuckingDebugCommand::class,
        Commands\CFDAIdentityCommand::class,
        Commands\DownloadCFDAPdfCommand::class,
        Commands\LogWriterCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->exec('stats:cfda-items-amount')->at('00:00');
    }
}
