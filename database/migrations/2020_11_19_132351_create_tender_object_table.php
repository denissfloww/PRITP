<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTenderObjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tender_object', function (Blueprint $table) {
            $table->integer('tender_id');
            $table->foreign('tender_id')->references('id')->on('tenders');
            $table->integer('object_id');
            $table->foreign('object_id')->references('id')->on('objects');
            $table->integer('quantity');

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
        Schema::dropIfExists('tender_object');
    }
}
