<?php

namespace App\Providers;

use App\MoonShine\Pages\BulkMessangerPage;
use App\MoonShine\Resources\ButtonResource;
use App\MoonShine\Resources\CompanyResource;
use App\MoonShine\Resources\FeedbackBotResource;
use App\MoonShine\Resources\FbBulkMessengerTextsResource;
use App\MoonShine\Resources\FeedbackGroupResource;
use App\MoonShine\Resources\FeedbackReportResource;
use App\MoonShine\Resources\FeedbackTextResource;
use App\MoonShine\Resources\FeedbackUserResource;
use App\MoonShine\Resources\MediaResource;
use App\MoonShine\Resources\MoonshineTranslateResource;
use App\MoonShine\Resources\PostResource;
use App\MoonShine\Resources\PostUserResource;
use App\MoonShine\Resources\TelegramUserResource;
use App\MoonShine\Resources\TgBotResource;
use App\MoonShine\Resources\TgBotTextResource;
use App\MoonShine\Resources\TgGroupResource;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Modules\FeedbackBot\Entities\Newslatter;
use Modules\FeedbackBot\Entities\TelegramBot;
use MoonShine\Menu\MenuDivider;
use MoonShine\MoonShine;
use MoonShine\Menu\MenuGroup;
use MoonShine\Menu\MenuItem;
use MoonShine\Resources\CustomPage;
use MoonShine\Resources\MoonShineUserResource;
use MoonShine\Resources\MoonShineUserRoleResource;

class MoonShineServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $botText = Newslatter::query()->where('id',1)->first();

        app(MoonShine::class)->menu([
            MenuDivider::make('Pooling Bot'),
            MenuGroup::make('moonshine::ui.custom.posts', [

                MenuItem::make('moonshine::ui.custom.create_post', new PostResource())
                    ->icon('heroicons.clipboard-document-list')->translatable(),
                MenuItem::make('moonshine::ui.custom.buttons', new ButtonResource())
                    ->icon('heroicons.document-duplicate')->translatable(),
                MenuItem::make('moonshine::ui.custom.media', new MediaResource())
                    ->icon('heroicons.paper-clip')->translatable(),

            ])->icon('heroicons.chat-bubble-bottom-center-text')->translatable(),


            MenuItem::make('moonshine::ui.custom.users', new TelegramUserResource())
                ->icon('heroicons.user-group')->translatable(),

            MenuGroup::make('moonshine::ui.custom.report', [

                MenuItem::make('Post-Users', new PostUserResource())
                    ->icon('heroicons.cursor-arrow-ripple')->translatable(),
            ])->icon('heroicons.flag')->translatable(),

            MenuGroup::make('moonshine::ui.custom.bot_settings', [

                MenuItem::make('moonshine::ui.custom.telegram_bot', new TgBotResource())
                    ->icon('heroicons.plus-circle')->translatable(),
                MenuItem::make('moonshine::ui.custom.bot_chats', new TgGroupResource())
                    ->icon('heroicons.chat-bubble-bottom-center-text')->translatable(),
                MenuItem::make('moonshine::ui.custom.bot_text', new TgBotTextResource())
                    ->icon('heroicons.document-text')->translatable(),

            ])->icon('heroicons.wrench-screwdriver')->translatable(),

            MenuDivider::make('FeedBack Bot'),


        MenuItem::make('moonshine::ui.custom.bulk_messanger', new BulkMessangerPage())->icon('heroicons.chat-bubble-left-right')->translatable(),


            MenuItem::make('moonshine::ui.custom.users', new FeedbackUserResource())
                ->icon('heroicons.user-group')->translatable(),

            MenuGroup::make('moonshine::ui.custom.report', [

                MenuItem::make('moonshine::ui.custom.bot_messages', new FeedbackReportResource())
                    ->icon('heroicons.envelope')->translatable(),
            ])->icon('heroicons.flag')->translatable(),


            MenuGroup::make('moonshine::ui.custom.bot_settings', [
                MenuItem::make('moonshine::ui.custom.telegram_bot', new FeedbackBotResource())
                    ->icon('heroicons.plus-circle')->translatable(),
                MenuItem::make('moonshine::ui.custom.bot_chats', new FeedbackGroupResource())
                    ->icon('heroicons.chat-bubble-bottom-center-text')->translatable(),
                MenuItem::make('moonshine::ui.custom.bot_text', new FeedbackTextResource())
                    ->icon('heroicons.document-text')->translatable(),
            ])->icon('heroicons.wrench-screwdriver')->translatable(),


            MenuDivider::make('Translates')->canSee(function (Request $request) {
                return $request->user()?->role_id === 1;
            }),
            MenuGroup::make('Translates',[
                MenuItem::make('Bulk Messenger',new FbBulkMessengerTextsResource())
                    ->icon('heroicons.chat-bubble-left-right'),
                MenuItem::make('Admin Panel Translates',new MoonshineTranslateResource())
                    ->icon('heroicons.chat-bubble-left-right'),
            ])->icon('heroicons.adjustments-horizontal')->canSee(function (Request $request) {
                return $request->user()?->role_id === 1;
            })

        ]);
    }
}
