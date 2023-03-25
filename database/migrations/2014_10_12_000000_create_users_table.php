<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
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
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
