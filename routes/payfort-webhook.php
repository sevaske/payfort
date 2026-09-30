<?php

use Illuminate\Support\Facades\Route;
use Sevaske\Payfort\Config;

if (Config::isWebhookFeedbackEnabled()) {
    Route::post(Config::getWebhookFeedbackUri(), [Config::getWebhookController(), 'feedback'])
        ->middleware(Config::getWebhookFeedbackMiddlewares())
        ->name('payfort.webhook.feedback');
}

if (Config::isWebhookNotificationEnabled()) {
    Route::post(Config::getWebhookNotificationUri(), [Config::getWebhookController(), 'notification'])
        ->middleware(Config::getWebhookNotificationMiddlewares())
        ->name('payfort.webhook.notification');
}
