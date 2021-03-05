<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElectionPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('election_posts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('election_id')->unsigned();
            $table->foreign('election_id')->references('id')->on('elections')->onDelete('cascade');
            $table->string('name');
            $table->bigInteger('winner_id')->unsigned();
            $table->foreign('winner_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('election_posts');
    }
}
