<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlanPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plan_payments', function (Blueprint $table) {
            $table->id();
            $table->integer('subscription_id');
            $table->string('payment_id');
            $table->string('status');
            $table->string('amount');
            $table->string('trans_id');
            $table->string('balance_transaction');
            $table->string('charge_id');
            
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
        Schema::dropIfExists('plan_payments');
    }
}


