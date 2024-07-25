<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLostTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lost', function (Blueprint $table) {
            $table->Increments('id');
            $table->string('lost',50)->nullable();
            $table->string('stolen',50)->nullable();
            $table->longText('details')->nullable();
            $table->string('last_seen',250)->nullable();
            $table->string('ampm',50)->nullable();
            $table->string('time',250)->nullable();
            $table->date('date_incident')->nullable();


            $table->unsignedInteger('id_employee');
            $table->foreign('id_employee','fk_lost_employee')->references('id')->on('user')->onDelete('cascade')->onUpdate('cascade');

            $table->string('status',250)->nullable();


            $table->unsignedInteger('id_tool')->nullable();
            $table->foreign('id_tool','fk_lost_tool')->references('id')->on('tool')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedInteger('id_checkout')->nullable();
            $table->foreign('id_checkout','fk_lost_checkout')->references('id')->on('check_out')->onDelete('cascade')->onUpdate('cascade');


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
        Schema::dropIfExists('lost');
    }
}
