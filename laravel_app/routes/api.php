<?php

use Illuminate\Support\Facades\Route;

Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);

Route::get('/login', function () {
    return response()->json(['message' => 'Unauthenticated.'], 401);
})->name('login');

Route::get('/debug-auth', function () {
    return response()->json([
        'user' => \Illuminate\Support\Facades\Auth::user(),
        'headers' => request()->headers->all(),
        'cookies' => request()->cookies->all(),
        'session_id' => session()->getId(),
        'xsrf_token' => request()->cookie('XSRF-TOKEN'),
    ]);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [\App\Http\Controllers\AuthController::class, 'me']);
    Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout']);
    Route::delete('/login', [\App\Http\Controllers\AuthController::class, 'logout']); // Support DELETE /api/login for logout
    Route::post('/settings/favicon', [\App\Http\Controllers\SettingsController::class, 'uploadFavicon']); // Favicon upload

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
