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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('rfc', 13)->unique();
            $table->string('tax_id')->nullable();
            $table->string('industry')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip_code', 10)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('email');
            $table->string('website')->nullable();
            $table->string('logo_path')->nullable();
            $table->integer('employee_count')->nullable();
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'status']);
            $table->index('rfc');
            $table->unique(['tenant_id', 'rfc']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
