<?php

use App\Http\Controllers\Admin\Api\ApplicationController;
use App\Http\Controllers\Admin\Api\BotUserController;
use App\Http\Controllers\Admin\Api\ImageController;
use Illuminate\Support\Facades\Route;

// Delete image
Route::delete('/delete/image/{folderName}/{fileName}', [ImageController::class, 'deletePhoto']);

// Bot user
Route::put('/bot-users/{botUser}/is-active', [BotUserController::class, 'isActive']);

// Application
Route::put('/application/{application}/is-reviewed', [ApplicationController::class, 'isReviewed']);
