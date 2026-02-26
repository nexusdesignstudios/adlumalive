<?php

use Illuminate\Support\Facades\Route;

Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [\App\Http\Controllers\AuthController::class, 'me']);
    Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout']);

    Route::apiResource('contents', \App\Http\Controllers\ContentController::class)->except(['index', 'show']);
    Route::apiResource('projects', \App\Http\Controllers\ProjectController::class)->except(['index', 'show']);
    Route::apiResource('team-members', \App\Http\Controllers\TeamMemberController::class)->except(['index', 'show']);
    Route::apiResource('testimonials', \App\Http\Controllers\TestimonialController::class)->except(['index', 'show']);
    Route::apiResource('trusted-brands', \App\Http\Controllers\TrustedBrandController::class)->except(['index', 'show']);
});

Route::apiResource('contents', \App\Http\Controllers\ContentController::class)->only(['index', 'show']);
Route::apiResource('projects', \App\Http\Controllers\ProjectController::class)->only(['index', 'show']);
Route::apiResource('team-members', \App\Http\Controllers\TeamMemberController::class)->only(['index', 'show']);
Route::apiResource('testimonials', \App\Http\Controllers\TestimonialController::class)->only(['index', 'show']);
Route::apiResource('trusted-brands', \App\Http\Controllers\TrustedBrandController::class)->only(['index', 'show']);
