<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration para extender el sistema de permisos con:
 * - 80+ permisos granulares organizados por módulo
 * - Permisos específicos para cada rol
 * - Campos adicionales para control de acceso
 */
return new class extends Migration
{
    public function up(): void
    {
        // Extender tabla de permisos con campos adicionales
        Schema::table('permissions', function (Blueprint $table) {
            if (!Schema::hasColumn('permissions', 'group')) {
                $table->string('group')->nullable()->after('description');
            }
            if (!Schema::hasColumn('permissions', 'subgroup')) {
                $table->string('subgroup')->nullable()->after('group');
            }
            if (!Schema::hasColumn('permissions', 'slug')) {
                $table->string('slug')->unique()->after('name');
            }
        });

        // Tabla para user_sites (asignación directa de usuarios a sedes)
        if (!Schema::hasTable('user_sites')) {
            Schema::create('user_sites', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('site_id')->constrained()->cascadeOnDelete();
                $table->timestamp('assigned_at')->useCurrent();
                $table->foreignId('assigned_by')->nullable()->constrained('users');
                $table->unique(['user_id', 'site_id']);
                $table->index(['user_id']);
                $table->index(['site_id']);
            });
        }

        // Tabla para user_permissions con expiración
        Schema::create('user_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('permission_id')->constrained()->cascadeOnDelete();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('granted_at')->useCurrent();
            $table->foreignId('granted_by')->nullable()->constrained('users');
            $table->text('reason')->nullable();
            $table->unique(['user_id', 'permission_id']);
            $table->index(['user_id']);
            $table->index(['permission_id']);
            $table->index(['expires_at']);
        });

        // Tabla para user_roles con contexto de sede
        Schema::table('role_user', function (Blueprint $table) {
            if (!Schema::hasColumn('role_user', 'site_id')) {
                $table->foreignId('site_id')->nullable()->after('user_id')->constrained()->nullOnDelete();
                $table->index(['site_id']);
            }
            if (!Schema::hasColumn('role_user', 'expires_at')) {
                $table->timestamp('expires_at')->nullable()->after('role_id');
                $table->index(['expires_at']);
            }
        });

        // Tabla para roles con jerarquía
        Schema::table('roles', function (Blueprint $table) {
            if (!Schema::hasColumn('roles', 'parent_id')) {
                $table->foreignId('parent_id')->nullable()->after('id')->constrained('roles')->nullOnDelete();
            }
            if (!Schema::hasColumn('roles', 'hierarchy_level')) {
                $table->integer('hierarchy_level')->default(0)->after('parent_id');
            }
            if (!Schema::hasColumn('roles', 'is_system')) {
                $table->boolean('is_system')->default(false)->after('hierarchy_level');
            }
            if (!Schema::hasColumn('roles', 'color')) {
                $table->string('color', 7)->nullable()->after('is_system');
            }
        });

        // Tabla de activity_logs para auditoría de permisos
        Schema::create('permission_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('action'); // granted, revoked, expired
            $table->string('permission_slug');
            $table->string('target_user_type')->nullable();
            $table->foreignId('target_user_id')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->text('reason')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
            $table->index(['user_id']);
            $table->index(['action']);
            $table->index(['permission_slug']);
            $table->index(['created_at']);
        });

        // Índices adicionales para performance
        Schema::table('permissions', function (Blueprint $table) {
            $table->index(['group']);
            $table->index(['subgroup']);
        });

        Schema::table('permission_role', function (Blueprint $table) {
            $table->index(['role_id']);
            $table->index(['permission_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permission_audit_logs');
        
        if (Schema::hasColumn('role_user', 'site_id')) {
            Schema::table('role_user', function (Blueprint $table) {
                $table->dropForeign(['site_id']);
                $table->dropColumn(['site_id', 'expires_at']);
            });
        }

        Schema::dropIfExists('user_permissions');
        
        if (Schema::hasTable('user_sites')) {
            Schema::table('user_sites', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
                $table->dropForeign(['site_id']);
            });
            Schema::dropIfExists('user_sites');
        }

        Schema::table('roles', function (Blueprint $table) {
            if (Schema::hasColumn('roles', 'parent_id')) {
                $table->dropForeign(['parent_id']);
                $table->dropColumn(['parent_id', 'hierarchy_level', 'is_system', 'color']);
            }
        });

        Schema::table('permissions', function (Blueprint $table) {
            $table->dropIndex(['group']);
            $table->dropIndex(['subgroup']);
        });
    }
};
