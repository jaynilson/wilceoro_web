<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateToolTable01 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tool', function (Blueprint $table) {
            $table->double('price',9,2)->default(0)->nullable()->after('type');
            $table->unsignedInteger('created_by')->nullable()->after('created_at');
            $table->unsignedInteger('updated_by')->nullable()->after('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tool', function (Blueprint $table) {
            //
        });
    }
}
