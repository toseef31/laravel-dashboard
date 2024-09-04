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
        Schema::create('twofactortimeline', function (Blueprint $table) {
            $table->id();
            $table->string('two_factor_secret')->nullable();
            $table->string('two_factor_type')->nullable();
            $table->string('two_factor_signature')->nullable();
            $table->string('otp')->nullable();
            $table->string('expires_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('twofactortimeline');
    }
};
