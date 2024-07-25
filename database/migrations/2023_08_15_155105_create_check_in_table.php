<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCheckInTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('check_in', function (Blueprint $table) {
            $table->Increments('id');
            $table->string('type',250)->nullable();
            $table->string('status',250)->nullable();
            $table->string('lat',250)->nullable();
            $table->string('lng',250)->nullable();
            $table->string('place',250)->nullable();
            $table->integer('odometer_reading')->nullable();


            $table->integer('quantity')->nullable();

            $table->unsignedInteger('id_fleet')->nullable();
            $table->foreign('id_fleet','fk_check_in_fleet')->references('id')->on('fleet')->onDelete('cascade')->onUpdate('cascade');

            

            $table->unsignedInteger('id_tool')->nullable();
            $table->foreign('id_tool','fk_check_in_tool')->references('id')->on('tool')->onDelete('cascade')->onUpdate('cascade');


            $table->unsignedInteger('id_employee');
            $table->foreign('id_employee','fk_check_in_employee')->references('id')->on('user')->onDelete('cascade')->onUpdate('cascade');



            $table->unsignedInteger('id_check_out')->nullable();
            $table->foreign('id_check_out','fk_check_in_check_out')->references('id')->on('check_out')->onDelete('cascade')->onUpdate('cascade');

            
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
        Schema::dropIfExists('check_in');
    }
}
