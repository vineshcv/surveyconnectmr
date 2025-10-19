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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('partner_id')->constrained('vendors');
            $table->integer('total_projects');
            $table->decimal('total_amount', 10, 2);
            $table->boolean('include_gst')->default(true);
            $table->decimal('gst', 10, 2);
            $table->enum('status', ['invoiced', 'paid', 'rejected'])->default('invoiced');
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
