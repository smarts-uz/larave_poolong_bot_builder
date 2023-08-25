<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('post_users', function (Blueprint $table) {
            $table->id();
            $table->softDeletes();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('post_id');
            $table->unsignedBigInteger('button_id');

            $table->index('user_id');
            $table->index('post_id');
            $table->index('button_id');

            $table->foreign('user_id')->on('telegram_users')->references('id');
            $table->foreign('post_id')->on('posts')->references('id');
            $table->foreign('button_id')->on('bot_buttons')->references('id');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_users');
    }
};
