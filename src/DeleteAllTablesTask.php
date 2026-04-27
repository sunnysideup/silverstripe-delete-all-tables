<?php

namespace Sunnysideup\DeleteAllTables;

use Symfony\Component\Console\Input\InputInterface;
use SilverStripe\Console\PolyOutput;
use SilverStripe\Control\Director;
use SilverStripe\Dev\BuildTask;
use SilverStripe\ORM\DB;
use Sunnysideup\Flush\FlushNowImplementor;

class DeleteAllTablesTask extends BuildTask
{
    protected string $title = 'CAREFUL: delete all tables';

    protected $description = 'Delete all tables in the database - no backup - so please be super careful!';

    protected function execute(InputInterface $input, PolyOutput $output): int
    {
        if (Director::isDev() || Director::is_cli()) {
            $rows = DB::query('SHOW TABLES;');
            foreach ($rows as $row) {
                if ($row) {
                    if (is_array($row)) {
                        foreach ($row as $table) {
                            $this->deleteTable($table);
                        }
                    } else {
                        $table = $row['table'] ?? '';
                        if ($table) {
                            $this->deleteTable($table);
                        }
                    }
                }
            }
        }

        return 0;
    }

    private function deleteTable(string $table)
    {
        FlushNowImplementor::do_flush('DELETING ' . $table);
        DB::query('DROP TABLE IF EXISTS "' . $table . '";');
    }
}
