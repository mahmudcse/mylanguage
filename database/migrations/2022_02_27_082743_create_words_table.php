<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('words', function (Blueprint $table) {
            $table->id();

            $table->index('user_id');
            $table->foreignId('user_id')->references('id')->on('users')->constrained()->onDelete('cascade');

            $table->index('article_id');
            $table->foreignId('article_id')->references('id')->on('articles')->constrained()->onDelete('cascade');

            $table->string('word')->nullable();
            $table->string('definition')->nullable();
            $table->integer('no_of_read')->default(0);
            $table->boolean('learned')->default(0);
            $table->boolean('deleted')->default(0);


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
        Schema::dropIfExists('words');
    }
}
