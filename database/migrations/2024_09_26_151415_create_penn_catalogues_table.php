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
        Schema::create('penn_catalogues', function (Blueprint $table) {
            $table->id();
            $table->text('name')->nullable();
            $table->text('year')->nullable();

            $table->text('catalogue_no')->nullable();
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
        Schema::dropIfExists('penn_catalogues');
    }
};
