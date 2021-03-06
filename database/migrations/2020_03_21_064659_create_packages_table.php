<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('destId');
            $table->string('name');
            $table->string('specialName')->nullable();
            $table->string('slug');
            $table->text('description')->nullable();
            $table->string('pic')->nullable();
            $table->string('day')->nullable();
            $table->string('season', 20)->nullable();
            $table->tinyInteger('freeTime')->default(0);
            $table->string('sDate', 20)->nullable();
            $table->string('eDate',20)->nullable();
            $table->string('registerSDate', 20)->nullable();
            $table->string('registerEDate', 20)->nullable();
            $table->integer('capacity')->nullable();
            $table->integer('minRegister')->default(0);
            $table->integer('maxRegister')->nullable();
            $table->string('money')->default(0);
            $table->string('lat')->nullable();
            $table->string('lng')->nullable();
            $table->string('level', 10)->default('easy');
            $table->integer('code');
            $table->string('brochure')->nullable();
            $table->unsignedInteger('mainActivityId')->nullable();
            $table->tinyInteger('showPack')->default(0);
            $table->string('lang', 5)->default('en');
            $table->unsignedInteger('langSource')->default(0);
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
        Schema::dropIfExists('packages');
    }
}
