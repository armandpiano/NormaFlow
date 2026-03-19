<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * RoleSeeder - Sistema completo de Roles y Permisos
 * 9 Roles + 88 permisos granulares organizados por módulo
 */
class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedRoles();
        $this->seedPermissions();
        $this->assignPermissionsToRoles();
    }

    protected function seedRoles(): void
    {
        $roles = [
            ['name' => 'Super Admin', 'slug' => 'super_admin', 'description' => 'Administrador de plataforma con acceso total', 'guard_name' => 'web', 'parent_id' => null, 'hierarchy_level' => 100, 'is_system' => true, 'color' => '#dc2626'],
            ['name' => 'Administrador de Empresa', 'slug' => 'company_admin', 'description' => 'Administrador de empresa', 'guard_name' => 'web', 'parent_id' => null, 'hierarchy_level' => 80, 'is_system' => true, 'color' => '#f59e0b'],
            ['name' => 'Responsable de Sede', 'slug' => 'site_manager', 'description' => 'Responsable operativo de sede', 'guard_name' => 'web', 'parent_id' => null, 'hierarchy_level' => 60, 'is_system' => true, 'color' => '#10b981'],
            ['name' => 'Auditor Interno', 'slug' => 'internal_auditor', 'description' => 'Auditor interno', 'guard_name' => 'web', 'parent_id' => null, 'hierarchy_level' => 50, 'is_system' => true, 'color' => '#3b82f6'],
            ['name' => 'Auditor Externo', 'slug' => 'external_auditor', 'description' => 'Auditor externo certificado', 'guard_name' => 'web', 'parent_id' => null, 'hierarchy_level' => 50, 'is_system' => true, 'color' => '#8b5cf6'],
            ['name' => 'Responsable de Cumplimiento', 'slug' => 'compliance_officer', 'description' => 'Encargado de cumplimiento normativo', 'guard_name' => 'web', 'parent_id' => null, 'hierarchy_level' => 55, 'is_system' => true, 'color' => '#06b6d4'],
            ['name' => 'Responsable de Capacitación', 'slug' => 'training_manager', 'description' => 'Gestor de capacitación NOM/STPS', 'guard_name' => 'web', 'parent_id' => null, 'hierarchy_level' => 45, 'is_system' => true, 'color' => '#ec4899'],
            ['name' => 'Empleado', 'slug' => 'employee', 'description' => 'Personal operativo', 'guard_name' => 'web', 'parent_id' => null, 'hierarchy_level' => 10, 'is_system' => true, 'color' => '#6b7280'],
            ['name' => 'Consulta', 'slug' => 'viewer', 'description' => 'Usuario de solo lectura', 'guard_name' => 'web', 'parent_id' => null, 'hierarchy_level' => 5, 'is_system' => true, 'color' => '#9ca3af'],
        ];
        foreach ($roles as $role) {
            DB::table('roles')->updateOrInsert(['slug' => $role['slug']], $role);
        }
    }

    protected function seedPermissions(): void
    {
        $permissions = [
            // Empresas - 10
            ['name' => 'Ver Empresas', 'slug' => 'companies.view', 'group' => 'Empresas', 'subgroup' => 'General'],
            ['name' => 'Ver Empresa', 'slug' => 'companies.view_single', 'group' => 'Empresas', 'subgroup' => 'General'],
            ['name' => 'Crear Empresas', 'slug' => 'companies.create', 'group' => 'Empresas', 'subgroup' => 'General'],
            ['name' => 'Actualizar Empresas', 'slug' => 'companies.update', 'group' => 'Empresas', 'subgroup' => 'General'],
            ['name' => 'Eliminar Empresas', 'slug' => 'companies.delete', 'group' => 'Empresas', 'subgroup' => 'General'],
            ['name' => 'Configurar Empresa', 'slug' => 'companies.settings', 'group' => 'Empresas', 'subgroup' => 'Config'],
            ['name' => 'Ver Usuarios Empresa', 'slug' => 'companies.view_users', 'group' => 'Empresas', 'subgroup' => 'Usuarios'],
            ['name' => 'Gestionar Usuarios', 'slug' => 'companies.manage_users', 'group' => 'Empresas', 'subgroup' => 'Usuarios'],
            ['name' => 'Ver Sedes Empresa', 'slug' => 'companies.view_sites', 'group' => 'Empresas', 'subgroup' => 'Sedes'],
            ['name' => 'Gestionar Sedes', 'slug' => 'companies.manage_sites', 'group' => 'Empresas', 'subgroup' => 'Sedes'],
            // Sedes - 8
            ['name' => 'Ver Sedes', 'slug' => 'sites.view', 'group' => 'Sedes', 'subgroup' => 'General'],
            ['name' => 'Crear Sedes', 'slug' => 'sites.create', 'group' => 'Sedes', 'subgroup' => 'General'],
            ['name' => 'Actualizar Sedes', 'slug' => 'sites.update', 'group' => 'Sedes', 'subgroup' => 'General'],
            ['name' => 'Eliminar Sedes', 'slug' => 'sites.delete', 'group' => 'Sedes', 'subgroup' => 'General'],
            ['name' => 'Asignar Usuarios', 'slug' => 'sites.assign_users', 'group' => 'Sedes', 'subgroup' => 'Usuarios'],
            ['name' => 'Ver Auditorías', 'slug' => 'sites.view_audits', 'group' => 'Sedes', 'subgroup' => 'Auditorías'],
            ['name' => 'Gestionar Auditorías', 'slug' => 'sites.manage_audits', 'group' => 'Sedes', 'subgroup' => 'Auditorías'],
            ['name' => 'Ver Cumplimiento', 'slug' => 'sites.view_compliance', 'group' => 'Sedes', 'subgroup' => 'Cumplimiento'],
            // Usuarios - 10
            ['name' => 'Ver Usuarios', 'slug' => 'users.view', 'group' => 'Usuarios', 'subgroup' => 'General'],
            ['name' => 'Ver Usuario', 'slug' => 'users.view_single', 'group' => 'Usuarios', 'subgroup' => 'General'],
            ['name' => 'Crear Usuarios', 'slug' => 'users.create', 'group' => 'Usuarios', 'subgroup' => 'General'],
            ['name' => 'Actualizar Usuarios', 'slug' => 'users.update', 'group' => 'Usuarios', 'subgroup' => 'General'],
            ['name' => 'Eliminar Usuarios', 'slug' => 'users.delete', 'group' => 'Usuarios', 'subgroup' => 'General'],
            ['name' => 'Asignar Roles', 'slug' => 'users.assign_role', 'group' => 'Usuarios', 'subgroup' => 'Roles'],
            ['name' => 'Asignar Sedes', 'slug' => 'users.assign_sites', 'group' => 'Usuarios', 'subgroup' => 'Sedes'],
            ['name' => 'Resetear Password', 'slug' => 'users.reset_password', 'group' => 'Usuarios', 'subgroup' => 'Seguridad'],
            ['name' => 'Desactivar Usuarios', 'slug' => 'users.deactivate', 'group' => 'Usuarios', 'subgroup' => 'Seguridad'],
            ['name' => 'Ver Perfiles', 'slug' => 'users.view_profile', 'group' => 'Usuarios', 'subgroup' => 'General'],
            // Normativas - 6
            ['name' => 'Ver Normativas', 'slug' => 'regulations.view', 'group' => 'Normativas', 'subgroup' => 'General'],
            ['name' => 'Ver Normativa', 'slug' => 'regulations.view_single', 'group' => 'Normativas', 'subgroup' => 'General'],
            ['name' => 'Crear Normativas', 'slug' => 'regulations.create', 'group' => 'Normativas', 'subgroup' => 'General'],
            ['name' => 'Actualizar Normativas', 'slug' => 'regulations.update', 'group' => 'Normativas', 'subgroup' => 'General'],
            ['name' => 'Eliminar Normativas', 'slug' => 'regulations.delete', 'group' => 'Normativas', 'subgroup' => 'General'],
            ['name' => 'Gestionar Normativas', 'slug' => 'regulations.manage', 'group' => 'Normativas', 'subgroup' => 'General'],
            // Requisitos - 8
            ['name' => 'Ver Requisitos', 'slug' => 'requirements.view', 'group' => 'Requisitos', 'subgroup' => 'General'],
            ['name' => 'Ver Requisito', 'slug' => 'requirements.view_single', 'group' => 'Requisitos', 'subgroup' => 'General'],
            ['name' => 'Crear Requisitos', 'slug' => 'requirements.create', 'group' => 'Requisitos', 'subgroup' => 'General'],
            ['name' => 'Actualizar Requisitos', 'slug' => 'requirements.update', 'group' => 'Requisitos', 'subgroup' => 'General'],
            ['name' => 'Eliminar Requisitos', 'slug' => 'requirements.delete', 'group' => 'Requisitos', 'subgroup' => 'General'],
            ['name' => 'Gestionar Requisitos', 'slug' => 'requirements.manage', 'group' => 'Requisitos', 'subgroup' => 'General'],
            ['name' => 'Ver Cumplimiento Req', 'slug' => 'requirements.view_compliance', 'group' => 'Requisitos', 'subgroup' => 'Cumplimiento'],
            ['name' => 'Subir Evidencia Req', 'slug' => 'requirements.upload_evidence', 'group' => 'Requisitos', 'subgroup' => 'Evidencias'],
            // Evidencias - 10
            ['name' => 'Ver Evidencias', 'slug' => 'evidences.view', 'group' => 'Evidencias', 'subgroup' => 'General'],
            ['name' => 'Ver Evidencia', 'slug' => 'evidences.view_single', 'group' => 'Evidencias', 'subgroup' => 'General'],
            ['name' => 'Subir Evidencias', 'slug' => 'evidences.upload', 'group' => 'Evidencias', 'subgroup' => 'General'],
            ['name' => 'Actualizar Evidencias', 'slug' => 'evidences.update', 'group' => 'Evidencias', 'subgroup' => 'General'],
            ['name' => 'Eliminar Evidencias', 'slug' => 'evidences.delete', 'group' => 'Evidencias', 'subgroup' => 'General'],
            ['name' => 'Aprobar Evidencias', 'slug' => 'evidences.approve', 'group' => 'Evidencias', 'subgroup' => 'Aprobación'],
            ['name' => 'Rechazar Evidencias', 'slug' => 'evidences.reject', 'group' => 'Evidencias', 'subgroup' => 'Aprobación'],
            ['name' => 'Descargar Evidencias', 'slug' => 'evidences.download', 'group' => 'Evidencias', 'subgroup' => 'General'],
            ['name' => 'Verificar Evidencias', 'slug' => 'evidences.verify', 'group' => 'Evidencias', 'subgroup' => 'Aprobación'],
            ['name' => 'Comentar Evidencias', 'slug' => 'evidences.comment', 'group' => 'Evidencias', 'subgroup' => 'General'],
            // Auditorías - 12
            ['name' => 'Ver Auditorías', 'slug' => 'audits.view', 'group' => 'Auditorías', 'subgroup' => 'General'],
            ['name' => 'Ver Auditoría', 'slug' => 'audits.view_single', 'group' => 'Auditorías', 'subgroup' => 'General'],
            ['name' => 'Crear Auditorías', 'slug' => 'audits.create', 'group' => 'Auditorías', 'subgroup' => 'General'],
            ['name' => 'Actualizar Auditorías', 'slug' => 'audits.update', 'group' => 'Auditorías', 'subgroup' => 'General'],
            ['name' => 'Eliminar Auditorías', 'slug' => 'audits.delete', 'group' => 'Auditorías', 'subgroup' => 'General'],
            ['name' => 'Gestionar Auditorías', 'slug' => 'audits.manage', 'group' => 'Auditorías', 'subgroup' => 'General'],
            ['name' => 'Ejecutar Auditorías', 'slug' => 'audits.execute', 'group' => 'Auditorías', 'subgroup' => 'Ejecución'],
            ['name' => 'Ver Resultados', 'slug' => 'audits.view_results', 'group' => 'Auditorías', 'subgroup' => 'Resultados'],
            ['name' => 'Cerrar Auditorías', 'slug' => 'audits.close', 'group' => 'Auditorías', 'subgroup' => 'General'],
            ['name' => 'Exportar Auditorías', 'slug' => 'audits.export', 'group' => 'Auditorías', 'subgroup' => 'Exportación'],
            ['name' => 'Programar Auditorías', 'slug' => 'audits.schedule', 'group' => 'Auditorías', 'subgroup' => 'General'],
            ['name' => 'Asignar Auditores', 'slug' => 'audits.assign_auditors', 'group' => 'Auditorías', 'subgroup' => 'General'],
            // Hallazgos - 8
            ['name' => 'Ver Hallazgos', 'slug' => 'findings.view', 'group' => 'Hallazgos', 'subgroup' => 'General'],
            ['name' => 'Ver Hallazgo', 'slug' => 'findings.view_single', 'group' => 'Hallazgos', 'subgroup' => 'General'],
            ['name' => 'Crear Hallazgos', 'slug' => 'findings.create', 'group' => 'Hallazgos', 'subgroup' => 'General'],
            ['name' => 'Actualizar Hallazgos', 'slug' => 'findings.update', 'group' => 'Hallazgos', 'subgroup' => 'General'],
            ['name' => 'Eliminar Hallazgos', 'slug' => 'findings.delete', 'group' => 'Hallazgos', 'subgroup' => 'General'],
            ['name' => 'Gestionar Hallazgos', 'slug' => 'findings.manage', 'group' => 'Hallazgos', 'subgroup' => 'General'],
            ['name' => 'Cerrar Hallazgos', 'slug' => 'findings.close', 'group' => 'Hallazgos', 'subgroup' => 'General'],
            ['name' => 'Asignar Responsable', 'slug' => 'findings.assign_responsible', 'group' => 'Hallazgos', 'subgroup' => 'General'],
            // Planes de Acción - 8
            ['name' => 'Ver Planes', 'slug' => 'action_plans.view', 'group' => 'Planes', 'subgroup' => 'General'],
            ['name' => 'Ver Plan', 'slug' => 'action_plans.view_single', 'group' => 'Planes', 'subgroup' => 'General'],
            ['name' => 'Crear Planes', 'slug' => 'action_plans.create', 'group' => 'Planes', 'subgroup' => 'General'],
            ['name' => 'Actualizar Planes', 'slug' => 'action_plans.update', 'group' => 'Planes', 'subgroup' => 'General'],
            ['name' => 'Eliminar Planes', 'slug' => 'action_plans.delete', 'group' => 'Planes', 'subgroup' => 'General'],
            ['name' => 'Gestionar Planes', 'slug' => 'action_plans.manage', 'group' => 'Planes', 'subgroup' => 'General'],
            ['name' => 'Completar Planes', 'slug' => 'action_plans.complete', 'group' => 'Planes', 'subgroup' => 'General'],
            ['name' => 'Aprobar Cierre', 'slug' => 'action_plans.approve_closure', 'group' => 'Planes', 'subgroup' => 'General'],
            // Reportes - 10
            ['name' => 'Ver Reportes', 'slug' => 'reports.view', 'group' => 'Reportes', 'subgroup' => 'General'],
            ['name' => 'Ver Reporte', 'slug' => 'reports.view_single', 'group' => 'Reportes', 'subgroup' => 'General'],
            ['name' => 'Crear Reportes', 'slug' => 'reports.create', 'group' => 'Reportes', 'subgroup' => 'General'],
            ['name' => 'Exportar Reportes', 'slug' => 'reports.export', 'group' => 'Reportes', 'subgroup' => 'Exportación'],
            ['name' => 'Ver Dashboard', 'slug' => 'reports.dashboard', 'group' => 'Reportes', 'subgroup' => 'Dashboard'],
            ['name' => 'Reportes Cumplimiento', 'slug' => 'reports.compliance', 'group' => 'Reportes', 'subgroup' => 'Especializados'],
            ['name' => 'Reportes Auditoría', 'slug' => 'reports.audit', 'group' => 'Reportes', 'subgroup' => 'Especializados'],
            ['name' => 'Reportes Tendencias', 'slug' => 'reports.trends', 'group' => 'Reportes', 'subgroup' => 'Especializados'],
            ['name' => 'Programar Reportes', 'slug' => 'reports.schedule', 'group' => 'Reportes', 'subgroup' => 'General'],
            ['name' => 'Enviar por Email', 'slug' => 'reports.send_email', 'group' => 'Reportes', 'subgroup' => 'General'],
            // Roles - 6
            ['name' => 'Ver Roles', 'slug' => 'roles.view', 'group' => 'Roles', 'subgroup' => 'Roles'],
            ['name' => 'Crear Roles', 'slug' => 'roles.create', 'group' => 'Roles', 'subgroup' => 'Roles'],
            ['name' => 'Actualizar Roles', 'slug' => 'roles.update', 'group' => 'Roles', 'subgroup' => 'Roles'],
            ['name' => 'Eliminar Roles', 'slug' => 'roles.delete', 'group' => 'Roles', 'subgroup' => 'Roles'],
            ['name' => 'Gestionar Roles', 'slug' => 'roles.manage', 'group' => 'Roles', 'subgroup' => 'Roles'],
            ['name' => 'Asignar Permisos', 'slug' => 'roles.assign_permissions', 'group' => 'Roles', 'subgroup' => 'Permisos'],
            // Capacitación - 6
            ['name' => 'Ver Capacitaciones', 'slug' => 'training.view', 'group' => 'Capacitación', 'subgroup' => 'General'],
            ['name' => 'Crear Capacitaciones', 'slug' => 'training.create', 'group' => 'Capacitación', 'subgroup' => 'General'],
            ['name' => 'Gestionar Capacitación', 'slug' => 'training.manage', 'group' => 'Capacitación', 'subgroup' => 'General'],
            ['name' => 'Ver Constancias', 'slug' => 'training.view_certificates', 'group' => 'Capacitación', 'subgroup' => 'Certificados'],
            ['name' => 'Emitir Constancias', 'slug' => 'training.issue_certificates', 'group' => 'Capacitación', 'subgroup' => 'Certificados'],
            ['name' => 'Ver Matrícula', 'slug' => 'training.view_roster', 'group' => 'Capacitación', 'subgroup' => 'General'],
            // Notificaciones - 4
            ['name' => 'Ver Notificaciones', 'slug' => 'notifications.view', 'group' => 'Notificaciones', 'subgroup' => 'General'],
            ['name' => 'Gestionar Notif.', 'slug' => 'notifications.manage', 'group' => 'Notificaciones', 'subgroup' => 'General'],
            ['name' => 'Ver Alertas', 'slug' => 'alerts.view', 'group' => 'Notificaciones', 'subgroup' => 'Alertas'],
            ['name' => 'Gestionar Alertas', 'slug' => 'alerts.manage', 'group' => 'Notificaciones', 'subgroup' => 'Alertas'],
            // Logs - 4
            ['name' => 'Ver Logs Actividad', 'slug' => 'activity_logs.view', 'group' => 'Logs', 'subgroup' => 'Logs'],
            ['name' => 'Ver Logs Permisos', 'slug' => 'permission_logs.view', 'group' => 'Logs', 'subgroup' => 'Logs'],
            ['name' => 'Ver Bitácora', 'slug' => 'access_logs.view', 'group' => 'Logs', 'subgroup' => 'Logs'],
            ['name' => 'Exportar Logs', 'slug' => 'logs.export', 'group' => 'Logs', 'subgroup' => 'Logs'],
        ];
        foreach ($permissions as $permission) {
            DB::table('permissions')->updateOrInsert(['slug' => $permission['slug']], $permission);
        }
    }

    protected function assignPermissionsToRoles(): void
    {
        $roles = DB::table('roles')->pluck('id', 'slug');
        $permissions = DB::table('permissions')->pluck('id', 'slug');

        // Super Admin - Todos
        $this->assignAll($roles['super_admin'], $permissions);
        
        // Company Admin
        $companyAdmin = ['companies.view', 'companies.view_single', 'companies.update', 'companies.settings', 'companies.view_users', 'companies.manage_users', 'companies.view_sites', 'companies.manage_sites', 'sites.view', 'sites.create', 'sites.update', 'sites.delete', 'sites.assign_users', 'sites.view_audits', 'sites.manage_audits', 'sites.view_compliance', 'users.view', 'users.view_single', 'users.create', 'users.update', 'users.delete', 'users.assign_role', 'users.assign_sites', 'users.reset_password', 'users.deactivate', 'users.view_profile', 'regulations.view', 'regulations.view_single', 'requirements.view', 'requirements.view_single', 'requirements.view_compliance', 'evidences.view', 'evidences.view_single', 'evidences.upload', 'evidences.update', 'evidences.delete', 'evidences.approve', 'evidences.reject', 'evidences.download', 'evidences.verify', 'evidences.comment', 'audits.view', 'audits.view_single', 'audits.create', 'audits.update', 'audits.delete', 'audits.manage', 'audits.execute', 'audits.view_results', 'audits.close', 'audits.export', 'audits.schedule', 'audits.assign_auditors', 'findings.view', 'findings.view_single', 'findings.create', 'findings.update', 'findings.manage', 'findings.close', 'findings.assign_responsible', 'action_plans.view', 'action_plans.view_single', 'action_plans.create', 'action_plans.update', 'action_plans.manage', 'action_plans.complete', 'action_plans.approve_closure', 'reports.view', 'reports.view_single', 'reports.create', 'reports.export', 'reports.dashboard', 'reports.compliance', 'reports.audit', 'reports.trends', 'reports.schedule', 'reports.send_email', 'training.view', 'training.create', 'training.manage', 'training.view_certificates', 'training.issue_certificates', 'training.view_roster', 'notifications.view', 'notifications.manage', 'alerts.view', 'alerts.manage'];
        $this->assignMany($roles['company_admin'], $permissions, $companyAdmin);

        // Site Manager
        $siteManager = ['sites.view', 'sites.update', 'sites.assign_users', 'sites.view_audits', 'sites.view_compliance', 'users.view', 'users.view_single', 'users.create', 'users.update', 'users.view_profile', 'requirements.view', 'requirements.view_single', 'requirements.view_compliance', 'requirements.upload_evidence', 'evidences.view', 'evidences.view_single', 'evidences.upload', 'evidences.update', 'evidences.delete', 'evidences.approve', 'evidences.reject', 'evidences.download', 'evidences.comment', 'audits.view', 'audits.view_single', 'audits.create', 'audits.update', 'audits.manage', 'audits.execute', 'audits.view_results', 'audits.close', 'audits.assign_auditors', 'findings.view', 'findings.view_single', 'findings.create', 'findings.update', 'findings.manage', 'action_plans.view', 'action_plans.view_single', 'action_plans.create', 'action_plans.update', 'action_plans.manage', 'reports.view', 'reports.view_single', 'reports.create', 'reports.export', 'reports.dashboard', 'reports.compliance', 'reports.audit', 'training.view', 'training.manage', 'training.view_roster', 'notifications.view', 'alerts.view'];
        $this->assignMany($roles['site_manager'], $permissions, $siteManager);

        // Internal Auditor
        $internalAuditor = ['sites.view', 'sites.view_audits', 'sites.view_compliance', 'requirements.view', 'requirements.view_single', 'requirements.view_compliance', 'evidences.view', 'evidences.view_single', 'evidences.upload', 'evidences.download', 'evidences.comment', 'audits.view', 'audits.view_single', 'audits.create', 'audits.update', 'audits.manage', 'audits.execute', 'audits.view_results', 'audits.close', 'audits.export', 'audits.assign_auditors', 'findings.view', 'findings.view_single', 'findings.create', 'findings.update', 'findings.delete', 'findings.manage', 'findings.close', 'findings.assign_responsible', 'action_plans.view', 'action_plans.view_single', 'action_plans.create', 'action_plans.update', 'action_plans.manage', 'action_plans.complete', 'reports.view', 'reports.view_single', 'reports.create', 'reports.export', 'reports.audit'];
        $this->assignMany($roles['internal_auditor'], $permissions, $internalAuditor);

        // External Auditor
        $externalAuditor = ['sites.view', 'sites.view_audits', 'sites.view_compliance', 'requirements.view', 'requirements.view_single', 'requirements.view_compliance', 'evidences.view', 'evidences.view_single', 'evidences.download', 'evidences.comment', 'audits.view', 'audits.view_single', 'audits.create', 'audits.execute', 'audits.view_results', 'audits.export', 'findings.view', 'findings.view_single', 'findings.create', 'findings.manage', 'findings.close', 'reports.view', 'reports.view_single', 'reports.export', 'reports.audit'];
        $this->assignMany($roles['external_auditor'], $permissions, $externalAuditor);

        // Compliance Officer
        $complianceOfficer = ['sites.view', 'sites.view_audits', 'sites.view_compliance', 'requirements.view', 'requirements.view_single', 'requirements.view_compliance', 'evidences.view', 'evidences.view_single', 'evidences.upload', 'evidences.update', 'evidences.approve', 'evidences.reject', 'evidences.download', 'evidences.verify', 'evidences.comment', 'audits.view', 'audits.view_single', 'audits.view_results', 'audits.export', 'reports.view', 'reports.view_single', 'reports.create', 'reports.export', 'reports.dashboard', 'reports.compliance', 'reports.trends', 'reports.schedule', 'reports.send_email', 'notifications.view', 'alerts.view', 'alerts.manage'];
        $this->assignMany($roles['compliance_officer'], $permissions, $complianceOfficer);

        // Training Manager
        $trainingManager = ['sites.view', 'sites.view_compliance', 'requirements.view', 'requirements.view_single', 'requirements.view_compliance', 'evidences.view', 'evidences.view_single', 'evidences.upload', 'evidences.download', 'reports.view', 'reports.view_single', 'reports.create', 'reports.export', 'reports.dashboard', 'reports.compliance', 'training.view', 'training.create', 'training.manage', 'training.view_certificates', 'training.issue_certificates', 'training.view_roster', 'notifications.view', 'alerts.view'];
        $this->assignMany($roles['training_manager'], $permissions, $trainingManager);

        // Employee
        $employee = ['sites.view', 'requirements.view', 'requirements.view_single', 'requirements.upload_evidence', 'evidences.view', 'evidences.view_single', 'evidences.upload', 'evidences.download', 'evidences.comment', 'reports.view', 'reports.dashboard', 'training.view', 'training.view_roster', 'training.view_certificates', 'notifications.view'];
        $this->assignMany($roles['employee'], $permissions, $employee);

        // Viewer
        $viewer = ['sites.view', 'requirements.view', 'requirements.view_single', 'evidences.view', 'evidences.view_single', 'reports.view', 'reports.dashboard', 'training.view'];
        $this->assignMany($roles['viewer'], $permissions, $viewer);
    }

    protected function assignAll($roleId, $permissions): void
    {
        if (!$roleId) return;
        foreach ($permissions as $slug => $id) {
            DB::table('permission_role')->updateOrInsert(['permission_id' => $id, 'role_id' => $roleId], ['permission_id' => $id, 'role_id' => $roleId]);
        }
    }

    protected function assignMany($roleId, $permissions, array $slugs): void
    {
        if (!$roleId) return;
        foreach ($slugs as $slug) {
            if (isset($permissions[$slug])) {
                DB::table('permission_role')->updateOrInsert(['permission_id' => $permissions[$slug], 'role_id' => $roleId], ['permission_id' => $permissions[$slug], 'role_id' => $roleId]);
            }
        }
    }
}
