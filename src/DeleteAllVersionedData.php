<?php

namespace Sunnysideup\DeleteAllTables;

use SilverStripe\ORM\DB;
use SilverStripe\Dev\BuildTask;
use SilverStripe\Control\Director;
use Sunnysideup\Flush\FlushNow;

class DeleteAllVersionedData extends BuildTask
{
    protected $title = 'CAREFUL: delete all versioned data';

    protected $description = 'Delete versioned data.!';

    public function run($request)
    {
        if(Director::isDev() ) {
            $rows = DB::query('SHOW TABLES;');
            foreach($rows as $row) {
                if($row) {
                    if(is_array($row)) {
                        foreach($row as $db => $table) {
                            $this->truncateTable($table);
                        }
                    } else {
                        $table = $row['table'] ?? '';
                        if($table) {
                            $this->truncateTable($table);
                        }
                    }
                }
            }
        }
        $this->truncateTable('ChangeSet');
        $this->truncateTable('ChangeSetItem');
    }

    private function truncateTable(string $table)
    {
        if(substr($table, -1 * strlen('_Versions')) !== '_Versions') {
            FlushNow::do_flush('TRUNCATING '.$table);
            DB::query('TRUNCATE TABLE "'.$table.'";');
        }
    }
}
