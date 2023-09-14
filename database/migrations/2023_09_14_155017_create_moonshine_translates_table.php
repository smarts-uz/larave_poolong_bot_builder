<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('moonshine_translates', function (Blueprint $table) {
            $table->id();
            $table->text('bot_toke');
            $table->text('base_url');
            $table->text('bot_username');
            $table->text('bot_chat_title');
            $table->text('group_id');
            $table->text('bot');
            $table->text('group_language');
            $table->text('group_on_off');
            $table->text('first_action_message');
            $table->text('repeated_action_message');
            $table->text('unfollow_users_message');
            $table->text('bot_input_text');
            $table->text('bot_response_text');
            $table->text('user_name');
            $table->text('user_lastname');
            $table->text('language_code');
            $table->text('bot_incoming_messages');
            $table->text('bot_response_messages');
            $table->text('post_title');
            $table->text('tg_post_url_title');
            $table->text('post_content');
            $table->text('media_content');
            $table->text('add_media');
            $table->text('post_buttons');
            $table->text('add_button');
            $table->text('buttons');
            $table->text('action_count');
            $table->text('file');
            $table->text('user_info');
            $table->text('button_title');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('moonshine_translates');
    }
};
