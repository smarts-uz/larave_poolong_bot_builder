<?php

namespace App\Providers;

use App\MoonShine\Resources\ButtonResource;
use App\MoonShine\Resources\CompanyResource;
use App\MoonShine\Resources\MediaResource;
use App\MoonShine\Resources\PostResource;
use App\MoonShine\Resources\PostUserResource;
use App\MoonShine\Resources\TelegramUserResource;
use Illuminate\Support\ServiceProvider;
use MoonShine\MoonShine;
use MoonShine\Menu\MenuGroup;
use MoonShine\Menu\MenuItem;
use MoonShine\Resources\MoonShineUserResource;
use MoonShine\Resources\MoonShineUserRoleResource;

class MoonShineServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        app(MoonShine::class)->menu([
            MenuGroup::make(trans('moonshine::ui.custom.posts'),[

                MenuItem::make(trans('moonshine::ui.custom.create_post'),new PostResource())
                    ->icon('heroicons.clipboard-document-list'),
                MenuItem::make(trans('moonshine::ui.custom.buttons'),new ButtonResource())
                    ->icon('heroicons.document-duplicate'),
                MenuItem::make(trans('moonshine::ui.custom.media'),new MediaResource())
                    ->icon('heroicons.paper-clip'),

                ])->icon('heroicons.chat-bubble-bottom-center-text'),

            MenuGroup::make( trans('moonshine::ui.custom.users'),[

                MenuItem::make(trans('moonshine::ui.custom.users'),new TelegramUserResource())
                    ->icon('heroicons.user'),

                ])->icon('heroicons.user-group'),
            MenuGroup::make(trans('moonshine::ui.custom.report'),[

                MenuItem::make('Post-Users',new PostUserResource())
                    ->icon('heroicons.cursor-arrow-ripple'),
            ])->icon('heroicons.flag'),

        ]);
    }
}
