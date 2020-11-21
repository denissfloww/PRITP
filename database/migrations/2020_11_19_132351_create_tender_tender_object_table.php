<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTenderTenderObjectTable extends Migration
{
    public function up()
    {
        Schema::create('tender_tender_object', function (Blueprint $table) {
            $table->foreignId('tender_id')->constrained('tenders');;
            $table->foreignId('tender_object_id')->constrained('tender_objects');
            $table->integer('quantity');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tender_tender_object');
    }
}
