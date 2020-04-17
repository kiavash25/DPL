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
            $table->string('slug');
            $table->text('description')->nullable();
            $table->string('pic')->nullable();
            $table->string('day')->nullable();
            $table->string('season', 20)->nullable();
            $table->string('sDate', 20)->nullable();
            $table->string('eDate',20)->nullable();
            $table->string('money')->default(0);
            $table->string('lat')->nullable();
            $table->string('lng')->nullable();
            $table->unsignedInteger('mainActivityId')->nullable();
            $table->tinyInteger('showPack')->default(0);
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
