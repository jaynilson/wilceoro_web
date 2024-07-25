<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCheckTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('check', function (Blueprint $table) {
            $table->Increments('id');
            $table->string('title',250)->unique();
            $table->string('picture',250)->nullable();
            $table->string('type',250)->nullable();
            $table->string('critical',250)->nullable();
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
        Schema::dropIfExists('check');
    }
}
