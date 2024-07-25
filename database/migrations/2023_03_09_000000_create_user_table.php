<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user', function (Blueprint $table) {
            $table->Increments('id');
            $table->string('name',250)->nullable();
            $table->string('pin',250)->nullable();
            $table->string('last_name',250)->nullable();
            $table->string('password',250);
            $table->string('tel',250)->nullable();
            $table->string('email',191)->unique();
            $table->string('user_name',191)->unique()->nullable();
            $table->text('address',1000)->nullable();
            $table->string('sex',250)->nullable();
            $table->string('color',250)->default("#ff0000");
            $table->date('date_of_birth')->nullable();
            $table->string('picture',250)->nullable();
            $table->string('status',250)->default('enable');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->unsignedInteger('id_rol');
            $table->foreign('id_rol','fk_user_rol')->references('id')->on('rol')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('user');
    }
}
