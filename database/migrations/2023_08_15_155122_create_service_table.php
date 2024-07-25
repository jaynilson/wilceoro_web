<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service', function (Blueprint $table) {
            $table->Increments('id');
            $table->longText('description')->nullable();
            $table->string('type',50)->nullable();

            $table->unsignedInteger('id_fleet')->nullable();
            $table->foreign('id_fleet','fk_service_fleet')->references('id')->on('fleet')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedInteger('id_employee');
            $table->foreign('id_employee','fk_service_employee')->references('id')->on('user')->onDelete('cascade')->onUpdate('cascade');
            $table->double('cost',9,2);
            
            $table->date('next_service_date')->nullable();
            $table->date('next_service_miles')->nullable();
            $table->longText('notes')->nullable();
            $table->integer('miles')->nullable();
            
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
        Schema::dropIfExists('service');
    }
}
