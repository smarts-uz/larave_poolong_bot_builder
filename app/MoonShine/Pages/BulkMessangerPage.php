<?php

namespace App\MoonShine\Pages;

use Modules\FeedbackBot\Entities\TelegramBot;
use MoonShine\Resources\CustomPage;

class BulkMessangerPage extends CustomPage
{
	public string $title = 'BulkMessangerPages';

	public string $alias = 'bulk-messanger-page';

	public function __construct()
	{
		parent::__construct(
			$this->title(),
			$this->alias(),
			$this->view()
		);
	}

	public function view(): string
	{
		return 'pages.bulk';
	}

	public function datas(): array
	{
        $fbBot = TelegramBot::query()->where('user_id', auth()->user()->id)->get();
		return [
            'fbBot' => $fbBot
        ];
	}
}
