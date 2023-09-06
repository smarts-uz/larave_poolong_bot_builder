<?php

namespace App\MoonShine;

use App\Models\BotButton;
use App\Models\Media;
use App\Models\Post;
use App\Models\TelegramUser;
use App\Models\TgBot;
use App\MoonShine\Resources\PostResource;
use MoonShine\Dashboard\DashboardBlock;
use MoonShine\Dashboard\DashboardScreen;
use MoonShine\Dashboard\ResourcePreview;
use MoonShine\Metrics\DonutChartMetric;
use MoonShine\Metrics\ValueMetric;

class Dashboard extends DashboardScreen
{
	public function blocks(): array
	{
        $posts = Post::query()->where('user_id', auth()->user()->id)->get();
        $postIds = $posts->pluck('id');

        $bots = TgBot::query()->where('user_id', auth()->user()->id)->get();
        $botIds = $bots->pluck('id');

		return [
            DashboardBlock::make([

                ValueMetric::make('Posts')
                    ->value($posts->count())
                    ->columnSpan(4),

                ValueMetric::make('Buttons')
                    ->value(BotButton::query()
                        ->where('post_id',$postIds)
                        ->count())
                    ->columnSpan(4),

                ValueMetric::make('Medias')
                    ->value(Media::query()
                        ->where('post_id',$postIds)
                        ->count())
                    ->columnSpan(4),
            ]),

            DashboardBlock::make([

                DonutChartMetric::make('Bots')
                    ->values(['Bots' => TgBot::query()
                        ->where('user_id',auth()->user()->id)
                        ->count()])
                    ->columnSpan(6),

                DonutChartMetric::make('Subscribers')
                ->values(['Telegram Users' => TelegramUser::query()
                    ->where('bot_id',$botIds)
                    ->count()])
                    ->columnSpan(6),
            ]),
            DashboardBlock::make([
                ResourcePreview::make(
                new PostResource(),
                'Latest Posts',
                Post::query()
                    ->where('user_id',auth()->user()->id)
                    ->limit(5)
                )
            ])
        ];
	}
}
