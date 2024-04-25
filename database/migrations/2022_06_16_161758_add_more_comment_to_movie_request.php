<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMoreCommentToMovieRequest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('movie_request', function (Blueprint $table) {
            $table->text('comment')->nullable()->after('title');
            $table->string('image')->nullable()->after('comment');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('movie_request', function (Blueprint $table) {
            $table->dropColumn('comment');
            $table->dropColumn('image');
        });
    }
}
