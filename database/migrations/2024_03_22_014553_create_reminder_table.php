<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReminderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reminder', function (Blueprint $table) {
            $table->Increments('id');
            $table->unsignedInteger('id_fleet')->nullable();
            $table->string('task',250)->nullable();
            $table->integer('time_interval')->nullable();
            $table->integer('time_interval_unit')->nullable();//0: days, 1: weeks, 2: months, 3: years
            $table->integer('time_interval_duesoon')->nullable();
            $table->integer('time_interval_duesoon_unit')->nullable();//0: days, 1: weeks, 2: months, 3: years
            $table->integer('meter_interval')->nullable();
            $table->integer('meter_interval_duesoon')->nullable();
            $table->integer('manual')->default(0);
            $table->date('manual_date')->nullable();
            $table->integer('nitify')->default(1);
            $table->unsignedInteger('id_employee')->nullable();
            $table->string('description',250)->nullable();
            $table->string('status',250)->default('enable');
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
        Schema::table('reminder', function (Blueprint $table) {
            //
        });
    }
}
