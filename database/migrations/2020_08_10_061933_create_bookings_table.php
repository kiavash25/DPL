<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 30);
            $table->unsignedInteger('eventId');
            $table->string('eventKind');
            $table->unsignedInteger('userId');
            $table->string('firstName');
            $table->string('lastName');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('nationalId', 30);
            $table->unsignedInteger('countryId');
            $table->tinyInteger('gender');
            $table->string('nationalPic');
            $table->string('birthDate', 30);
            $table->string('address', 300)->nullable();
            $table->string('addressCode')->nullable();
            $table->string('paymentCode')->nullable();
            $table->string('sDate', 20)->nullable();
            $table->string('eDate', 20)->nullable();
            $table->unsignedInteger('relatedId')->default(0);
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
        Schema::dropIfExists('bookings');
    }
}
