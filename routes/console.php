<?php

use Carbon\Carbon;
use App\Models\Trip;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Schedule::call(function () {
    $now = Carbon::now('UTC');

    Trip::withoutTimestamps(function () use ($now) {
        Trip::where([['is_closed', false], ['return_at', '<=', $now]])->update(['is_closed' => true]);
    });
})->dailyAt('00:10');
