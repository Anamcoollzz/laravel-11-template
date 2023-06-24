<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        Schema::create('persons', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('nik')->unique();
            $table->string('village_code', 13);
            // $table->foreign('village_code')->references('code')->on('regions');
            $table->string('district_code', 13);
            // $table->foreign('district_code')->references('code')->on('regions');
            $table->string('city_code', 13);
            // $table->foreign('city_code')->references('code')->on('regions');
            $table->string('province_code', 13);
            // $table->foreign('province_code')->references('code')->on('regions');
            $table->unsignedBigInteger('pic_id');
            $table->foreign('pic_id')->references('id')->on('users');
            $table->timestamps();
        });
        // DB::statement("ALTER TABLE `persons` ADD FOREIGN KEY (`city_code`) REFERENCES `regions`(`code`) ON DELETE CASCADE ON UPDATE CASCADE;");
        // DB::statement("ALTER TABLE `persons` ADD FOREIGN KEY (`district_code`) REFERENCES `regions`(`code`) ON DELETE CASCADE ON UPDATE CASCADE;");
        // DB::statement("ALTER TABLE `persons` ADD FOREIGN KEY (`province_code`) REFERENCES `regions`(`code`) ON DELETE CASCADE ON UPDATE CASCADE;");
        // DB::statement("ALTER TABLE `persons` ADD FOREIGN KEY (`village_code`) REFERENCES `regions`(`code`) ON DELETE CASCADE ON UPDATE CASCADE;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('persons');
    }
};
