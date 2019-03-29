<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStreamMetadataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stream_metadata', function (Blueprint $table) {
            $table->string('stream_id');
            $table->integer('number');
            $table->integer('viewers');
            $table->timestamp('created_at');

            $table->primary(['number', 'stream_id']);
            $table->foreign('stream_id')->references('id')->on('streams');
        });

        DB::unprepared('CREATE TRIGGER `stream_metadata_bi` BEFORE INSERT ON `stream_metadata` FOR EACH ROW 
        SET new.number = (SELECT (IFNULL(MAX(number), 0) +1) FROM stream_metadata WHERE stream_id = new.stream_id)
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER `tr_stream_metadata_number`');
        Schema::dropIfExists('stream_metadata');
    }
}
