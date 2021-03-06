<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackageMoreInfoRelationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packageMoreInfoRelations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('packageId');
            $table->unsignedInteger('moreInfoId');
            $table->text('text');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('packageMoreInfoRelations');
    }
}
