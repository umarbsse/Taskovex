<?php

use App\Jobs\GenerateWeeklyReportJob;
use App\Jobs\SendTaskReminderJob;
use App\Models\TaskReminder;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function (): void {
    TaskReminder::query()
        ->whereNull('sent_at')
        ->where('remind_at', '<=', now())
        ->each(fn (TaskReminder $reminder) => SendTaskReminderJob::dispatch($reminder));
})->everyFiveMinutes();

Schedule::job(new GenerateWeeklyReportJob)->weeklyOn(1, '08:00');
