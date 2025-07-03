<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('street')->nullable();
            $table->string('city')->nullable();
            $table->string('zip')->nullable();
            $table->string('country')->nullable();
            $table->string('email');
            $table->string('phone_number');
            $table->boolean('is_legal_entity');
            $table->string('company_name')->nullable();
            $table->string('company_street')->nullable();
            $table->string('company_city')->nullable();
            $table->string('company_zip')->nullable();
            $table->string('company_country')->nullable();
            $table->string('company_vat')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
