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
        Schema::create('hardy_reels', function (Blueprint $table) {
            $table->id();
            $table->string('reel_id')->unique();
            $table->string('makers_name')->nullable();
            $table->string('model')->nullable();
            $table->string('sub_model')->nullable();
            $table->string('size')->nullable();
            $table->string('approximate_date')->nullable();

            $table->string('foot')->nullable();
            $table->string('handle')->nullable();
            $table->string('tension_regultor')->nullable();
            $table->string('details')->nullable();
            $table->string('condition')->nullable();

            $table->string('add_date')->nullable();
            $table->float('valuation')->nullable();
            $table->float('cost_price')->nullable();

            $table->string('sold_date')->nullable();
            $table->float('sold_price')->nullable();
            $table->string('buyer_name')->nullable();
            $table->string('buyer_email')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hardy_reels');
    }
};
