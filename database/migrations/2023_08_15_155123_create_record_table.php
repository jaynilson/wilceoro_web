<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('record', function (Blueprint $table) {
            $table->Increments('id');
            $table->longText('description')->nullable();
 

            $table->unsignedInteger('id_service')->nullable();
            $table->foreign('id_service','fk_record_service')->references('id')->on('service')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedInteger('id_mechanic');
            $table->foreign('id_mechanic','fk_record_employee')->references('id')->on('user')->onDelete('cascade')->onUpdate('cascade');

            $table->double('hour_spend',9,2);

            $table->double('cost',9,2);



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
        Schema::dropIfExists('record');
    }
}
