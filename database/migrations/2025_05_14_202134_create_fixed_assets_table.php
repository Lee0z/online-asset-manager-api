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
        Schema::create('fixed_assets', function (Blueprint $table) {
            $table->id();
            $table->string('asset_name', 1024);
            $table->string('kst_symbol', 3)->nullable();
            $table->float('initial_value')->nullable();
            $table->float('current_value')->nullable();
            $table->string('acquisition_document_type', 255)->nullable();
            $table->float('depreciation_rate')->nullable();
            $table->string('status', 255)->nullable();
            $table->date('acquisition_date')->nullable();
            $table->date('commissioning_date')->nullable();
            $table->date('liquidation_date')->nullable();
            $table->text('liquidation_reason')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fixed_assets');
    }
};
