<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->string("app_name")->nullable();
            $table->text("contact_page")->nullable();
            $table->enum('access_mode',['FREE','PAID'])->nullable();
            $table->string('phone_number')->nullable();
            $table->string('app_logo')->nullable();


            $table->string('paypal_seller_email')->nullable();
            $table->enum('paypal_status',['ON','OFF'])->nullable();
            $table->enum('paypal_mode',['PRODUCTION','SANDBOX'])->nullable();
            $table->text('paypal_client_id')->nullable();
            $table->string('paypal_client_secret')->nullable();

            $table->enum('google_play_status',['ON','OFF'])->nullable();

            $table->enum('stripe_status',['ON','OFF'])->nullable();
            $table->string('stripe_api_key')->nullable();
            $table->string('stripe_public_key')->nullable();

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
        Schema::dropIfExists('settings');
    }
}
