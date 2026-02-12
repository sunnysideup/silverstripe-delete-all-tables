# tl;dr

There are two tasks here:

- delete all tables (nukes your entire project!)
- delete all older versions (great for a spring-clean)

## Usage

- Visit `/dev/tasks/DeleteAllTablesTask` in a dev environment to drop every table.
- Run `vendor/bin/sake dev/tasks/DeleteAllVersionedDataTask` to remove historic versions only.

Always back up your database before running either task.
