<?php

namespace Modules\FeedbackBot\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BotFatherTextModel extends Model
{
    use HasFactory;

    use HasFactory, Translatable;

    protected $table = 'father_bot_text';
    protected $translatable =[
        'main_menu_text',
        'main_menu_bot_set_button',
        'main_menu_bot_control_button',
        'main_menu_bot_help_button',
        'bot_set_text',
        'back_button',
        'bot_set_response_text',
        'bot_set_response_bot_settings_button',
        'bot_contol_text',
        'bot_settings_text',
        'bot_settings_newslatter_button',
        'bot_settings_chats_button',
        'bot_settings_statistics_button',
        'bot_settings_disable_bot_button',
        'bot_chat_settings_text',
        'bot_chat_settings_nested_menu_text',
        'bot_statistics_title',
        'bot_statistics_users',
        'bot_statistics_users_count',
        'bot_statistics_messages',
        'bot_statistics_messages_all_count',
        'bot_statistics_messages_response_count',
        'bot_disable_text',
        'bot_disable_yes_button',
        'bot_disable_no_button',
        'bot_disable_response_text',
        'bot_disable_response_button',
        'bot_help_text',
        'incorrect_bot_token',
        'bot_chats_text',
        'bot_settings_texts_button',
        'bot_texts_menu_text',
        'bot_texts_menu_next_button',
        'bot_texts_menu_default_button',
        'bot_texts_menu_edit_button',
        'bot_texts_menu_hello_message_text',
        'bot_texts_menu_response_message_text',
        'bot_texts_edit_menu_text',
        'bot_texts_set_default_menu_text',
        'main_menu_premium_button',
        'premium_menu_text',
        'premium_menu_status_active',
        'premium_menu_status_unactive',
        'premium_menu_one_month_button',
        'premium_menu_three_month_button',
        'premium_menu_six_month_button',
        'premium_menu_twelve_month_button',
        'premium_menu_month_text',
        'premium_menu_click_button',
        'premium_menu_payme_button',
        'help_menu_text',
        'help_menu_button',
        'one_month_title',
        'three_month_title',
        'six_month_title',
        'twelve_month_title',
        'payment_description',
    ];
}
