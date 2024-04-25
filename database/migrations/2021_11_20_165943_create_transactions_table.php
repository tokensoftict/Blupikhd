<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('recipient_id')->nullable();
            $table->enum("trans_type",["TOP UP","SUBSCRIPTION","FUND TRANSFER"]);
            $table->enum("sign",['+','-']);
            $table->decimal("amount");
            $table->string("currency");
            $table->date("transaction_date");
            $table->string("description")->nullable();
            $table->decimal("wallet_amt_before");
            $table->decimal("wallet_amt_after");
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('recipient_id')->references('id')->on('users');
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
}
