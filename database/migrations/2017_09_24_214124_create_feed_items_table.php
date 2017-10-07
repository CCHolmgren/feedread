<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeedItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feed_items', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->unsignedInteger('feed_id');
            $table->string('title')->nullable(true);
            $table->text('link')->nullable(true);
            $table->text('description')->nullable(true);
            $table->text('author')->nullable(true);
            $table->text('category')->nullable(true);
            $table->text('comments')->nullable(true);
            $table->text('enclosure')->nullable(true);
            $table->text('guid')->nullable(true);
            $table->text('pub_date')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('feed_items');
    }
}
