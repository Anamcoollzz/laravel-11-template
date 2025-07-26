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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 191);
            $table->string('email', 191)->nullable()->unique();
            $table->string('avatar')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 191);
            $table->dateTime('last_login')->nullable();
            $table->string('email_token', 191)->nullable();
            $table->string('verification_code', 6)->nullable();
            $table->boolean('is_locked')->default(0);
            $table->string('phone_number', 50)->nullable();
            $table->date('birth_date')->nullable();
            $table->text('address')->nullable();
            $table->dateTime('last_password_change')->nullable();
            $table->string('twitter_id', 30)->nullable()->unique();
            $table->text('file_upload')->nullable();
            $table->tinyInteger('wrong_login')->default(0);
            $table->boolean('is_active')->default(1);
            $table->rememberToken();
            $table->timestamps();
            $table->unsignedBigInteger('created_by_id')->nullable()->foreign('created_by_id')->references('id')->on('users')->onDelete('set null');
            $table->unsignedBigInteger('last_updated_by_id')->nullable()->foreign('last_updated_by_id')->references('id')->on('users')->onDelete('set null');
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
