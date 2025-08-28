<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Agendamento da sincronização de centros de custo com API Omie
Schedule::command('omie:sync-centros-custo')
    ->dailyAt('02:00')
    ->withoutOverlapping()
    ->runInBackground()
    ->onSuccess(function () {
        \Log::info('Sincronização de centros de custo executada com sucesso');
    })
    ->onFailure(function () {
        \Log::error('Falha na sincronização automática de centros de custo');
    });
