<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report', function (Blueprint $table) {
            $table->Increments('id');
            $table->string('type',250)->nullable();

            $table->unsignedInteger('id_employee');
            $table->foreign('id_employee','fk_report_employee')->references('id')->on('user')->onDelete('cascade')->onUpdate('cascade');



            $table->unsignedInteger('id_fleet')->nullable();
            $table->foreign('id_fleet','fk_report_fleet')->references('id')->on('fleet')->onDelete('cascade')->onUpdate('cascade');

    

            $table->unsignedInteger('id_tool')->nullable();
            $table->foreign('id_tool','fk_report_tool')->references('id')->on('tool')->onDelete('cascade')->onUpdate('cascade');

            $table->string('report_linked_id',250)->nullable();

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
        Schema::dropIfExists('report');
    }
}
