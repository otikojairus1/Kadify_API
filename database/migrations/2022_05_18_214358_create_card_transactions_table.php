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
        Schema::create('card_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('referenceNo');
            $table->string('amountPaid');
            $table->string('sender_card');
            $table->string('receiver_card_name');
            $table->string('sender_card_name');
            $table->string('receiver_card');
            $table->string('bank_issuerer');
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
        Schema::dropIfExists('card_transactions');
    }
};
