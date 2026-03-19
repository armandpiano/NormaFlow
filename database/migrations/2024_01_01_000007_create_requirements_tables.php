<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('requirements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('regulation_id')->constrained()->cascadeOnDelete();
            $table->string('code', 50);
            $table->text('description');
            $table->enum('obligation_type', ['obligatorio', 'cumplimiento_opcional'])->default('obligatorio');
            $table->enum('frequency', ['unico', 'mensual', 'trimestral', 'semestral', 'anual', 'bisemestral'])->nullable();
            $table->string('evidence_type'); // documento, fotografia, video, registro
            $table->integer('expiration_days')->default(365);
            $table->json('criteria')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->unique(['regulation_id', 'code']);
            $table->index('obligation_type');
        });

        Schema::create('normative_matrix_requirements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('normative_matrix_id')->constrained()->cascadeOnDelete();
            $table->foreignId('requirement_id')->constrained()->cascadeOnDelete();
            $table->foreignId('site_id')->nullable()->constrained()->nullOnDelete();
            $table->date('assigned_date')->nullable();
            $table->date('due_date')->nullable();
            $table->enum('status', ['pending', 'in_progress', 'compliant', 'non_compliant', 'not_applicable'])->default('pending');
            $table->timestamps();
            
            $table->unique(['normative_matrix_id', 'requirement_id', 'site_id']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('normative_matrix_requirements');
        Schema::dropIfExists('requirements');
    }
};
