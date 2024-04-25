<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->string('firstname');
            $table->string('lastname');
            $table->string('username');
            $table->string('phoneno')->nullable();
            $table->string('password');
            $table->enum('role',['ADMIN','SUBSCRIBER']);
            $table->string('email')->unique();
            $table->string('device_key')->nullable();
            $table->string('device_push_token')->nullable();
            $table->unsignedBigInteger('country_id')->nullable();
            $table->unsignedBigInteger('state_id')->nullable();
            $table->decimal('wallet')->default(0);
            $table->bigInteger('subscription_expired_timestamp')->nullable();
            $table->bigInteger('subscription_begin_timestamp')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
