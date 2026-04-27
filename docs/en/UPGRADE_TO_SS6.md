# Upgrade to SilverStripe 6

## Requirements

- **SilverStripe Framework**: Minimum version `^6.0` (previously `^4.0 || ^5.0`)

## API Changes

### BuildTask Migration to CLI Commands

⚠️ **Breaking Change**: Both `DeleteAllTablesTask` and `DeleteAllVersionedData` have migrated from the traditional BuildTask pattern to the new CLI command pattern.

#### Method Signature Changes

- Replace `public function run($request)` with `protected function execute(InputInterface $input, PolyOutput $output): int`
- Return `Command::SUCCESS` at the end of `execute()` methods
- Add `protected static string $commandName` property to define the CLI command name

#### Property Type Declarations

- Convert `$title` to `protected string $title`
- Convert `$description` to `protected static string $description`
- Add `$commandName` property for CLI command registration

#### New Imports Required

```php
use SilverStripe\PolyExecution\PolyOutput;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
```

#### Output Handling

⚠️ Replace `FlushNowImplementor::do_flush()` calls with `$output->writeln()`:
- Pass `PolyOutput $output` as first parameter to methods that need to write output
- Update method signatures to accept `$output` parameter

## Removed Features

⚠️ **Dependency Removed**: `Sunnysideup\Flush\FlushNowImplementor` is no longer used for output

## Command Names

The tasks are now available as CLI commands:
- `DeleteAllTablesTask`: `sake delete-all-tables`
- `DeleteAllVersionedData`: `sake delete-all-versioned-data`

🔍 **Note**: Verify that any custom code calling these tasks directly has been updated to use the new command pattern or CLI execution method.
