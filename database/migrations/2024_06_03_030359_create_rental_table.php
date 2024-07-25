<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRentalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rental', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('id_tool')->nullable();
            $table->foreign('id_tool','fk_rental_tool')->references('id')->on('tool')->onDelete('cascade')->onUpdate('cascade');
            $table->date('rental_date')->nullable();
            $table->date('return_date')->nullable();
            $table->date('needed_date')->nullable();
            $table->string('return_picture',250)->nullable();
            $table->integer('notify')->nullable();
            $table->string('vendor_name',250)->nullable();
            $table->unsignedInteger('id_employee')->nullable();
            $table->string('status')->nullable();
            $table->string('note',250)->nullable();
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
        Schema::dropIfExists('rental');
    }
}
