<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDamageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('damage', function (Blueprint $table) {
            $table->Increments('id');
            $table->string('type',250)->nullable();

            $table->unsignedInteger('id_employee');
            $table->foreign('id_employee','fk_damage_employee')->references('id')->on('user')->onDelete('cascade')->onUpdate('cascade');

            $table->string('status',250)->nullable();


            $table->unsignedInteger('id_checkout')->nullable();
            $table->foreign('id_checkout','fk_damage_checkout')->references('id')->on('check_out')->onDelete('cascade')->onUpdate('cascade');

            
            $table->unsignedInteger('id_fleet')->nullable();
            $table->foreign('id_fleet','fk_damage_fleet')->references('id')->on('fleet')->onDelete('cascade')->onUpdate('cascade');


            $table->unsignedInteger('id_tool')->nullable();
            $table->foreign('id_tool','fk_damage_tool')->references('id')->on('tool')->onDelete('cascade')->onUpdate('cascade');

            $table->longText('explanation')->nullable();

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
        Schema::dropIfExists('damage');
    }
}
