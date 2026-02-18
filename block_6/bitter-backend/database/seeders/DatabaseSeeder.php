<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Post;
use App\Models\Hashtag;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {

        $users = [
            [
                'username' => 'current_user',
                'name' => 'Текущий Пользователь',
                'email' => 'current@example.com',
                'password' => bcrypt('password'),
            ],
            [
                'username' => 'testuser',
                'name' => 'Тестовый Пользователь',
                'email' => 'test@example.com',
                'password' => bcrypt('password'),
            ],
            [
                'username' => 'developer',
                'name' => 'Разработчик',
                'email' => 'dev@example.com',
                'password' => bcrypt('password'),
            ],
            [
                'username' => 'organizer',
                'name' => 'Организатор',
                'email' => 'org@example.com',
                'password' => bcrypt('password'),
            ],
        ];

        foreach ($users as $userData) {
            User::create($userData);
        }

        $posts = [
            [
                'user_id' => 1,
                'content' => 'Привет всем! Это мой первый пост в Bitter @testuser #первыйпост',
            ],
            [
                'user_id' => 2,
                'content' => 'Сегодня отличный день для программирования! #код #vue',
            ],
            [
                'user_id' => 3,
                'content' => 'Встречаемся в 18:00 @current_user @testuser #встреча',
            ],
        ];

        foreach ($posts as $postData) {
            $post = Post::create($postData);
            
            preg_match_all('/#([\w\p{Cyrillic}]+)/u', $postData['content'], $hashtagMatches);
            foreach ($hashtagMatches[1] ?? [] as $tagName) {
                $tagName = strtolower($tagName);
                $hashtag = Hashtag::firstOrCreate(['name' => $tagName]);
                $post->hashtags()->attach($hashtag->id);
            }

            preg_match_all('/@([\w\p{Cyrillic}]+)/u', $postData['content'], $mentionMatches);
            foreach ($mentionMatches[1] ?? [] as $username) {
                $mentionedUser = User::where('username', $username)->first();
                if ($mentionedUser) {
                    DB::table('mentions')->insert([
                        'post_id' => $post->id,
                        'mentioned_user_id' => $mentionedUser->id,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }
        }
        DB::table('follows')->insert([
            ['follower_id' => 1, 'following_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['follower_id' => 1, 'following_id' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['follower_id' => 2, 'following_id' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}