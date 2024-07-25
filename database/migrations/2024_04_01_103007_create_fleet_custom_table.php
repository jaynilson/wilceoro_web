<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFleetCustomTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fleet_custom', function (Blueprint $table) {
            $table->Increments('id');
            $table->unsignedInteger('id_fleet')->nullable();
            $table->unsignedInteger('id_field')->nullable();
            $table->integer('value_integer')->nullable();
            $table->string('value_string')->nullable();
            $table->double('value_double')->nullable();
            $table->boolean('value_boolean')->nullable();
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
        Schema::table('fleet_custom', function (Blueprint $table) {
            //
        });
    }
}
