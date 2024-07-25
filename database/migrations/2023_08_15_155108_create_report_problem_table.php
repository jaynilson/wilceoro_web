<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportProblemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_problem', function (Blueprint $table) {
            $table->Increments('id');
            $table->string('type',250)->nullable();
            $table->string('status',250)->nullable();
            $table->string('lat',250)->nullable();
            $table->string('lng',250)->nullable();
            $table->string('place',250)->nullable();
           
            $table->longText('description');
            $table->string('audio',250)->nullable();
            
            $table->unsignedInteger('id_fleet')->nullable();
            $table->foreign('id_fleet','fk_report_problem_fleet')->references('id')->on('fleet')->onDelete('cascade')->onUpdate('cascade');

            


            $table->unsignedInteger('id_tool')->nullable();
            $table->foreign('id_tool','fk_report_problem_tool')->references('id')->on('tool')->onDelete('cascade')->onUpdate('cascade');


            $table->unsignedInteger('id_employee');
            $table->foreign('id_employee','fk_report_problem_employee')->references('id')->on('user')->onDelete('cascade')->onUpdate('cascade');


            $table->unsignedInteger('id_request_category');
            $table->foreign('id_request_category','fk_report_problem_request_category')->references('id')->on('request_category')->onDelete('cascade')->onUpdate('cascade');

            
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
        Schema::dropIfExists('report_problem');
    }
}
