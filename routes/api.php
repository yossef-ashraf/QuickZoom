<?php
// quickzoom/routes/api.php
use Illuminate\Support\Facades\Route;
use QuickZoom\Controllers\ZoomController;
use QuickZoom\Controllers\ZoomWebhookController;
use QuickZoom\Middleware\VerifyZoomWebhook;

Route::prefix(config('quickzoom.routes.prefix', 'api/zoom'))
    ->middleware(config('quickzoom.routes.middleware', ['api', 'auth:sanctum']))
    ->group(function () {
        Route::get('meetings', [ZoomController::class, 'index']);
        Route::post('meetings', [ZoomController::class, 'store']);
        Route::get('meetings/{id}', [ZoomController::class, 'show']);
        Route::put('meetings/{id}', [ZoomController::class, 'update']);
        Route::delete('meetings/{id}', [ZoomController::class, 'destroy']);
        Route::put('meetings/{id}/end', [ZoomController::class, 'endMeeting']);
        Route::get('meetings/{id}/participants', [ZoomController::class, 'participants']);
        Route::get('meetings/{id}/recordings', [ZoomController::class, 'recordings']);
        Route::post('meetings/{id}/register', [ZoomController::class, 'registerParticipant']);
    });

Route::post(
    config('quickzoom.routes.webhook_path', 'webhook'),
    [ZoomWebhookController::class, 'handle']
)->middleware(config('quickzoom.routes.webhook_middleware', ['api']));