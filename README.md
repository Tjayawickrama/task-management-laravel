# PawTasks: Task Management System (Laravel)

A simple task management application built with **Laravel**, **MySQL**, **Eloquent ORM** and **Blade** templates. Users can create, view, edit, delete and change the status of tasks, with search, status filtering and pagination. The interface uses a pet-care theme.

## Features

- Create, view, edit and delete tasks (delete asks for confirmation in a modal)
- Fields: title (required, max 255), description (optional), due date (optional), status, created/updated timestamps
- Statuses: Pending, In Progress, Completed
- Quick status change from the task details page
- Task list with title, status, due date and created date
- Search by title and filter by status (they work together)
- Pagination (10 per page, a window of 5 page numbers with previous/next arrows)
- Server-side validation with meaningful messages, shown next to each field
- Flash messages after create, update and delete
- Overdue highlighting for unfinished tasks past their due date
- Summary cards (all / pending / in progress / completed) that act as quick filters

## Requirements

- PHP 8.2 or higher
- Composer
- MySQL 8+ or MariaDB
- PHP extensions: `pdo_mysql`, `mbstring`, `openssl`, `fileinfo`, `ctype`, `json`

## Installation

```bash
# 1. Clone the repository and enter the project folder
git clone https://github.com/Tjayawickrama/task-management-laravel
cd task-manager

# 2. Install PHP dependencies
composer install

# 3. Create the environment file and application key
cp .env.example .env        # on Windows PowerShell: copy .env.example .env
php artisan key:generate
```

4. Create an empty MySQL database, for example:

```sql
CREATE DATABASE task_manager CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

5. Open `.env` and set the database section (make sure `DB_CONNECTION` is `mysql`, not `sqlite`):

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_manager
DB_USERNAME=root
DB_PASSWORD=
```

6. Create the tables and load demo data (90 sample tasks):

```bash
php artisan migrate --seed
```

7. Start the application:

```bash
php artisan serve
```

Open http://127.0.0.1:8000 in your browser. Make sure the MySQL server is running first.

> The page loads Tailwind CSS and the Poppins font from a CDN, so an internet connection is needed to see the styling.

## Running the tests

```bash
php artisan test
```

The tests use the `RefreshDatabase` trait. By default Laravel's `phpunit.xml` uses SQLite in memory, so the `pdo_sqlite` PHP extension must be enabled. Alternatively, point the test environment at a separate MySQL database such as `task_manager_test`.

## Project structure (main files)

| File | Purpose |
|---|---|
| `database/migrations/*_create_tasks_table.php` | Database schema |
| `app/Models/Task.php` | Eloquent model, casts, search/status scopes, `isOverdue()` |
| `app/Enums/TaskStatus.php` | The three allowed statuses with labels and badge colors |
| `app/Http/Requests/TaskRequest.php` | Validation rules and error messages |
| `app/Http/Controllers/TaskController.php` | Resource controller (CRUD, listing, status change) |
| `routes/web.php` | Resource route plus a PATCH route for status changes |
| `resources/views/layouts/app.blade.php` | Layout, flash messages, delete confirmation modal |
| `resources/views/tasks/*.blade.php` | Index, create, edit, show and the shared form partial |
| `resources/views/pagination/paw.blade.php` | Custom pagination view |
| `resources/views/components/*.blade.php` | Status badge and dog illustration components |
| `database/factories/TaskFactory.php` | Realistic demo data |

## Implementation decisions

**Migrations and Eloquent.** The schema is created with a migration so it is version controlled and can be recreated on any machine with one command. All database access goes through Eloquent, which gives readable queries and protects against SQL injection through parameter binding.

**Status stored as a string with a PHP backed enum.** The `status` column is a string with an index, and a `TaskStatus` enum defines the only valid values. I chose this over a MySQL `ENUM` column because adding a status later needs no schema change, and the enum gives type safety in PHP. The model casts `status` to the enum, so `$task->status` is always a valid `TaskStatus`.

**Validation in a Form Request.** `TaskRequest` holds all the rules, so the controller stays small and the same rules are reused for create and update. `Rule::enum(TaskStatus::class)` makes it impossible to save an invalid status, even if someone edits the form in the browser. The quick status-change endpoint validates with the same rule. Failed validation redirects back with the errors and the old input.

**Search, filter and pagination together.** `search()` and `status()` are query scopes on the model. Each uses `when()`, so its `WHERE` clause is only added when a value is present. The controller chains both scopes into a single query, then calls `paginate(10)->withQueryString()`. `withQueryString()` keeps `?search=...&status=...` in every pagination link, so changing page never loses the active filters.

**Resource controller and route-model binding.** `Route::resource` follows Laravel's conventions. Type-hinting `Task $task` in controller methods lets Laravel load the record and return a 404 automatically when it does not exist.

**Delete confirmation.** Deleting opens a confirmation modal before the DELETE request is sent. The request itself is a normal form with `@csrf` and `@method('DELETE')`.

**Feedback to the user.** Create, update, delete and status changes redirect with a flash message, displayed as a toast that disappears automatically.

**Interface.** Plain Blade with Tailwind CSS (CDN), no JavaScript framework. Small pieces of JavaScript are used for the delete modal, toast and keeping the scroll position when filtering.

## Assumptions

- Authentication is not required (as stated in the brief), so all tasks are shared and visible to everyone.
- Deleting a task is permanent (no soft deletes).
- A task is "overdue" when it has a due date in the past and its status is not Completed.
- Task titles do not need to be unique.
- Due dates are plain dates (no time of day).

## Challenges encountered

- **MySQL access denied for `root`.** phpMyAdmin and Laravel were rejected because the `root` account had a password set. I reset it by starting MariaDB temporarily with `skip-grant-tables`, fixing the account, and then removing that option again.
- **`could not find driver`.** The PHP installation did not have `pdo_mysql` enabled. I enabled `extension=pdo_mysql` and `extension=mysqli` in `php.ini`.
- **Laravel still used SQLite.** New Laravel versions default to `DB_CONNECTION=sqlite` in `.env`. I switched it to `mysql`, set the database name and ran `php artisan config:clear`.
- **Seeder run twice created duplicate demo data.** I use `php artisan migrate:fresh --seed` when I want a clean database.
- **Empty page after reseeding.** The browser still had a `?page=` number higher than the new number of pages. The controller now redirects to the last page when the requested page does not exist.
- **Filtering jumped the page to the top.** Fixed by remembering the scroll position before a filter or page change and restoring it after the reload.

## If authentication were added

I would add a `user_id` foreign key to `tasks`, scope queries to the logged-in user (`auth()->user()->tasks()`), create a `TaskPolicy` whose `update` and `delete` methods compare `$user->id` with `$task->user_id`, call `$this->authorize()` in the controller, and protect the routes with the `auth` middleware.

## Note on use of AI assistance

I used an AI assistant as a learning and debugging resource while building this project (for example for code examples and for diagnosing environment errors). I reviewed the code and can explain how each part works and the reasons behind the decisions above.