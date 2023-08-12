<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Outlet extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('outlet', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestampTz('created_at', 0)->useCurrent();
            $table->bigInteger('created_by');
            $table->timestampTz('updated_at', 0);
            $table->bigInteger('updated_by');
            $table->string('name', 300);
            $table->string('code', 10);
            $table->string('address', 1000);
            $table->double('latitude', 13, 11);
            $table->double('longitude', 13, 11);
            $table->bigInteger('business_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('outlet');
    }
}
