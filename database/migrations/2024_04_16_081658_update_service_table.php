<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateServiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('service', function (Blueprint $table) {
            $table->date('completed_date')->nullable()->after('next_service_date');
            $table->date('needed_date')->nullable()->after('next_service_date');
            $table->string('status')->nullable()->default('Unassigned')->after('miles');
            $table->string('working')->nullable()->after('miles');
            $table->integer('engine_hours')->nullable()->after('miles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('service', function (Blueprint $table) {
            //
        });
    }
}
