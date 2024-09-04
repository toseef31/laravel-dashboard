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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('book_id')->unique();
            # Book Details
            $table->string('title');
            $table->string('author');
            $table->text('description');
            $table->integer('pages')->default(0);
            $table->integer('size')->nullable();
            # Publisher Details
            $table->string('edition')->nullable();
            $table->string('publisher')->nullable();
            $table->string('publication_year')->nullable();
            # Availability
            $table->string('status')->default('for_sale');
            # Book Condition
            $table->text('book_condition')->nullable();
            $table->text('jacket_condition')->nullable();
            $table->text('comment')->nullable();
            # Dates
            $table->string('add_date')->nullable();
            $table->string('sold_date')->nullable();
            # Buyer Details
            $table->string('buyer_name')->nullable();
            $table->string('buyer_email')->nullable();
            # Costing
            $table->float('sold_price')->default(0.00)->nullable();
            $table->float('cost_price')->default(0.00)->nullable();
            $table->string('valuation')->nullable();
            # Timestamps
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
