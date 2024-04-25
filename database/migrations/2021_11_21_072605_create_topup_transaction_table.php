<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTopupTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('topup_transaction', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger("user_id");
            $table->decimal("amount");
            $table->enum("status",['PENDING','COMPLETE',"FAILED"]);
            $table->string("transaction_token")->unique();
            $table->date("transaction_date");
            $table->string("firstname");
            $table->string("lastname");
            $table->string("country");
            $table->string("state");
            $table->string("phoneno");
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('topup_transaction');
    }
}
