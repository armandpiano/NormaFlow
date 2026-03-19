<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // auditor
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('audit_type', ['interna', 'externa', 'certificacion'])->default('interna');
            $table->date('planned_start_date');
            $table->date('planned_end_date')->nullable();
            $table->date('actual_start_date')->nullable();
            $table->date('actual_end_date')->nullable();
            $table->enum('status', ['planificada', 'en_proceso', 'completada', 'cancelada'])->default('planificada');
            $table->text('scope')->nullable();
            $table->text('methodology')->nullable();
            $table->json('checklist')->nullable();
            $table->json('results_summary')->nullable();
            $table->text('conclusions')->nullable();
            $table->text('recommendations')->nullable();
            $table->timestamps();
            
            $table->index(['site_id', 'status']);
            $table->index(['user_id', 'status']);
            $table->index('status');
        });

        Schema::create('audit_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('audit_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('role'); // auditor_lider, auditor, observado
            $table->text('responsibilities')->nullable();
            $table->timestamps();
            
            $table->unique(['audit_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_participants');
        Schema::dropIfExists('audits');
    }
};
