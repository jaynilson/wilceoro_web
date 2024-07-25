<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCheckOutTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('check_out', function (Blueprint $table) {
            $table->Increments('id');
            $table->string('type',250)->nullable();
            $table->string('status',250)->nullable();
            $table->string('lat',250)->nullable();
            $table->string('lng',250)->nullable();
            $table->string('place',250)->nullable();
            $table->string('problem_found',250)->nullable();
            $table->integer('odometer_reading')->nullable();
            $table->date('return_date')->nullable();
            $table->integer('quantity')->nullable();
            $table->unsignedInteger('id_fleet')->nullable();
            $table->foreign('id_fleet','fk_check_out_fleet')->references('id')->on('fleet')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedInteger('id_tool')->nullable();
            $table->foreign('id_tool','fk_check_out_tool')->references('id')->on('tool')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedInteger('id_employee');
            $table->foreign('id_employee','fk_check_out_employee')->references('id')->on('user')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('check_out');
    }
}
