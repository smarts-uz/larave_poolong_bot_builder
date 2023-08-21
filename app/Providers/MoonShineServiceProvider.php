<?php

namespace App\Providers;

use App\MoonShine\Resources\CompanyResource;
use App\MoonShine\Resources\PostResource;
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
            MenuItem::make('Company',new CompanyResource())->icon('heroicons.clipboard-document-list'),
            MenuItem::make('Posts',new PostResource())->icon('heroicons.clipboard-document-list'),
        ]);
    }
}
