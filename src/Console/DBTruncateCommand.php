<?php
namespace Blubird\DbTruncate\Console;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;

class DBTruncateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:truncate {--except= : Tables to exclude from truncation (comma-separated)} {--seed : If want to run seed after the truncate} {--only= : Tables to include in truncation (comma-separated)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'truncates all table except the ones specified in the --except option';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $tableNames = Schema::getConnection()->getDoctrineSchemaManager()->listTableNames();

        if ($this->option('except')) {
            $excepts = $this->option('except');
            $this->comment('Skipping tables: ' . $excepts);
            $except = explode(',', $excepts);
            $tableNames = array_diff($tableNames, $except);
        }

        if ($this->option('only')) {
            $onlys = $this->option('only');
            $this->comment('Only truncating tables: ' . $onlys);
            $only = explode(',', $onlys);
            $tableNames = array_intersect($tableNames, $only);
        }

        $this->comment('Disabling foreign key checks...');
        Schema::disableForeignKeyConstraints();

        foreach ($tableNames as $name) {
            $this->comment('Truncating '.$name);
            DB::table($name)->truncate();
            $this->info('Truncated '.$name);
        }

        $this->comment('Enabling foreign key checks...');
        Schema::enableForeignKeyConstraints();
        $this->comment('Truncate completed.');

        if ($this->isSeedEnabled()) {
            $this->dbSeed();
        }
        return 1;
    }

    /**
     * Run the db:seed command
     *
     * @return void
     */
    protected function dbSeed(): void
    {
        $this->info('Executing seeds...');
        $this->call('db:seed');
        $this->info('Seeding completed.');
    }

    /**
     * Checks if seed flag is passed
     *
     * @return boolean
     */
    protected function isSeedEnabled(): bool
    {
        return $this->option('seed') == true;
    }
}
