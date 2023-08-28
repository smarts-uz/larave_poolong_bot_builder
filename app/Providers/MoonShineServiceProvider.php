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
            MenuGroup::make('Post',[
                MenuItem::make('Posts',new PostResource())->icon('heroicons.clipboard-document-list'),
                MenuItem::make('Buttons',new ButtonResource())->icon('heroicons.document-duplicate'),
                MenuItem::make('Media',new MediaResource())->icon('heroicons.paper-clip'),])->icon('heroicons.chat-bubble-bottom-center-text'),

            MenuGroup::make( 'Users',[
                MenuItem::make('Users',new TelegramUserResource())->icon('heroicons.user'),
                MenuItem::make('Post-Users',new PostUserResource())->icon('heroicons.cursor-arrow-ripple'),])->icon('heroicons.user-group'),

        ]);
    }
}
