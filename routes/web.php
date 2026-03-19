<?php

use Illuminate\Support\Facades\Route;
use App\UI\Web\Controllers\HomeController;
use App\UI\Web\Controllers\DashboardController;
use App\UI\Web\Controllers\CompanyController;
use App\UI\Web\Controllers\SiteController;
use App\UI\Web\Controllers\ComplianceController;
use App\UI\Web\Controllers\AuditController;
use App\UI\Web\Controllers\ReportController;
use App\UI\Web\Controllers\SettingsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::get('/', [HomeController::class, 'index']);
Route::get('/login', [HomeController::class, 'login'])->name('login');
Route::get('/register', [HomeController::class, 'register'])->name('register');
Route::get('/pricing', [HomeController::class, 'pricing'])->name('pricing');
Route::get('/features', [HomeController::class, 'features'])->name('features');

// Authenticated routes
Route::middleware(['auth:sanctum', 'tenant'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Companies
    Route::prefix('companies')->group(function () {
        Route::get('/', [CompanyController::class, 'index'])->name('companies.index');
        Route::get('/create', [CompanyController::class, 'create'])->name('companies.create');
        Route::post('/', [CompanyController::class, 'store'])->name('companies.store');
        Route::get('/{company}', [CompanyController::class, 'show'])->name('companies.show');
        Route::get('/{company}/edit', [CompanyController::class, 'edit'])->name('companies.edit');
        Route::put('/{company}', [CompanyController::class, 'update'])->name('companies.update');
        Route::delete('/{company}', [CompanyController::class, 'destroy'])->name('companies.destroy');
    });

    // Sites
    Route::prefix('sites')->group(function () {
        Route::get('/', [SiteController::class, 'index'])->name('sites.index');
        Route::get('/create', [SiteController::class, 'create'])->name('sites.create');
        Route::post('/', [SiteController::class, 'store'])->name('sites.store');
        Route::get('/{site}', [SiteController::class, 'show'])->name('sites.show');
    });

    // Compliance
    Route::prefix('compliance')->group(function () {
        Route::get('/', [ComplianceController::class, 'index'])->name('compliance.index');
        Route::get('/regulations', [ComplianceController::class, 'regulations'])->name('compliance.regulations');
        Route::get('/requirements', [ComplianceController::class, 'requirements'])->name('compliance.requirements');
        Route::get('/evidences', [ComplianceController::class, 'evidences'])->name('compliance.evidences');
        Route::get('/evidences/create', [ComplianceController::class, 'createEvidence'])->name('compliance.evidences.create');
    });

    // Audits
    Route::prefix('audits')->group(function () {
        Route::get('/', [AuditController::class, 'index'])->name('audits.index');
        Route::get('/create', [AuditController::class, 'create'])->name('audits.create');
        Route::post('/', [AuditController::class, 'store'])->name('audits.store');
        Route::get('/{audit}', [AuditController::class, 'show'])->name('audits.show');
        Route::get('/{audit}/findings', [AuditController::class, 'findings'])->name('audits.findings');
    });

    // Reports
    Route::prefix('reports')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/compliance', [ReportController::class, 'compliance'])->name('reports.compliance');
        Route::get('/audits', [ReportController::class, 'audits'])->name('reports.audits');
        Route::get('/export/{type}', [ReportController::class, 'export'])->name('reports.export');
    });

    // Settings
    Route::prefix('settings')->group(function () {
        Route::get('/', [SettingsController::class, 'index'])->name('settings.index');
        Route::get('/profile', [SettingsController::class, 'profile'])->name('settings.profile');
        Route::put('/profile', [SettingsController::class, 'updateProfile'])->name('settings.profile.update');
        Route::get('/users', [SettingsController::class, 'users'])->name('settings.users');
        Route::get('/roles', [SettingsController::class, 'roles'])->name('settings.roles');
    });
});
