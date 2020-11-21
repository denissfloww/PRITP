<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTenderObjectsTable extends Migration
{
    public function up()
    {
        Schema::create('tender_objects', function (Blueprint $table) {
            $table->id('id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tender_objects');
    }
}
