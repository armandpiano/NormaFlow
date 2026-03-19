<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('findings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('audit_id')->constrained()->cascadeOnDelete();
            $table->foreignId('requirement_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('site_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('severity', ['critico', 'mayor', 'menor', 'observacion'])->default('menor');
            $table->string('title');
            $table->text('description');
            $table->text('root_cause')->nullable();
            $table->text('immediate_action')->nullable();
            $table->text('recommendation')->nullable();
            $table->enum('status', ['abierto', 'en_accion', 'verificado', 'cerrado'])->default('abierto');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();
            
            $table->index(['audit_id', 'status']);
            $table->index('severity');
        });

        Schema::create('action_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('finding_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // responsable
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('start_date')->nullable();
            $table->date('due_date')->nullable();
            $table->date('completed_at')->nullable();
            $table->enum('status', ['pendiente', 'en_proceso', 'completado', 'vencido'])->default('pendiente');
            $table->enum('priority', ['alta', 'media', 'baja'])->default('media');
            $table->text('evidence')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['finding_id', 'status']);
            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('action_plans');
        Schema::dropIfExists('findings');
    }
};
