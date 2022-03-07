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
        Schema::create('transaction_details', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('transaction_id');
            $table->foreign('transaction_id')->references('id')->on('transactions');

            //account number
            $table->string('destinationNumber',26);

            $table->string('sourceNumber',26);

            $table->string('type');

            $table->string('inquiryDate')->nullable();
            $table->string('inquiryTime')->nullable();

            $table->string('refCode');
            $table->string('paymentNumber');

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
        Schema::dropIfExists('trancaction_successes');
    }
};
