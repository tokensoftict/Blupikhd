<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePushMessageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('push_message', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text("title")->nullable();
            $table->text("body")->nullable();
            $table->text("payload")->nullable();
            $table->text("device_ids")->nullable();
            $table->bigInteger("no_of_device")->nullable();
            $table->enum("type",array('topic','group','device'));
            $table->bigInteger("total_view")->nullable();
            $table->bigInteger("total_sent")->nullable();
            $table->enum("status",['DRAFT','SENT'])->default("DRAFT");
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
        Schema::dropIfExists('push_message');
    }
}
