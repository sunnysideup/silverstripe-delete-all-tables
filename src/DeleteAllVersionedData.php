<?php

namespace Sunnysideup\DeleteAllTables;

use SilverStripe\Control\Director;
use SilverStripe\Dev\BuildTask;
use SilverStripe\ORM\DB;
use SilverStripe\PolyExecution\PolyOutput;
use Sunnysideup\Flush\FlushNowImplementor;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;

class DeleteAllVersionedData extends BuildTask
{
    protected static string $commandName = 'delete-all-versioned-data';

    protected string $title = 'CAREFUL: delete all versioned data';

    protected static string $description = 'Delete versioned data!';

    protected function execute(InputInterface $input, PolyOutput $output): int
    {
        if (! Director::isLive()) {
            $rows = DB::query('SHOW TABLES;');
            foreach ($rows as $row) {
                if ($row) {
                    if (is_array($row)) {
                        foreach ($row as $table) {
                            $this->truncateTable($output, $table);
                        }
                    } else {
                        $table = $row['table'] ?? '';
                        if ($table) {
                            $this->truncateTable($output, $table);
                        }
                    }
                }
            }

            $output->writeln('TRUNCATING ChangeSet');
            DB::query('TRUNCATE TABLE "ChangeSet";');
            $output->writeln('TRUNCATING ChangeSetItem');
            DB::query('TRUNCATE TABLE "ChangeSetItem";');
        } else {
            $output->writeln('You need to set the environment to TEST or DEV to run this task.');
        }

        return Command::SUCCESS;
    }

    private function truncateTable(PolyOutput $output, string $table): void
    {
        if ('_Versions' === substr($table, -1 * strlen('_Versions'))) {
            $output->writeln('TRUNCATING ' . $table);
            DB::query('TRUNCATE TABLE "' . $table . '";');
        }
    }
}
