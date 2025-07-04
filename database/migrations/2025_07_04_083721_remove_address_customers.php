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
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('company_street');
            $table->dropColumn('company_city');
            $table->dropColumn('company_zip');
            $table->dropColumn('company_country');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('company_street')->nullable();
            $table->string('company_city')->nullable();
            $table->string('company_zip')->nullable();
            $table->string('company_country')->nullable();
        });
    }
};
