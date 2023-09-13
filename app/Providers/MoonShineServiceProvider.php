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
use App\MoonShine\Resources\PostResource;
use App\MoonShine\Resources\PostUserResource;
use App\MoonShine\Resources\TelegramUserResource;
use App\MoonShine\Resources\TgBotResource;
use App\MoonShine\Resources\TgBotTextResource;
use App\MoonShine\Resources\TgGroupResource;
use Illuminate\Support\ServiceProvider;
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
        app(MoonShine::class)->menu([
            MenuDivider::make('Pooling Bot'),
            MenuGroup::make(trans('moonshine::ui.custom.posts'), [

                MenuItem::make(trans('moonshine::ui.custom.create_post'), new PostResource())
                    ->icon('heroicons.clipboard-document-list'),
                MenuItem::make(trans('moonshine::ui.custom.buttons'), new ButtonResource())
                    ->icon('heroicons.document-duplicate'),
                MenuItem::make(trans('moonshine::ui.custom.media'), new MediaResource())
                    ->icon('heroicons.paper-clip'),

            ])->icon('heroicons.chat-bubble-bottom-center-text'),


            MenuItem::make(trans('moonshine::ui.custom.users'), new TelegramUserResource())
                ->icon('heroicons.user-group'),

            MenuGroup::make(trans('moonshine::ui.custom.report'), [

                MenuItem::make('Post-Users', new PostUserResource())
                    ->icon('heroicons.cursor-arrow-ripple'),
            ])->icon('heroicons.flag'),

            MenuGroup::make('Bot Settings', [

                MenuItem::make('Telegram Bot', new TgBotResource())
                    ->icon('heroicons.plus-circle'),
                MenuItem::make('Bot Chats', new TgGroupResource())
                    ->icon('heroicons.chat-bubble-bottom-center-text'),
                MenuItem::make('Bot Text', new TgBotTextResource())
                    ->icon('heroicons.document-text'),

            ])->icon('heroicons.wrench-screwdriver'),

            MenuDivider::make('FeedBack Bot'),

//            MenuItem::make('Bulk Messenger',new FbBulkMessengerTextsResource())
//                ->icon('heroicons.chat-bubble-left-right'),
//            MenuItem::make(
//                'Bulk Messenger',
//                CustomPage::make('Bulk Messenger','bilkmessanger','pages.bulk',
//                static function(){
//                    $fbBot = TelegramBot::query()->where('user_id', auth()->user()->id)->get();
//                    return [$fbBot];
//                })
//            ),
        MenuItem::make('Bulk Messanger', new BulkMessangerPage())->icon('heroicons.chat-bubble-left-right'),

            MenuGroup::make(trans('moonshine::ui.custom.report'), [

                MenuItem::make('Bot Messages', new FeedbackReportResource())
                    ->icon('heroicons.envelope'),
            ])->icon('heroicons.flag'),


            MenuItem::make(trans('moonshine::ui.custom.users'), new FeedbackUserResource())
                ->icon('heroicons.user-group'),


            MenuGroup::make('Bot Settings', [
                MenuItem::make('Telegram Bot', new FeedbackBotResource())
                    ->icon('heroicons.plus-circle'),
                MenuItem::make('Bot Chats', new FeedbackGroupResource())
                    ->icon('heroicons.chat-bubble-bottom-center-text'),
                MenuItem::make('Bot Text', new FeedbackTextResource())
                    ->icon('heroicons.document-text'),
            ])->icon('heroicons.wrench-screwdriver'),

        ]);
    }
}
