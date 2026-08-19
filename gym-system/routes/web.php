<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Controllers
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CheckInController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\TrainerController;
use App\Http\Controllers\MemberTrainerController;
use App\Http\Controllers\MembershipController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(['verify' => true]);

// Redirect based on User Role
Route::get('/home', function () {
    if (Auth::user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    } elseif (Auth::user()->isTrainer()) {
        return redirect()->route('trainer.dashboard');
    } else {
        return redirect()->route('member.dashboard');
    }
})->name('home');

// ==========================================
// ADMIN ROUTES
// ==========================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/scan/{token}', [CheckInController::class, 'scan'])->name('checkin.scan');
});

// ==========================================
// TRAINER ROUTES
// ==========================================
Route::middleware(['auth', 'role:trainer'])->prefix('trainer')->name('trainer.')->group(function () {
    Route::get('/dashboard', [TrainerController::class, 'dashboard'])->name('dashboard');
    Route::post('/availability', [TrainerController::class, 'storeAvailability'])->name('availability.store');
});

// ==========================================
// MEMBER ROUTES
// ==========================================
Route::middleware(['auth', 'verified', 'role:member'])->prefix('member')->name('member.')->group(function () {
   
    // Member Dashboard
    Route::get('/dashboard', [MemberController::class, 'dashboard'])->name('dashboard');
    
    // Gym Booking Routes
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::put('/bookings/{id}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
    
    // Waitlist Routes
    Route::post('/waitlist', [BookingController::class, 'joinWaitlist'])->name('waitlist.join');
    Route::get('/waitlist/{id}/confirm', [BookingController::class, 'confirmWaitlist'])->name('waitlist.confirm');

    // Trainer Booking Routes
    Route::get('/trainers', [MemberTrainerController::class, 'index'])->name('trainers.index');
    Route::post('/trainers/book', [MemberTrainerController::class, 'book'])->name('trainers.book');

    // Membership Plans Checkout Routes
    Route::get('/plans', [MembershipController::class, 'index'])->name('plans.index');
    Route::post('/plans/purchase', [MembershipController::class, 'purchase'])->name('plans.purchase');

    // NEW: Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});