<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTenderFavoritesTable extends Migration
{
    public function up()
    {
        Schema::create('tender_favorites', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('tender_id')->constrained('tenders');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tender_favorites');
    }
}
