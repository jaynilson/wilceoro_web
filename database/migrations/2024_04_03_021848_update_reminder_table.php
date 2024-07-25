<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateReminderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reminder', function (Blueprint $table) {
            $table->dropColumn('nitify');
            $table->string('type')->default('service')->after('id_fleet');
            $table->unsignedInteger('id_service')->nullable();
            $table->unsignedInteger('id_interface')->nullable();
            $table->unsignedInteger('complete_value')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reminder', function (Blueprint $table) {
            //
        });
    }
}
