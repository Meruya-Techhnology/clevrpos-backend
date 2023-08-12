<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Business extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestampTz('created_at', 0)->useCurrent();
            $table->bigInteger('created_by');
            $table->timestampTz('updated_at', 0);
            $table->bigInteger('updated_by');
            $table->bigInteger('owner_id');
            $table->string('name', 300);
            $table->string('address', 1000);
            $table->bigInteger('business_type_id');
            $table->double('latitude', 13, 11);
            $table->double('longitude', 13, 11);
            $table->string('tax_name', 300);
            $table->longText('attachment_url');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('business');
    }
}
