<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('regulations', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('type'); // NOM, STPS, NOM-035, etc.
            $table->string('authority'); // STPS, SEMARNAT, COFEPRIS, etc.
            $table->string('scope'); // Federal, Estatal
            $table->date('effective_date')->nullable();
            $table->date('review_date')->nullable();
            $table->string('url')->nullable();
            $table->json('metadata')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index('type');
            $table->index('authority');
            $table->index('is_active');
        });

        Schema::create('normative_matrices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->json('settings')->nullable();
            $table->timestamps();
            
            $table->index('company_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('normative_matrices');
        Schema::dropIfExists('regulations');
    }
};
