<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserSubscriptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_subscription', function (Blueprint $table) {
            $table->integer("user_id");
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('subscription_id');
            $table->foreign('subscription_id')->references('id')->on('subscriptions');
            $table->integer('price');
            $table->dateTime('sub_date');
            $table->integer('sub_duration');
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
        Schema::dropIfExists('user_subscription');
    }
}
