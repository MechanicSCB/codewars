<?php

use App\Http\Controllers\KataController;
use App\Http\Controllers\RandomTestController;
use App\Http\Controllers\SampleController;
use App\Http\Controllers\SolutionController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\TrainController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::view('/test-js', 'test');

Route::get('/test', [TestController::class, 'test'])->name('test');

Route::controller(KataController::class)->group(function() {
    Route::get('/', 'index')->name('katas.index');
    Route::get('/katas/create','create')->name('katas.create');
    Route::get('/katas/{kata}/edit','edit')->name('katas.edit');
    Route::put('/katas/{kata}','update')->name('katas.update');
    Route::post('/katas','store')->name('katas.store');
    Route::get('/katas/{kata}', 'show')->name('katas.show');
    Route::get('/katas-get', 'getKatas')->name('katas.get');
});

Route::get('/katas/{kata}/train', [TrainController::class, 'train'])->name('katas.train');
Route::post('/katas/{kata}/train', [TrainController::class, 'attempt'])->name('katas.attempt');

// User routes
Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');

// update routes
Route::put('/samples/{sample}', [SampleController::class, 'update'])->name('samples.update');
// update solutions
Route::put('/solutions/rename-function/{kata}', [SolutionController::class, 'renameFunction'])->name('solutions.rename-function');
Route::post('/solutions/check/{solution?}', [SolutionController::class, 'check'])->name('solutions.check');
Route::post('/solutions/mass-check/{kata}', [SolutionController::class, 'massCheck'])->name('solutions.mass-check');
// create random args
Route::get('/create-random-tests/{kata?}', [RandomTestController::class, 'create'])->name('random-tests.create');
Route::get('/edit-random-tests/{random_test}', [RandomTestController::class, 'edit'])->name('random-tests.edit');
Route::put('/random-tests/{random_test}', [RandomTestController::class, 'update'])->name('random-tests.update');

// ----------  END MOD ---------------

// TEST CASES
Route::get('/cases', [SampleController::class, 'showTestCases'])->name('test_cases');


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});
