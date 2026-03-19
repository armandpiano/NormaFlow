<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware SiteAccess
 * 
 * Controla el acceso a nivel de sede.
 * 
 * Funcionalidades:
 * - Verifica que el usuario pueda acceder a la sede solicitada
 * - Super Admin y Company Admin tienen acceso a todas las sedes de su empresa
 * - Site Manager y empleados tienen acceso solo a sedes asignadas
 * - Soporta validación de acciones específicas por sede
 */
class SiteAccess
{
    /**
     * Manejar una solicitud entrante
     */
    public function handle(Request $request, Closure $next, ?string $action = null): Response
    {
        $user = $request->user();

        // Si no hay usuario, no aplicar
        if (!$user) {
            return $next($request);
        }

        // Super Admin siempre tiene acceso
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        // Obtener el site_id de la solicitud
        $siteId = $this->getSiteIdFromRequest($request);

        // Si no hay site_id, continuar (no todas las rutas requieren sede)
        if ($siteId === null) {
            return $next($request);
        }

        // Verificar acceso a la sede
        if (!$user->canAccessSite($siteId)) {
            $this->logSiteAccessDenied($request, $siteId, $action);
            return $this->deniedResponse($siteId);
        }

        // Si se especificaron acciones, verificar cada una
        if ($action && !$this->hasSiteActionPermission($user, $action, $siteId)) {
            $this->logSiteActionDenied($request, $siteId, $action);
            return $this->deniedActionResponse($action);
        }

        // Añadir site context a la solicitud
        $request->attributes->set('current_site_id', $siteId);

        return $next($request);
    }

    /**
     * Obtener el site_id de la solicitud
     */
    protected function getSiteIdFromRequest(Request $request): ?int
    {
        // Del parámetro de ruta
        $siteId = $request->route('site_id') ?? $request->route('site');

        if ($siteId) {
            return (int) $siteId;
        }

        // Del modelo en la ruta
        $siteModel = $request->route('site');
        if ($siteModel && isset($siteModel->id)) {
            return (int) $siteModel->id;
        }

        // De la sede actual del usuario
        if ($request->user()->site_id) {
            return (int) $request->user()->site_id;
        }

        // De parámetros POST
        $siteId = $request->input('site_id');
        if ($siteId) {
            return (int) $siteId;
        }

        return null;
    }

    /**
     * Verificar si el usuario tiene permiso para una acción específica en la sede
     */
    protected function hasSiteActionPermission($user, string $action, int $siteId): bool
    {
        $permissionMap = [
            'view' => 'sites.view',
            'create' => 'sites.create',
            'update' => 'sites.update',
            'delete' => 'sites.delete',
            'manage_users' => 'users.manage',
            'approve_evidence' => 'evidences.approve',
            'view_evidence' => 'evidences.view',
            'upload_evidence' => 'evidences.upload',
            'manage_findings' => 'findings.manage',
        ];

        $permission = $permissionMap[$action] ?? null;

        if (!$permission) {
            return true; // Acción desconocida, permitir
        }

        // Company Admin siempre tiene permisos en sus sedes
if ($user->isCompanyAdmin()) {
            return true;
        }

        return $user->hasPermission($permission);
    }

    /**
     * Responder con error de acceso denegado a sede
     */
    protected function deniedResponse(int $siteId): Response
    {
        if (request()->expectsJson()) {
            return response()->json([
                'error' => 'Acceso denegado',
                'message' => 'No tienes acceso a esta sede.',
                'site_id' => $siteId,
            ], 403);
        }

        return redirect()->back()
            ->with('error', 'No tienes acceso a esta sede.');
    }

    /**
     * Responder con error de acción no permitida
     */
    protected function deniedActionResponse(string $action): Response
    {
        $actionMessages = [
            'view' => 'No tienes permiso para ver esta sede.',
            'create' => 'No tienes permiso para crear en esta sede.',
            'update' => 'No tienes permiso para editar esta sede.',
            'delete' => 'No tienes permiso para eliminar esta sede.',
            'approve_evidence' => 'No tienes permiso para aprobar evidencias en esta sede.',
            'manage_users' => 'No tienes permiso para gestionar usuarios de esta sede.',
        ];

        $message = $actionMessages[$action] ?? 'No tienes permiso para realizar esta acción.';

        if (request()->expectsJson()) {
            return response()->json([
                'error' => 'Permiso denegado',
                'message' => $message,
                'action' => $action,
            ], 403);
        }

        return redirect()->back()->with('error', $message);
    }

    /**
     * Registrar intento de acceso denegado a sede
     */
    protected function logSiteAccessDenied(Request $request, int $siteId, ?string $action): void
    {
        activity()
            ->causedBy($request->user())
            ->withProperties([
                'site_id' => $siteId,
                'action' => $action,
                'ip' => $request->ip(),
                'url' => $request->fullUrl(),
            ])
            ->log('site_access_denied');
    }

    /**
     * Registrar intento de acción denegada en sede
     */
    protected function logSiteActionDenied(Request $request, int $siteId, string $action): void
    {
        activity()
            ->causedBy($request->user())
            ->withProperties([
                'site_id' => $siteId,
                'action' => $action,
                'ip' => $request->ip(),
            ])
            ->log('site_action_denied');
    }
}
