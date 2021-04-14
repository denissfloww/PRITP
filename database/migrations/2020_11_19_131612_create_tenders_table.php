<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTendersTable extends Migration
{
    public function up()
    {
        Schema::create('tenders', function (Blueprint $table) {
            $table->id('id');
            $table->integer('number')->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('source_url');
            $table->dateTime('start_request_date')->nullable();
            $table->dateTime('end_request_date')->nullable();
            $table->dateTime('result_date')->nullable();
            $table->float('nmc_price')->nullable();
            $table->float('ensure_request_price')->nullable();
            $table->float('ensure_contract_price')->nullable();
            $table->foreignId('customer_id')->constrained('customers');
            $table->foreignId('type_id')->constrained('tender_types');
            $table->foreignId('currency_id')->constrained('currencies');
            $table->foreignId('stage_id')->constrained('tender_stages');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tenders');
    }
}
