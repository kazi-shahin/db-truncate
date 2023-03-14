# DB Truncate
This package is a simple way to truncate your database tables. Sometimes you need to truncate your database tables for testing purpose. This package will help you to truncate your database tables without running fresh migration. cause, re-running migration will take a lot of time. So, you might need to truncate your database tables.

You can also ignore some tables while truncating all the others. for example `migrations` table.
# Installation:

- `composer require blubird/db-truncate`

# Command

- `php artisan db:truncate`
- `php artisan db:truncate --except=migrations,users`
