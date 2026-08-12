<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('sitemap:generate')->dailyAt('03:00');
