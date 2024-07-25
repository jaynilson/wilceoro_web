<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnswerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('answer', function (Blueprint $table) {
            $table->Increments('id');
            $table->string('type',250)->nullable();
            $table->string('question_text',250)->nullable();
       
            $table->integer('position')->nullable();

            $table->longText('content')->nullable();

            $table->unsignedInteger('id_report');

            $table->foreign('id_report','fk_report_answer')->references('id')->on('report')->onDelete('cascade')->onUpdate('cascade');

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
        Schema::dropIfExists('answer');
    }
}
