<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFleetRecordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fleet_record', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('id_fleet')->nullable();
            $table->unsignedInteger('type')->default(0);
            $table->date('date')->nullable();
            $table->string('note', 255)->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->foreign('id_fleet','fk_fleet_record_fleet')->references('id')->on('fleet')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('fleet_record');
    }
}
