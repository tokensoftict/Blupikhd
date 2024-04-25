<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMovieRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movie_request', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->date('request_date');
            $table->enum("request_type",['SERIES','MOVIE']);
            $table->unsignedBigInteger('user_id');
            $table->enum('status',['PENDING','APPROVED','READY','UNAPPROVED'])->nullable();
            $table->date("date_approved")->nullable();
            $table->date("ready_date")->nullable();
            $table->text("movie_description")->nullable();
            $table->string("movie_file")->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('approved_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('movie_request');
    }
}
