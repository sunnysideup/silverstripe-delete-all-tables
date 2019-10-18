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
            $table = $row['table'];
            DB::query('DROP TABLE IF EXISTS "'.$table.'";');
        }
    }
}
