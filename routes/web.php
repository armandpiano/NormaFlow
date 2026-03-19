<?php

use Illuminate\Support\Facades\Route;
use App\UI\WEB\Controllers\AuthController;
use App\UI\WEB\Controllers\DashboardController;
use App\UI\WEB\Controllers\CompanyController;
use App\UI\WEB\Controllers\RegulationController;
use App\UI\WEB\Controllers\AuditController;
use App\UI\WEB\Controllers\EvidenceController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public routes - Landing
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Auth routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Authenticated routes
Route::middleware(['auth:sanctum', 'tenant'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Companies
    Route::prefix('companies')->group(function () {
        Route::get('/', [CompanyController::class, 'index'])->name('companies.index');
        Route::get('/create', [CompanyController::class, 'create'])->name('companies.create');
        Route::post('/', [CompanyController::class, 'store'])->name('companies.store');
        Route::get('/{id}', [CompanyController::class, 'show'])->name('companies.show');
        Route::get('/{id}/edit', [CompanyController::class, 'edit'])->name('companies.edit');
        Route::put('/{id}', [CompanyController::class, 'update'])->name('companies.update');
        Route::post('/{id}/suspend', [CompanyController::class, 'suspend'])->name('companies.suspend');
        Route::post('/{id}/activate', [CompanyController::class, 'activate'])->name('companies.activate');
    });

    // Regulations
    Route::prefix('regulations')->group(function () {
        Route::get('/', [RegulationController::class, 'index'])->name('regulations.index');
        Route::get('/create', [RegulationController::class, 'create'])->name('regulations.create');
        Route::post('/', [RegulationController::class, 'store'])->name('regulations.store');
        Route::get('/{id}', [RegulationController::class, 'show'])->name('regulations.show');
        Route::get('/{id}/edit', [RegulationController::class, 'edit'])->name('regulations.edit');
        Route::put('/{id}', [RegulationController::class, 'update'])->name('regulations.update');
        Route::get('/{id}/requirements', [RegulationController::class, 'requirements'])->name('regulations.requirements');
        Route::get('/{id}/requirements/create', [RegulationController::class, 'createRequirement'])->name('regulations.requirements.create');
        Route::post('/{id}/requirements', [RegulationController::class, 'storeRequirement'])->name('regulations.requirements.store');
    });

    // Audits
    Route::prefix('audits')->group(function () {
        Route::get('/', [AuditController::class, 'index'])->name('audits.index');
        Route::get('/create', [AuditController::class, 'create'])->name('audits.create');
        Route::post('/', [AuditController::class, 'store'])->name('audits.store');
        Route::get('/{id}', [AuditController::class, 'show'])->name('audits.show');
        Route::get('/{id}/edit', [AuditController::class, 'edit'])->name('audits.edit');
        Route::put('/{id}', [AuditController::class, 'update'])->name('audits.update');
        Route::post('/{id}/start', [AuditController::class, 'start'])->name('audits.start');
        Route::post('/{id}/complete', [AuditController::class, 'complete'])->name('audits.complete');
        Route::post('/{id}/cancel', [AuditController::class, 'cancel'])->name('audits.cancel');
        Route::get('/{id}/findings/create', [AuditController::class, 'addFinding'])->name('audits.add-finding');
    });

    // Evidence
    Route::prefix('evidence')->group(function () {
        Route::get('/', [EvidenceController::class, 'index'])->name('evidence.index');
        Route::get('/create', [EvidenceController::class, 'create'])->name('evidence.create');
        Route::post('/', [EvidenceController::class, 'store'])->name('evidence.store');
        Route::get('/{id}', [EvidenceController::class, 'show'])->name('evidence.show');
        Route::get('/{id}/download', [EvidenceController::class, 'download'])->name('evidence.download');
        Route::post('/{id}/approve', [EvidenceController::class, 'approve'])->name('evidence.approve');
        Route::post('/{id}/reject', [EvidenceController::class, 'reject'])->name('evidence.reject');
        Route::post('/{id}/request-revision', [EvidenceController::class, 'requestRevision'])->name('evidence.request-revision');
        Route::delete('/{id}', [EvidenceController::class, 'destroy'])->name('evidence.destroy');
    });

    // Profile
    Route::get('/profile', function () {
        return view('profile.index');
    })->name('profile.index');

    // Settings
    Route::get('/settings', function () {
        return view('settings.index');
    })->name('settings.index');
});
