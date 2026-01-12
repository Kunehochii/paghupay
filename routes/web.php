<?php

use App\Http\Controllers\Admin\ClientController as AdminClientController;
use App\Http\Controllers\Admin\CounselorController as AdminCounselorController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Client\BookingController;
use App\Http\Controllers\Client\OnboardingController;
use App\Http\Controllers\Counselor\AppointmentController;
use App\Http\Controllers\Counselor\CaseLogController;
use App\Http\Controllers\Counselor\DashboardController as CounselorDashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Route Separation (per spec):
| - guest: Login pages
| - / (Root): Client/Student Portal (auth, role:client)
| - /counselor: Counselor Portal (auth, role:counselor, verify.device)
| - /admin: Admin Portal (auth, role:admin)
|
*/

// ============================================================================
// GUEST ROUTES (Login Pages)
// ============================================================================

Route::middleware('guest')->group(function () {
    // Student Login (Default)
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    // Counselor Login
    Route::get('/counselor/login', [AuthController::class, 'showCounselorLoginForm'])->name('counselor.login');
    Route::post('/counselor/login', [AuthController::class, 'counselorLogin']);

    // Admin Login
    Route::get('/admin/login', [AuthController::class, 'showAdminLoginForm'])->name('admin.login');
    Route::post('/admin/login', [AuthController::class, 'adminLogin']);
});

// Student Registration (Profile Completion + Password Change)
// Accessible to: logged in inactive clients OR redirects to login
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->middleware('auth');

// Logout (requires auth)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ============================================================================
// CLIENT/STUDENT PORTAL (Root: /)
// ============================================================================

Route::middleware(['auth', 'role:client'])->group(function () {
    // Student Welcome Page (Landing after login)
    Route::get('/', [BookingController::class, 'welcome'])->name('client.welcome');

    // Confidentiality Agreement
    Route::get('/agreement', [BookingController::class, 'showAgreement'])->name('client.agreement');
    Route::post('/agreement', [BookingController::class, 'acceptAgreement'])->name('client.agreement.accept');

    // Profile completion/onboarding
    Route::get('/onboarding', [OnboardingController::class, 'show'])->name('client.onboarding');
    Route::post('/onboarding', [OnboardingController::class, 'complete'])->name('client.onboarding.complete');

    // Booking Flow
    Route::prefix('booking')->name('booking.')->group(function () {
        // Booking Start Page
        Route::get('/', [BookingController::class, 'index'])->name('index');

        // Step 1: Choose Counselor
        Route::get('/counselors', [BookingController::class, 'chooseCounselor'])->name('choose-counselor');
        Route::post('/counselors', [BookingController::class, 'selectCounselor'])->name('select-counselor');

        // Step 2: Date & Time Selection
        Route::get('/schedule/{counselor}', [BookingController::class, 'schedule'])->name('schedule');
        Route::post('/schedule/{counselor}', [BookingController::class, 'selectSchedule'])->name('select-schedule');

        // API: Get available time slots for a date
        Route::get('/counselor/{counselor}/slots', [BookingController::class, 'getAvailableSlots'])->name('get-slots');

        // Step 3: Reason Input
        Route::get('/reason', [BookingController::class, 'reason'])->name('reason');

        // Step 4: Store appointment
        Route::post('/store', [BookingController::class, 'store'])->name('store');

        // Thank You / Confirmation Page
        Route::get('/thankyou', [BookingController::class, 'thankyou'])->name('thankyou');

        // Legacy Confirmation (redirect to thankyou)
        Route::get('/confirmation', [BookingController::class, 'confirmation'])->name('confirmation');
    });

    // View appointments
    Route::get('/appointments', [BookingController::class, 'appointments'])->name('client.appointments');
});

// ============================================================================
// COUNSELOR PORTAL (/counselor)
// ============================================================================

Route::middleware(['auth', 'role:counselor', 'verify.device'])
    ->prefix('counselor')
    ->name('counselor.')
    ->group(function () {
        // Dashboard
        Route::get('/dashboard', [CounselorDashboardController::class, 'index'])->name('dashboard');

        // Appointments Management
        Route::prefix('appointments')->name('appointments.')->group(function () {
            Route::get('/', [AppointmentController::class, 'index'])->name('index');
            Route::post('/{appointment}/accept', [AppointmentController::class, 'accept'])->name('accept');
            Route::post('/{appointment}/reject', [AppointmentController::class, 'reject'])->name('reject');
            Route::post('/{appointment}/cancel', [AppointmentController::class, 'cancel'])->name('cancel');
            Route::post('/{appointment}/start-session', [AppointmentController::class, 'startSession'])->name('start-session');
            Route::post('/{appointment}/end-session', [AppointmentController::class, 'endSession'])->name('end-session');
            Route::get('/active-session', [AppointmentController::class, 'activeSession'])->name('active-session');
        });

        // Case Logs
        Route::prefix('case-logs')->name('case-logs.')->group(function () {
            Route::get('/', [CaseLogController::class, 'index'])->name('index');
            Route::get('/create', [CaseLogController::class, 'create'])->name('create');
            Route::post('/store', [CaseLogController::class, 'store'])->name('store');
            Route::get('/{caseLog}', [CaseLogController::class, 'show'])->name('show');
            Route::get('/{caseLog}/edit', [CaseLogController::class, 'edit'])->name('edit');
            Route::put('/{caseLog}', [CaseLogController::class, 'update'])->name('update');
            Route::delete('/{caseLog}', [CaseLogController::class, 'destroy'])->name('destroy');
            Route::get('/{caseLog}/export-pdf', [CaseLogController::class, 'exportPdf'])->name('export-pdf');
        });

        // About Page
        Route::get('/about', function () {
            return view('counselor.about');
        })->name('about');

        // Client History
        Route::get('/clients/{client}/history', function ($client) {
            return view('counselor.client-history', compact('client'));
        })->name('client-history');
    });

// ============================================================================
// ADMIN PORTAL (/admin)
// ============================================================================

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Counselor Management
        Route::prefix('counselors')->name('counselors.')->group(function () {
            Route::get('/', [AdminCounselorController::class, 'index'])->name('index');
            Route::get('/create', [AdminCounselorController::class, 'create'])->name('create');
            Route::post('/', [AdminCounselorController::class, 'store'])->name('store');
            Route::get('/{counselor}/edit', [AdminCounselorController::class, 'edit'])->name('edit');
            Route::put('/{counselor}', [AdminCounselorController::class, 'update'])->name('update');
            Route::delete('/{counselor}', [AdminCounselorController::class, 'destroy'])->name('destroy');
            // Device Reset
            Route::post('/{counselor}/reset-device', [AdminCounselorController::class, 'resetDevice'])->name('reset-device');
        });

        // Client/User Management
        Route::prefix('clients')->name('clients.')->group(function () {
            Route::get('/', [AdminClientController::class, 'index'])->name('index');
            Route::get('/create', [AdminClientController::class, 'create'])->name('create');
            Route::post('/', [AdminClientController::class, 'store'])->name('store');
            Route::get('/{client}', [AdminClientController::class, 'show'])->name('show');
            Route::delete('/{client}', [AdminClientController::class, 'destroy'])->name('destroy');
        });

        // Reports (optional)
        Route::get('/reports', function () {
            return view('admin.reports');
        })->name('reports');
    });
