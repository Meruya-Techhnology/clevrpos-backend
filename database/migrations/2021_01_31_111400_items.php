<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Items extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestampTz('created_at', 0)->useCurrent();
            $table->bigInteger('created_by');
            $table->timestampTz('updated_at', 0);
            $table->bigInteger('updated_by');
            $table->bigInteger('item_id');
            $table->string('variant', 300);
            $table->bigInteger('price');
            $table->string('sku', 50);
            $table->longText('image_url');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('items');
    }
}
