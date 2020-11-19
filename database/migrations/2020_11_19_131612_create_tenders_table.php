<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTendersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tenders', function (Blueprint $table) {
            $table->id('id')->autoIncrement();
            $table->string('name');
            $table->text('description');
            $table->dateTime('start_request_date');
            $table->dateTime('end_request_date');
            $table->string('source_url');
            $table->dateTime('result_date');
            $table->integer('nmc_price');
            $table->integer('ensure_request_price');
            $table->integer('ensure_contract_price');
            $table->integer('customer_id');
            $table->foreign('customer_id')->references('id')->on('customer');
            $table->integer('type_id');
            $table->foreign('type_id')->references('id')->on('tender_types');
            $table->integer('currency_id');
            $table->foreign('currency_id')->references('id')->on('currency');
            $table->integer('stage_id');
            $table->foreign('stage_id')->references('id')->on('tender_stages');
            $table->integer('classifiers_id');
            $table->foreign('classifiers_id')->references('id')->on('classifiers');
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
        Schema::dropIfExists('tenders');
    }
}
