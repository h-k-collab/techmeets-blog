<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $events = [
            [
                'title'       => 'Laravel入門セミナー',
                'description' => 'Laravelの基本を学びます。',
                'location'    => '東京',
                'event_date'  => '2026-12-01 13:00:00',
            ],
            [
                'title'       => 'Dockerハンズオン',
                'description' => 'Dockerを実際に動かします。',
                'location'    => '大阪',
                'event_date'  => '2026-12-10 10:00:00',
            ],
            [
                'title'       => 'Webアプリ成果発表会',
                'description' => '受講生の成果を発表します。',
                'location'    => 'オンライン',
                'event_date'  => '2026-12-20 15:00:00',
            ],
        ];

        foreach ($events as $event) {
            // 同じタイトルがあれば更新、なければ作成(何回実行しても重複しない)
            Event::updateOrCreate(['title' => $event['title']], $event);
        }
    }
}