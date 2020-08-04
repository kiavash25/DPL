<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateForumTopicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forumTopics', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('userId');
            $table->string('title', 200);
            $table->text('text');
            $table->unsignedBigInteger('categoryId');
            $table->integer('like')->default(0);
            $table->integer('dislike')->default(0);
            $table->integer('seen')->default(0);
            $table->unsignedBigInteger('bestAnsId')->nullable();
            $table->string('lang', 10)->default('en');
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
        Schema::dropIfExists('forumTopics');
    }
}
