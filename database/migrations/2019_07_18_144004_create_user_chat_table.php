<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserChatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_chat', function (Blueprint $table) {

                $table->integer('user_id')->unsigned()->nullable();
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

                $table->integer('chat_id')->unsigned()->nullable();
                $table->foreign('chat_id')->references('id')->on('chats')->onDelete('cascade');

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
        Schema::dropIfExists('user_chat');
    }
}
