<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShotsCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shotsCategories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('lang', 10)->default('en');
            $table->unsignedInteger('sourceId')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shotsCategories');
    }
}
