<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTenderMailingsTable extends Migration
{
    public function up()
    {
        Schema::create('tender_mailings', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('tender_classifier_id')->constrained('tender_classifiers');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tender_mailings');
    }
}
