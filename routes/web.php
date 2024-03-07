<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RedirectController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', function () {
    return view('home');
})->name('home');

Route::get('/redirects', [RedirectController::class, 'index'])->name('redirects.index');
Route::get('/redirects/create', [RedirectController::class, 'create'])->name('redirects.create');
Route::post('/redirects', [RedirectController::class, 'store'])->name('redirects.store');
Route::get('/redirects/{code}/edit', [RedirectController::class, 'edit'])->name('redirects.edit');
Route::put('/redirects/{redirect}', [RedirectController::class, 'update'])->name('redirects.update');
Route::delete('/redirects/{code}', [RedirectController::class, 'destroy'])->name('redirects.destroy');
Route::put('/redirects/{redirect}/toggle', [RedirectController::class, 'toggle'])->name('redirects.toggle');
Route::get('/redirects/{code}', [RedirectController::class, 'redirect'])->name('redirects.redirect');
Route::get('/redirects/{redirect}/stats', [RedirectController::class, 'stats'])->name('redirects.stats');






