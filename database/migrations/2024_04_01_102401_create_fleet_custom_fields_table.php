<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFleetCustomFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fleet_custom_field', function (Blueprint $table) {
            $table->Increments('id');
            $table->string('name',250)->nullable();
            $table->string('title',250)->nullable();
            $table->string('type')->nullable();
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
        Schema::table('fleet_custom_field', function (Blueprint $table) {
            //
        });
    }
}
