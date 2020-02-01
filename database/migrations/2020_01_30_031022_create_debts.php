<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDebts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('debts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('client_id');
            $table->string('description', 200);
            $table->timestamp('startDate');
            $table->timestamp('datePayment');
            $table->double('initialAmount');
            $table->double('paymentAmount');
            $table->boolean('isActive');
            $table->timestamps();
            $table->foreign('client_id')->references('id')->on('clients');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('debts');
    }
}
