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
        Schema::create('company_info', function (Blueprint $table) {
            $table->id();
            $table->string('company_name')->nullable();
            $table->string('company_tax_number', 10)->nullable();
            $table->string('company_street', 255)->nullable();
            $table->string('company_street_number', 255)->nullable();
            $table->string('company_zip_code', 5)->nullable();
            $table->string('company_city', 255)->nullable();
            $table->string('company_state', 255)->nullable();
            $table->string('company_country', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_info');
    }
};
