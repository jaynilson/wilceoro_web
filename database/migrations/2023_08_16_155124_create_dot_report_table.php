<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDotReportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dot_report', function (Blueprint $table) {
            $table->Increments('id');

            $table->unsignedInteger('id_check')->nullable();
            $table->foreign('id_check','fk_dot_report_check')->references('id')->on('check')->onDelete('cascade')->onUpdate('cascade');

            $table->string('status',250)->nullable();
            $table->string('is_critical',250)->nullable();
            $table->string('type',250)->nullable();
            $table->string('id_reference',250)->nullable();
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
        Schema::dropIfExists('dot_report');
    }
}
