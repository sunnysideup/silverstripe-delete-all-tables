<?php

namespace Sunnysideup\DeleteAllTables;

use SilverStripe\Control\Director;
use SilverStripe\Dev\BuildTask;
use SilverStripe\ORM\DB;
use SilverStripe\PolyExecution\PolyOutput;
use Sunnysideup\Flush\FlushNowImplementor;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;

class DeleteAllTablesTask extends BuildTask
{
    protected static string $commandName = 'delete-all-tables';

    protected string $title = 'CAREFUL: delete all tables';

    protected static string $description = 'Delete all tables in the database - no backup - so please be super careful!';

    protected function execute(InputInterface $input, PolyOutput $output): int
    {
        if (Director::isDev() || Director::is_cli()) {
            $rows = DB::query('SHOW TABLES;');
            foreach ($rows as $row) {
                if ($row) {
                    if (is_array($row)) {
                        foreach ($row as $table) {
                            $this->deleteTable($output, $table);
                        }
                    } else {
                        $table = $row['table'] ?? '';
                        if ($table) {
                            $this->deleteTable($output, $table);
                        }
                    }
                }
            }
        }

        return Command::SUCCESS;
    }

    private function deleteTable(PolyOutput $output, string $table): void
    {
        $output->writeln('DELETING ' . $table);
        DB::query('DROP TABLE IF EXISTS "' . $table . '";');
    }
}
