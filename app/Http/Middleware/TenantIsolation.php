<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware TenantIsolation
 * 
 * Garantiza el aislamiento multi-tenant en todas las solicitudes.
 * 
 * Funcionalidades:
 * - Verifica que el usuario esté autenticado
 * - Super Admin puede acceder a todos los tenants
 * - Usuarios normales solo acceden a recursos de su tenant
 * - Añade headers de contexto de tenant
 * - Registra intentos de acceso no autorizado
 */
class TenantIsolation
{
    /**
     * Rutas que NO requieren verificación de tenant
     */
    protected array $exceptRoutes = [
        'login',
        'logout',
        'register',
        'password.*',
        'api/health',
        'webhook.*',
    ];

    /**
     * Manejar una solicitud entrante
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Si la ruta está exceptuada, continuar
        if ($this->shouldSkip($request)) {
            return $next($request);
        }

        // Verificar que el usuario esté autenticado
        if (!$request->user()) {
            return redirect()->route('login');
        }

        $user = $request->user();

        // Super Admin puede acceder a todo
        if ($user->isSuperAdmin()) {
            // Añadir header para debugging
            $request->headers->set('X-Tenant-ID', 'platform');
            $request->headers->set('X-Access-Level', 'platform');
            
            return $next($request);
        }

        // Verificar que el usuario tenga un tenant_id
        if (!$user->tenant_id) {
            $this->logUnauthorizedAccess($request, 'No tenant_id');
            return $this->deniedResponse('Tu cuenta no tiene acceso a ningún tenant.');
        }

        // Verificar que el usuario esté activo
        if (!$user->isActive()) {
            $this->logUnauthorizedAccess($request, 'User inactive');
            return $this->deniedResponse('Tu cuenta está desactivada. Contacta al administrador.');
        }

        // Añadir headers de contexto
        $request->headers->set('X-Tenant-ID', (string) $user->tenant_id);
        $request->headers->set('X-Company-ID', (string) $user->company_id ?? 'none');
        $request->headers->set('X-Access-Level', $user->getAccessLevel());

        // Si es una solicitud a un recurso específico, verificar pertenencia al tenant
        $resourceTenantId = $this->getResourceTenantId($request);
        
        if ($resourceTenantId !== null && $resourceTenantId !== $user->tenant_id) {
            $this->logUnauthorizedAccess($request, 'Tenant mismatch', [
                'user_tenant' => $user->tenant_id,
                'resource_tenant' => $resourceTenantId,
                'resource_type' => $request->route()?->getName(),
            ]);
            
            return $this->deniedResponse('No tienes acceso a este recurso.');
        }

        return $next($request);
    }

    /**
     * Verificar si la solicitud debe ser exceptuada
     */
    protected function shouldSkip(Request $request): bool
    {
        foreach ($this->exceptRoutes as $pattern) {
            if ($request->routeIs($pattern)) {
                return true;
            }
        }

        // Rutas de API públicas
        if ($request->is('api/public/*')) {
            return true;
        }

        return false;
    }

    /**
     * Obtener el tenant_id del recurso solicitado
     */
    protected function getResourceTenantId(Request $request): ?int
    {
        // Intentar obtener el tenant del modelo en la ruta
        $model = $request->route()?->parameter('tenant');

        if ($model && isset($model->tenant_id)) {
            return (int) $model->tenant_id;
        }

        // Intentar obtener de parámetros comunes
        $tenantParam = $request->route('tenant_id') ?? $request->input('tenant_id');

        if ($tenantParam) {
            return (int) $tenantParam;
        }

        return null;
    }

    /**
     * Responder con error de acceso denegado
     */
    protected function deniedResponse(string $message): Response
    {
        if (request()->expectsJson()) {
            return response()->json([
                'error' => 'Acceso denegado',
                'message' => $message,
            ], 403);
        }

        return redirect()->route('dashboard')->with('error', $message);
    }

    /**
     * Registrar intento de acceso no autorizado
     */
    protected function logUnauthorizedAccess(Request $request, string $reason, array $context = []): void
    {
        $user = $request->user();

        activity()
            ->causedBy($user)
            ->withProperties(array_merge([
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'reason' => $reason,
            ], $context))
            ->log('unauthorized_tenant_access');
    }
}
