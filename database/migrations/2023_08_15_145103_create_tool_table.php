<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateToolTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tool', function (Blueprint $table) {
            $table->Increments('id');
            $table->string('n',250)->unique();
            $table->string('title',250)->unique();
            $table->integer('stock')->nullable();
            $table->string('status',250)->nullable();
            $table->string('picture',250)->nullable();
            $table->string('type',250)->nullable();
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
        Schema::dropIfExists('tool');
    }
}
