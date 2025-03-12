<?php

use App\Http\Controllers\AbConversionDataController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\AbTestDataController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

//User Test Data
Route::get('user-data', function () {
    return Inertia::render('UserData');
})->middleware(['auth', 'verified'])->name('user-data');
Route::get('user-data/{user_session_id}', function ($user_session_id) {
    return Inertia::render('IndividualUserData', [
        'user_session_id' => $user_session_id, // Passing the `id` to the Inertia component
    ]);
})->middleware(['auth', 'verified'])->name('user-data');
Route::get('/ab-test-data', [AbTestDataController::class, 'index']);
Route::get('/ab-test-data/{id}', [AbTestDataController::class, 'show']);

//Conversion Data
Route::get('conversion-data', function () {
    return Inertia::render('ConversionData');
})->middleware(['auth', 'verified'])->name('conversion-data');
Route::get('/ab-conversion-data', [AbConversionDataController::class, 'index']);

//A/B Tests
Route::get('ab-tests', function () {
    return Inertia::render('AbTestsDisplay');
})->middleware(['auth', 'verified'])->name('ab-tests');
Route::get('ab-tests/{test_id}', function ($test_id) {
    return Inertia::render('IndividualAbTestData', [
        'test_id' => $test_id, // Passing the `id` to the Inertia component
    ]);
})->middleware(['auth', 'verified'])->name('user-data');
Route::get('/list-ab-tests', [AbTestDataController::class, 'getAbTestsDistinct']);
Route::get('/list-ab-tests/{test_id}', [AbTestDataController::class, 'showAbTest']);

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';