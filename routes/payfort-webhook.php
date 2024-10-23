<?php

use Illuminate\Support\Facades\Route;
use Sevaske\Payfort\Http\Controllers\PayfortWebhookController;

if (\Sevaske\Payfort\Config::isWebhookFeedbackEnabled()) {
    Route::post(\Sevaske\Payfort\Config::getWebhookFeedbackUri(), [PayfortWebhookController::class, 'feedback'])
        ->middleware(\Sevaske\Payfort\Config::getWebhookFeedbackMiddlewares())
        ->name('payfort.webhook.feedback');
}

if (\Sevaske\Payfort\Config::isWebhookNotificationEnabled()) {
    Route::post(\Sevaske\Payfort\Config::getWebhookNotificationUri(), [PayfortWebhookController::class, 'notification'])
        ->middleware(\Sevaske\Payfort\Config::getWebhookNotificationMiddlewares())
        ->name('payfort.webhook.notification');
}
