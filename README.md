# Taskovex

Taskovex is a full-stack smart task and workflow manager for organizing projects, tasks, subtasks, comments, attachments, reminders, and activity history in one focused workspace.

## Features

- Project management with owned workspaces
- Task boards with `todo`, `in_progress`, `review`, and `completed` columns
- Drag-and-drop Kanban status updates
- Task priorities: `low`, `medium`, `high`, and `urgent`
- Assigned users, due dates, comments, attachments, and subtasks
- Task events for creation, assignment, and completion
- Notification and activity log listeners
- Queued task reminders and weekly reports
- Policy-based authorization and form request validation
- Demo seed data for local development
- Feature tests for key project, task, auth, and event flows

## Tech Stack

- Laravel 12
- Vue 3
- Inertia.js
- Tailwind CSS
- Vite
- Laravel Breeze authentication
- SQLite by default for local development

## Screenshots

Add screenshots to `docs/screenshots` and reference them here.

- Dashboard: `docs/screenshots/dashboard.png`
- Project board: `docs/screenshots/project-board.png`
- Task details: `docs/screenshots/task-details.png`

## Requirements

- PHP 8.2 or newer
- Composer 2
- Node.js 20 or newer
- npm
- SQLite, MySQL, PostgreSQL, or another Laravel-supported database

## Installation

```bash
git clone <repository-url>
cd Taskovex
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm run dev
php artisan serve
```

For a WAMP setup that exposes the public directory at `http://localhost/taskovex/`, keep `APP_URL` and `ASSET_URL` set to `http://localhost/taskovex`.

The seeded demo user is:

```text
Email: demo@taskovex.test
Password: password
```

## Queue And Scheduler

Taskovex uses queued jobs for task reminders and weekly reports. Run a queue worker during development or in production:

```bash
php artisan queue:work
```

Run the Laravel scheduler from cron in production:

```bash
* * * * * cd /path/to/Taskovex && php artisan schedule:run >> /dev/null 2>&1
```

For local scheduler testing:

```bash
php artisan schedule:work
```

## Testing

```bash
php artisan test
```

To run the frontend production build:

```bash
npm run build
```

## Folder Structure

```text
app/
  Actions/Tasks        Task workflow actions
  Events               Task lifecycle events
  Http/Controllers     Inertia and workflow controllers
  Http/Requests        Validation requests
  Http/Resources       Project and task resources
  Jobs                 Reminder and report jobs
  Listeners            Notification and activity listeners
  Models               Taskovex domain models
  Policies             Project and task authorization
  Services             Task, notification, and activity services
database/
  factories            Model factories
  migrations           Schema definitions
  seeders              Demo workspace data
resources/js/
  Components           Reusable Vue components
  Layouts              Authenticated and guest layouts
  Pages                Inertia pages
  types                TypeScript interfaces
routes/
  web.php              Web routes
  console.php          Scheduler entries
tests/
  Feature              Feature and workflow tests
```

## Contributing

Pull requests are welcome. Keep changes focused, include tests for behavior changes, and follow the existing Laravel and Vue conventions.

## License

Taskovex is open-sourced software licensed under the MIT license.
