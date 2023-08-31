<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tg_groups', function (Blueprint $table) {
            $table->id();

            $table->text('group_id');
            $table->text('title');
            $table->unsignedBigInteger('user_id');
            $table->bigInteger('tg_user_id');
            $table->unsignedBigInteger('tg_bot_id');
            $table->boolean('tg_bot_on');
            $table->boolean('is_channel');

            $table->foreign('user_id')->on('moonshine_users')->references('id');
            $table->foreign('tg_bot_id')->on('tg_bots')->references('id');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tg_groups');
    }
};
