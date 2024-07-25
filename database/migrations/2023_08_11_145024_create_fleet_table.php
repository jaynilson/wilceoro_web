<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFleetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fleet', function (Blueprint $table) {
            $table->Increments('id');
            $table->string('n',250)->unique();
            $table->string('model',250)->nullable();
            $table->string('licence_plate',250)->nullable();
            $table->string('year',250)->nullable();
            $table->string('yard_location',250)->nullable();
            $table->string('department',250)->nullable();
            $table->string('status',250)->nullable();
            $table->string('picture',250)->nullable();
            $table->string('type',250)->nullable();
            $table->string('category',250)->nullable();
            $table->string('current_yard_location',250)->nullable();
            $table->integer('last_odometer')->nullable();
            $table->string('vin',250)->nullable();
            $table->double('price',9,2)->nullable();
            $table->date('insurance_expiration_date')->nullable();
            $table->date('lease_rental_return_date')->nullable();
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
        Schema::dropIfExists('fleet');
    }
}
