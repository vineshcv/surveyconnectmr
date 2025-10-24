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
        Schema::table('vendor_registrations', function (Blueprint $table) {
            $table->string('status')->default('pending')->after('pincode');
            $table->string('username')->nullable()->after('status');
            $table->string('password')->nullable()->after('username');
            $table->unsignedBigInteger('approved_by')->nullable()->after('password');
            $table->timestamp('approved_at')->nullable()->after('approved_by');
            $table->text('rejected_reason')->nullable()->after('approved_at');
            
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendor_registrations', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropColumn([
                'status',
                'username', 
                'password',
                'approved_by',
                'approved_at',
                'rejected_reason'
            ]);
        });
    }
};