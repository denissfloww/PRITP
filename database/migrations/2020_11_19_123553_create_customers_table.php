<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id('id');
            $table->string('name');
            $table->integer('inn')->nullable()->unique();
            $table->integer('ogrn')->nullable()->unique();
            $table->integer('kpp')->nullable();
            $table->string('region')->nullable();
            $table->integer('region_id')->nullable();
            $table->string('place')->nullable();
            $table->integer('place_id')->nullable();
            $table->string('cp_name')->nullable();
            $table->string('cp_email')->nullable();
            $table->string('cp_phone',)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
