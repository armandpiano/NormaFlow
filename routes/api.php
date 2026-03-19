<?php

use Illuminate\Support\Facades\Route;
use App\UI\API\Controllers\AuthController;
use App\UI\API\Controllers\CompanyController;
use App\UI\API\Controllers\SiteController;
use App\UI\API\Controllers\UserController;
use App\UI\API\Controllers\RegulationController;
use App\UI\API\Controllers\RequirementController;
use App\UI\API\Controllers\EvidenceController;
use App\UI\API\Controllers\AuditController;
use App\UI\API\Controllers\FindingController;
use App\UI\API\Controllers\ActionPlanController;
use App\UI\API\Controllers\DashboardController;
use App\UI\API\Controllers\NotificationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/forgot-password', [AuthController::class, 'forgotPassword']);

// Tenant identification for API
Route::middleware(['tenant'])->group(function () {

    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {

        // Auth
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::get('/auth/me', [AuthController::class, 'me']);

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index']);
        Route::get('/dashboard/compliance-summary', [DashboardController::class, 'complianceSummary']);
        Route::get('/dashboard/expiring-requirements', [DashboardController::class, 'expiringRequirements']);

        // Companies (Super Admin & Company Admin)
        Route::apiResource('companies', CompanyController::class);
        Route::get('/companies/{company}/stats', [CompanyController::class, 'stats']);

        // Sites
        Route::apiResource('sites', SiteController::class);
        Route::get('/sites/{site}/requirements', [SiteController::class, 'requirements']);
        Route::get('/sites/{site}/evidences', [SiteController::class, 'evidences']);

        // Users
        Route::apiResource('users', UserController::class);
        Route::post('/users/{user}/activate', [UserController::class, 'activate']);
        Route::post('/users/{user}/deactivate', [UserController::class, 'deactivate']);

        // Regulations
        Route::apiResource('regulations', RegulationController::class);
        Route::get('/regulations/{regulation}/requirements', [RegulationController::class, 'requirements']);

        // Requirements
        Route::apiResource('requirements', RequirementController::class);
        Route::get('/normative-matrices/{matrix}/requirements', [RequirementController::class, 'byMatrix']);

        // Evidences
        Route::apiResource('evidences', EvidenceController::class);
        Route::post('/evidences/{evidence}/approve', [EvidenceController::class, 'approve']);
        Route::post('/evidences/{evidence}/reject', [EvidenceController::class, 'reject']);
        Route::get('/requirements/{requirement}/evidences', [EvidenceController::class, 'byRequirement']);

        // Audits
        Route::apiResource('audits', AuditController::class);
        Route::post('/audits/{audit}/start', [AuditController::class, 'start']);
        Route::post('/audits/{audit}/complete', [AuditController::class, 'complete']);
        Route::post('/audits/{audit}/cancel', [AuditController::class, 'cancel']);

        // Findings
        Route::apiResource('findings', FindingController::class);
        Route::get('/audits/{audit}/findings', [FindingController::class, 'byAudit']);

        // Action Plans
        Route::apiResource('action-plans', ActionPlanController::class);
        Route::post('/action-plans/{actionPlan}/complete', [ActionPlanController::class, 'complete']);
        Route::get('/findings/{finding}/action-plans', [ActionPlanController::class, 'byFinding']);

        // Notifications
        Route::get('/notifications', [NotificationController::class, 'index']);
        Route::get('/notifications/unread', [NotificationController::class, 'unread']);
        Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead']);
        Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead']);
    });
});
