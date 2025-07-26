<?php

use Illuminate\Support\Facades\Route;

if (config('payfort.webhook.feedback.enabled')) {
    Route::post(config('payfort.webhook.feedback.uri'), [config('payfort.webhook.controller'), 'feedback'])
        ->middleware(config('payfort.webhook.feedback.middlewares'))
        ->name('payfort.webhook.feedback');
}

if (config('payfort.webhook.notification.enabled')) {
    Route::post(config('payfort.webhook.notification.uri'), [config('payfort.webhook.controller'), 'notification'])
        ->middleware(config('payfort.webhook.notification.middlewares'))
        ->name('payfort.webhook.notification');
}
