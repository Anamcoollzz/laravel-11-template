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
        Schema::create('bank_deposit_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bank_deposit_id')->nullable();
            $table->foreign('bank_deposit_id')->references('id')->on('bank_deposits')->onUpdate('set null')->onDelete('set null');
            $table->double('per_anum')->comment('percentage');
            $table->double('amount');
            $table->double('tax_percentage')->default(2);
            $table->double('tax');
            $table->double('estimation');
            $table->string('time_period', 50);
            $table->date('due_date')->nullable();
            // $table->enum('status', ['Aktif', 'Tidak Aktif'])->default('Aktif');
            $table->double('realization')->nullable();
            $table->double('difference')->nullable();

            // wajib
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
        Schema::dropIfExists('bank_deposit_histories');
    }
};
