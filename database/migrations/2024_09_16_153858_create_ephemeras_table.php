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
        Schema::create('ephemeras', function (Blueprint $table) {
            $table->id();
            $table->string('ephemera_id')->unique();
            $table->string('type')->nullable();
            $table->text('details')->nullable();
            $table->string('size')->nullable();
            $table->string('approximate_date')->nullable();

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
        Schema::dropIfExists('ephemeras');
    }
};
