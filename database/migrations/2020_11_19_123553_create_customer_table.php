<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer', function (Blueprint $table) {
            $table->id('id')->autoIncrement();
            $table->string('name')->nullable();
            $table->integer('inn')->nullable();
            $table->integer('kpp')->nullable();
            $table->string('organization')->nullable();
            $table->string('region')->nullable();
            $table->integer('region_id')->nullable();
            $table->string('place')->nullable();
            $table->integer('place-id')->nullable();
            $table->string('cp_name')->nullable();
            $table->string('email')->nullable();
            $table->integer('phone',)->nullable();
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
        Schema::dropIfExists('customer');
    }
}
