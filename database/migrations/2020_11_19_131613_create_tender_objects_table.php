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
            $table->text('name');
            $table->text('description')->nullable();
            $table->foreignId('tender_id')->constrained('tenders');
            $table->string('okvad2_classifier', 20)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tender_objects');
    }
}
