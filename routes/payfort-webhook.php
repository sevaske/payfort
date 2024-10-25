<?php

use Illuminate\Support\Facades\Route;

if (\Sevaske\Payfort\Config::isWebhookFeedbackEnabled()) {
    Route::post(\Sevaske\Payfort\Config::getWebhookFeedbackUri(), [\Sevaske\Payfort\Config::getWebhookController(), 'feedback'])
        ->middleware(\Sevaske\Payfort\Config::getWebhookFeedbackMiddlewares())
        ->name('payfort.webhook.feedback');
}

if (\Sevaske\Payfort\Config::isWebhookNotificationEnabled()) {
    Route::post(\Sevaske\Payfort\Config::getWebhookNotificationUri(), [\Sevaske\Payfort\Config::getWebhookController(), 'notification'])
        ->middleware(\Sevaske\Payfort\Config::getWebhookNotificationMiddlewares())
        ->name('payfort.webhook.notification');
}
