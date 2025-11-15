<?php

use \Modules\Counseling\Http\Controllers\API\PresenceClassRoomAPIController;

Route::middleware('auth:sanctum')->get('/presences-list', [
    PresenceClassRoomAPIController::class,
    'index'
])->name('presences-list');

Route::middleware('auth:sanctum')->post('/presences-save', [
    PresenceClassRoomAPIController::class,
    'store'
])->name('presences-save');

Route::middleware('auth:sanctum')->get('/presences-list-teacher', [
    PresenceClassRoomAPIController::class,
    'presencesSummary'
])->name('presences-list-teacher');