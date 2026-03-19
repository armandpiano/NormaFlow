<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evidences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('requirement_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('site_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('file_path');
            $table->string('original_name');
            $table->string('mime_type');
            $table->integer('file_size');
            $table->timestamp('document_date')->nullable();
            $table->timestamp('valid_from')->nullable();
            $table->timestamp('valid_until')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'expired'])->default('pending');
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            $table->index(['requirement_id', 'status']);
            $table->index(['site_id', 'status']);
            $table->index('status');
            $table->index('valid_until');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evidences');
    }
};
