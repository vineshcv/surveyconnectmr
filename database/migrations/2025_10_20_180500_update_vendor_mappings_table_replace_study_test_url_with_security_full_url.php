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
        Schema::table('vendor_mappings', function (Blueprint $table) {
            // Add the new security_full_url column
            $table->string('security_full_url')->nullable()->after('study_url');
            
            // Drop the old study_test_url column
            $table->dropColumn('study_test_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendor_mappings', function (Blueprint $table) {
            // Add back the study_test_url column
            $table->string('study_test_url')->nullable()->after('study_url');
            
            // Drop the security_full_url column
            $table->dropColumn('security_full_url');
        });
    }
};
