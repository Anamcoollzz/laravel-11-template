<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCrudExamplesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crud_examples', function (Blueprint $table) {
            $table->id();
            $table->string('text');
            $table->string('barcode');
            $table->string('qr_code');
            $table->string('email');
            $table->double('number');
            $table->double('currency');
            $table->double('currency_idr');
            $table->string('select');
            $table->string('select2');
            $table->text('select2_multiple');
            $table->text('textarea');
            $table->string('radio');
            $table->string('checkbox');
            $table->string('checkbox2');
            $table->text('tags');
            $table->text('file');
            $table->text('image');
            $table->date('date');
            $table->time('time');
            $table->string('color', 10);
            $table->text('summernote_simple');
            $table->longText('summernote');
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
        Schema::dropIfExists('crud_examples');
    }
}
