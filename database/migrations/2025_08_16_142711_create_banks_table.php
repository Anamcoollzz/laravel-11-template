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
        Schema::create('banks', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique();
            $table->enum('bank_type', ['Syariah', 'Konvensional'])->default('Konvensional');
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->unsignedBigInteger('last_updated_by_id')->nullable();
            $table->foreign('created_by_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('last_updated_by_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banks');
    }
};
