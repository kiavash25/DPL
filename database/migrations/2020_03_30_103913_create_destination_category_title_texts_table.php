<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDestinationCategoryTitleTextsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('destinationCategoryTitleTexts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('destId');
            $table->unsignedBigInteger('titleId');
            $table->longText('text');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('destinationCategoryTitleTexts');
    }
}
