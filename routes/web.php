<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\InstitutionController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware([
    'auth',
    'role:SuperAdmin'
])->group(function(){

    Route::resource('users',UserController::class);

});

// Route::middleware([
//     'auth',
//     'role:Teacher'
// ])->group(function(){

//     Route::get();

// });

// Route::middleware([
//     'auth',
//     'permission:institution.create'
// ])->group(function(){

//     Route::get();

// });
Route::middleware([
    'auth'
])->group(function () {

    Route::resource(
        'institutions',
        InstitutionController::class
    );

});

require __DIR__.'/auth.php';
