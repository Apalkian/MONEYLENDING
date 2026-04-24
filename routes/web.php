<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\BorrowerController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Auth;
// Redirect home to login or dashboard
// Route::get('/', function () {
//     return Auth::check() ? redirect('/dashboard') : view('welcome');
// });

Route::get('/', function () {
    return redirect('/dashboard');
});

// Route::middleware(['auth'])->group(function () {
// Dashboard (Figure 19 & 20)
Route::get('/dashboard', [LoanController::class, 'index'])->name('dashboard');


Route::resource('borrowers', BorrowerController::class);

// Loan Operations (Figure 20)
Route::post('/loans', [LoanController::class, 'store'])->name('loans.store');

// Payment Tracking (Page 3 Logic)
Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');
// });
Route::post('/loans/{loan}/add-capital', [LoanController::class, 'addCapital'])->name('loans.addCapital');
// require __DIR__.'/auth.php';
