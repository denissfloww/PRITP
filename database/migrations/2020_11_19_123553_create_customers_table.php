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
            $table->text('name');
            $table->string('inn')->nullable()->unique();
            $table->string('ogrn')->nullable()->unique();
            $table->string('kpp')->nullable();
            $table->json('location')->nullable();
            $table->text('cp_name')->nullable();
            $table->string('cp_email')->nullable();
            $table->string('cp_phone', )->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
