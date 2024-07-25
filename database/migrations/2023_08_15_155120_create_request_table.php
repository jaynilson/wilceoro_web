<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request', function (Blueprint $table) {
            $table->Increments('id');
            $table->string('type',250)->nullable();

            $table->unsignedInteger('id_employee');
            $table->foreign('id_employee','fk_request_employee')->references('id')->on('user')->onDelete('cascade')->onUpdate('cascade');

            $table->string('status',250)->nullable();

            $table->integer('cant')->nullable();
            
            $table->unsignedInteger('id_fleet')->nullable();
            $table->foreign('id_fleet','fk_request_fleet')->references('id')->on('fleet')->onDelete('cascade')->onUpdate('cascade');


            $table->unsignedInteger('id_tool')->nullable();
            $table->foreign('id_tool','fk_request_tool')->references('id')->on('tool')->onDelete('cascade')->onUpdate('cascade');

            $table->date('date_needed')->nullable();

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
        Schema::dropIfExists('request');
    }
}
