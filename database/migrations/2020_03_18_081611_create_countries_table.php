<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code',2);
            $table->string('name',64);
            $table->string('full_name',128);
            $table->string('iso3',3);
            $table->integer('number');
            $table->string('continent_code',2);
            $table->integer('display_order')->default(900);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('countries');
    }
}
