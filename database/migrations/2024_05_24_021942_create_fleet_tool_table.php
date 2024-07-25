<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFleetToolTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fleet_tool', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('id_fleet');
            $table->string('id_tool');
            $table->date('assign_date')->nullable();
            $table->date('return_date')->nullable();
            $table->string('note', 255)->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->foreign('id_fleet','fk_fleet_tool_fleet')->references('id')->on('fleet')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::table('fleet_tool', function (Blueprint $table) {
            //
        });
    }
}
