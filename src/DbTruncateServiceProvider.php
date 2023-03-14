<?php
namespace Blubird\DbTruncate;

use Illuminate\Support\ServiceProvider;
use Blubird\DbTruncate\Console\DBTruncateCommand;
class DbTruncateServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->commands([
            DBTruncateCommand::class,
        ]);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}