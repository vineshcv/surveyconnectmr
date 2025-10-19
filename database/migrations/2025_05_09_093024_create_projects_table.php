<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('project_type', ['single', 'multiple', 'unique']);
            $table->text('specifications')->nullable();
            $table->integer('quota')->nullable();
            $table->string('loi')->nullable();
            $table->string('ir')->nullable();
            $table->enum('status', ['live', 'pause', 'invoice', 'ir', 'commission', 'cancelled']);
            $table->foreignId('client_id')->constrained('clients');
            $table->foreignId('login_type_id')->nullable()->constrained('roles');
            $table->boolean('enable_questions')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('projects');
    }
};
