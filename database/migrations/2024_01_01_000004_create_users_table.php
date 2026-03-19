<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('company_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('site_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('email');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('role'); // super_admin, company_admin, site_manager, auditor, employee
            $table->string('position')->nullable();
            $table->string('department')->nullable();
            $table->string('employee_id')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('avatar')->nullable();
            $table->rememberToken();
            $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_ip')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tenant_id', 'email']);
            $table->index(['tenant_id', 'company_id', 'is_active']);
            $table->index('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
