<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Mantenimiento automático de la subred RobCo (cada 15 minutos)
\Illuminate\Support\Facades\Schedule::command('robco:purge-rooms')->everyFifteenMinutes();

