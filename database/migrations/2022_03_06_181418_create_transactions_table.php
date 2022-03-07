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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');

            $table->unsignedBigInteger('amount');
            $table->string('description',30);

            $table->string('destinationFirstname',30);
            $table->string('destinationLastname',30);
            // if shaba number is used, it will be 26 chars or cart number will be 16 chars
            $table->string('destinationNumber',26);

            $table->string('deposit',26)->nullable();

            $table->string('sourceFirstName',30);
            $table->string('sourceLastName',30);

            $table->integer('reasonDescription')->nullable();

            $table->string('message')->nullable();
            $table->string('status');
            $table->string('trackId');

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
        Schema::dropIfExists('transactions');
    }
};
