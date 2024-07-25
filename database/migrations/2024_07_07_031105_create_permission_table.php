<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permission', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('id_page')->default(0);
            $table->unsignedInteger('id_rol')->default(0);
            $table->boolean('read')->default(false);
            $table->boolean('write')->default(false);
            $table->boolean('create')->default(false);
            $table->boolean('delete')->default(false);
            $table->boolean('other')->default(false);
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
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
        Schema::dropIfExists('permission');
    }
}
