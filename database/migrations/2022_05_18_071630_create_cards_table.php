<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cards', function (Blueprint $table) {
            $table->id();
           
            $table->string('description')->nullable();
            $table->string('expiry');
            $table->string('name');
            $table->string('balance');
            $table->string('transactionsno');
            $table->string('banktype');
            $table->string('email');
            $table->integer('security_code');
            $table->integer('card_no');
            $table->boolean('contactless_payment');
            $table->boolean('merchant_lock');
            $table->boolean('friends_withdrawal');
            $table->boolean('geo_lock');
            $table->boolean('deactivate_card');
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
        Schema::dropIfExists('cards');
    }
};
