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
        Schema::create('vendor_mappings', function (Blueprint $table) {
            $table->id();
            $table->string('mapping_id')->unique();
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->foreignId('vendor_id')->constrained('vendors')->onDelete('cascade');
            $table->string('study_url')->nullable();
            $table->string('study_test_url')->nullable();
            $table->string('success_url')->nullable();
            $table->string('terminate_url')->nullable();
            $table->string('over_quota_url')->nullable();
            $table->timestamps();
            
            // Ensure unique combination of project and vendor
            $table->unique(['project_id', 'vendor_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_mappings');
    }
};