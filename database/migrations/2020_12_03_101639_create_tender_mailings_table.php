<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTenderMailingsTable extends Migration
{
    public function up()
    {
        Schema::create('tender_mailings', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('user_id')->constrained('users');
            $table->string('okvad2_classifier', 20);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tender_mailings');
    }
}
