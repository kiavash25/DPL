<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMainPageSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mainPageSettings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('header');
            $table->string('pic')->nullable();
            $table->text('text')->nullable();
            $table->string('lang')->default('en');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mainPageSettings');
    }
}
