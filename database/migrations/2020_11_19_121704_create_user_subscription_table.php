<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserSubscriptionTable extends Migration
{
    public function up()
    {
        Schema::create('user_subscription', function (Blueprint $table) {
            $table->foreignId("user_id")->constrained('users');
            $table->foreignId('subscription_id')->constrained('subscriptions');
            $table->integer('price');
            $table->dateTime('sub_date');
            $table->integer('sub_duration');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_subscription');
    }
}
