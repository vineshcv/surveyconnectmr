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
        Schema::create('participants', function (Blueprint $table) {
            $table->id();
            $table->string('participant_id')->unique();
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->foreignId('vendor_id')->nullable()->constrained('vendors')->onDelete('set null');
            $table->string('uid')->nullable(); // Respondent ID
            $table->tinyInteger('status')->default(1); // 1=Complete, 2=Terminate, 3=Quota Full, 4=Security Full, 5=LOI Fail, 6=IR Count, 7=IP Fail, 8=URL Error, 9=Unknown, 10=Already Participated
            $table->decimal('loi', 8, 2)->nullable(); // Length of Interview
            $table->timestamp('start_loi')->nullable();
            $table->timestamp('end_loi')->nullable();
            $table->string('participant_ip')->nullable();
            $table->timestamps();
            
            // Indexes for better performance
            $table->index(['project_id', 'status']);
            $table->index(['vendor_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participants');
    }
};