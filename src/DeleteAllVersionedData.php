<?php

namespace Sunnysideup\DeleteAllTables;

use SilverStripe\Control\Director;
use SilverStripe\Dev\BuildTask;
use SilverStripe\ORM\DB;
use Sunnysideup\Flush\FlushNow;

class DeleteAllVersionedData extends BuildTask
{
    protected $title = 'CAREFUL: delete all versioned data';

    protected $description = 'Delete versioned data.!';

    public function run($request)
    {
        if (Director::isDev()) {
            $rows = DB::query('SHOW TABLES;');
            foreach ($rows as $row) {
                if ($row) {
                    if (is_array($row)) {
                        foreach ($row as $db => $table) {
                            $this->truncateTable($table);
                        }
                    } else {
                        $table = $row['table'] ?? '';
                        if ($table) {
                            $this->truncateTable($table);
                        }
                    }
                }
            }
        }

        FlushNow::do_flush('TRUNCATING ChangeSet');
        DB::query('TRUNCATE TABLE "ChangeSet";');
        FlushNow::do_flush('TRUNCATING ChangeSetItem');
        DB::query('TRUNCATE TABLE "ChangeSetItem";');
    }

    private function truncateTable(string $table)
    {
        if ('_Versions' === substr($table, -1 * strlen('_Versions'))) {
            FlushNow::do_flush('TRUNCATING ' . $table);
            DB::query('TRUNCATE TABLE "' . $table . '";');
        }
    }
}
