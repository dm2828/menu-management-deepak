<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MenuManagementController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\menuStatus;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('menu', [MenuManagementController::class, 'index'])->name('menu.list');
    Route::get('menu/create', [MenuManagementController::class, 'create'])->name('menu.create');
    Route::post('menu/store', [MenuManagementController::class, 'store'])->name('menu.store');
    Route::get('menu/display', [MenuManagementController::class, 'display'])->name('menu.display');//->middleware(menuStatus::class);
    Route::get('menu/edit/{id}', [MenuManagementController::class, 'edit'])->name('menu.edit');
    Route::post('menu/update/{id}', [MenuManagementController::class, 'update'])->name('menu.update');
    
    Route::delete('menu/delete/{id}', [MenuManagementController::class, 'destroy'])->name('menu.delete');
    Route::get('menu/data', [MenuManagementController::class, 'getData'])->name('menu.data');

});

Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'hi'])) {
        session(['applocale' => $locale]);
    }
    return redirect()->back();
});

require __DIR__.'/auth.php';
