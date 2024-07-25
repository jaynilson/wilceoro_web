<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReminderNotifyUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reminder_notify_user', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('id_reminder')->nullable();
            $table->unsignedInteger('id_role')->nullable();
            $table->unsignedInteger('id_user')->nullable();
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
        Schema::dropIfExists('reminder_notify_user');
    }
}
