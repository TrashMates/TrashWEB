<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommunityStreamTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('community_stream', function (Blueprint $table) {
            $table->string('community_id');
            $table->string('stream_id');

            $table->primary(['community_id', 'stream_id']);
            $table->foreign('community_id')->references('id')->on('communities')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('stream_id')->references('id')->on('streams')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('community_stream');
    }
}
