<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateFleetTable03 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fleet', function (Blueprint $table) {
            $table->boolean('insurance_expiration_reminder')->default(true);
            $table->boolean('lease_rental_return_reminder')->default(true);
            $table->boolean('registration_reminder')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fleet', function (Blueprint $table) {
            //
        });
    }
}
