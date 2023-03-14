<?php
namespace Blubird\DbTruncate\Console;

use App\Facades\Schema;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DBTruncateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:truncate {--except= : Tables to exclude from truncation (comma-separated)}';

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

        $this->comment('Disabling foreign key checks...');
        Schema::disableForeignKeyConstraints();

        foreach ($tableNames as $name) {
            $this->comment('Truncating '.$name);
            DB::table($name)->truncate();
            $this->info('Truncated '.$name);
        }

        $this->comment('Enabling foreign key checks...');
        Schema::enableForeignKeyConstraints();
        return 1;
    }
}
