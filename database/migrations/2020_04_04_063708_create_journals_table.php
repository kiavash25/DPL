<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJournalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('journals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('slug');
            $table->string('summery')->nullable();
            $table->longText('text');
            $table->string('pic')->nullable();
            $table->unsignedBigInteger('categoryId');
            $table->string('releaseDate')->default('draft');
            $table->text('meta');
            $table->string('seoTitle', 255);
            $table->string('keyword', 200);
            $table->unsignedBigInteger('userId');
            $table->string('lang', 5)->default('en');
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
        Schema::dropIfExists('journals');
    }
}
