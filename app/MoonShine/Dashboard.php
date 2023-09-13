<?php

namespace App\MoonShine;


use App\MoonShine\Resources\PostResource;
use Modules\FeedbackBot\Entities\FeedbackUserChat;
use Modules\FeedbackBot\Entities\TelegramBot;
use Modules\PoolingBot\Entities\BotButton;
use Modules\PoolingBot\Entities\Media;
use Modules\PoolingBot\Entities\Post;
use Modules\PoolingBot\Entities\TelegramUser;
use Modules\PoolingBot\Entities\TgBot;
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

        $fbBots = TelegramBot::query()->where('user_id', auth()->user()->id)->get();
        $fbBotIds = $fbBots->pluck('id');

		return [
            DashboardBlock::make([

                ValueMetric::make('Posts')
                    ->value($posts->count())
                    ->columnSpan(4),

                ValueMetric::make('Buttons')
                    ->value(BotButton::query()
                        ->whereIn('post_id',$postIds)
                        ->count())
                    ->columnSpan(4),

                ValueMetric::make('Medias')
                    ->value(Media::query()
                        ->whereIn('post_id',$postIds)
                        ->count())
                    ->columnSpan(4),
            ]),

            DashboardBlock::make([

                DonutChartMetric::make('Bots')
                    ->values(['Pooling Bots' => TgBot::query()
                        ->where('user_id',auth()->user()->id)
                        ->count(),
                        'Feedback Bots' => TelegramBot::query()
                            ->where('user_id', auth()->user()->id)
                            ->count()])
                    ->columnSpan(6),

                DonutChartMetric::make('Subscribers')
                ->values(['Pooling Bot Users' => TelegramUser::query()
                    ->whereIn('bot_id',$botIds)
                    ->count(),
                    'Feedback Bot Users' => FeedbackUserChat::query()
                        ->whereIn('botid',$fbBotIds)
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
