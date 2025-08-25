<?php

return array_filter([
    App\Providers\AppServiceProvider::class,
    // Collision é apenas para desenvolvimento
    class_exists('NunoMaduro\Collision\Adapters\Laravel\CollisionServiceProvider') 
        ? NunoMaduro\Collision\Adapters\Laravel\CollisionServiceProvider::class 
        : null,
]);
