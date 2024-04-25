<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAwsFileManagerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aws_file_manager', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('bucket_id');
            $table->string('name');
            $table->text('descriptions');
            $table->string('aws_name');
            $table->json('extra');
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
        Schema::dropIfExists('aws_file_manager');
    }
}
