<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tg_bots', function (Blueprint $table) {
            $table->id();

            $table->text('bot_token');
            $table->text('bot_username');
            $table->bigInteger('tg_user_id');
            $table->unsignedBigInteger('user_id');

            $table->foreign('user_id')->on('moonshine_users')->references('id');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tg_bots');
    }
};
