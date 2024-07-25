<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReminderIssueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reminder_issue', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('id_reminder')->nullable();
            $table->string('type')->nullable()->default('report_problem');
            $table->string('issue_serials')->nullable();
            $table->unsignedInteger('id_report')->nullable();
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
        Schema::dropIfExists('reminder_issue');
    }
}
