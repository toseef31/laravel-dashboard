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
            $table->text('makers_name')->nullable()->default('Hardy');
            $table->text('model')->nullable();
            $table->text('sub_model')->nullable();
            $table->text('size')->nullable();
            $table->text('approximate_date')->nullable();

            $table->text('foot')->nullable();
            $table->text('handle')->nullable();
            $table->text('tension_regultor')->nullable();
            $table->text('details')->nullable();
            $table->text('condition')->nullable();

            $table->string('add_date')->nullable();
            $table->string('valuation')->nullable();
            $table->string('cost_price')->nullable();

            $table->string('sold_date')->nullable();
            $table->string('sold_price')->nullable();
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
