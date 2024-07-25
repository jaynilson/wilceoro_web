<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user', function (Blueprint $table) {
            $table->string('department',250)->defalut('fleet')->nullable();
            $table->string('yard_location',250)->nullable();
            $table->string('phone',250)->nullable();
            $table->date('phone_verified_at',250)->nullable();
            $table->string('cdl_path',250)->nullable();
            $table->date('cdl_verified_at',250)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user', function (Blueprint $table) {
            //
        });
    }
}
