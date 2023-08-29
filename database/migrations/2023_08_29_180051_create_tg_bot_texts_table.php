<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tg_bot_texts', function (Blueprint $table) {
            $table->id();
            $table->softDeletes();
            $table->string('first_action_msg');
            $table->string('repeated_action_msg');
            $table->string('follow_msg');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tg_bot_texts');
    }
};
