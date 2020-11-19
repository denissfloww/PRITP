<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionCapabilityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscription_capability', function (Blueprint $table) {
            $table->integer('subscription_id');
            $table->foreign('subscription_id')->references('id')->on('subscriptions');
            $table->integer('capability_id');
            $table->foreign('capability_id')->references('id')->on('capabilities');
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
        Schema::dropIfExists('subscription_capability');
    }
}
