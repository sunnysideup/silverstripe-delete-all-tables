<?php

namespace Sunnysideup\DeleteAllTables;

use Silverstripe\ORM\DB;
use Silverstripe\ORM\BuildTask;

class DeleteAllTablesTask extends BuildTask
{
    protected $title = 'CAREFUL: delete all tables';

    protected $description = 'Delete all tables in the database - no backup - so please be super careful!';

    public function run($request)
    {
        $rows = DB::query('SHOW TABLES;');
        foreach($rows as $row) {
            if($row) {
                if(is_array($row)) {
                    foreach($row as $db => $table) {
                        FlushNow::do_flush('DELETING '.$table);
                        DB::query('DROP TABLE IF EXISTS "'.$table.'";');
                    }
                } else {
                    $table = $row['table'] ?? '';
                    if($table) {
                        FlushNow::do_flush('DELETING '.$table);
                        DB::query('DROP TABLE IF EXISTS "'.$table.'";');                        
                    }
                }
            }
        }
    }
}
