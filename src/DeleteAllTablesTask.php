<?php

namespace Sunnysideup\DeleteAllTables;

use SilverStripe\Control\Director;
use SilverStripe\Dev\BuildTask;
use SilverStripe\ORM\DB;
use Sunnysideup\Flush\FlushNowImplementor;

class DeleteAllTablesTask extends BuildTask
{
    protected $title = 'CAREFUL: delete all tables';

    protected $description = 'Delete all tables in the database - no backup - so please be super careful!';

    public function run($request)
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
    }

    private function deleteTable(string $table)
    {
        FlushNowImplementor::do_flush('DELETING ' . $table);
        DB::query('DROP TABLE IF EXISTS "' . $table . '";');
    }
}
