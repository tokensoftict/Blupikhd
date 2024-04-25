<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPaymentGatewayMethodToTopupTransaction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('topup_transaction', function (Blueprint $table) {
            $table->string('payment_gateway')->nullable()->after('status')->default('STRIPE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('topup_transaction', function (Blueprint $table) {
            $table->dropColumn('payment_gateway');
        });
    }
}
