<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Article;
use App\Models\Comment;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(8)->create();

        Tag::factory(80)->create();

        Article::factory(15)->create()->each(function ($article) {
            $randomTag = Tag::inRandomOrder()->limit(random_int(3, 10))->get();
            $article->tags()->saveMany($randomTag);

            $randomUsers = User::where('roles', 'LIKE', "%WRITER%")->get();
            $article->author()->associate($randomUsers->random())->save();
        });

        Comment::factory(55)->create()->each(function ($comment) {
            $randomUsers = User::inRandomOrder()->limit(6)->get();
            $comment->author()->associate($randomUsers->random())->save();

            $randomArticle = Article::inRandomOrder()->limit(10)->get();
            $comment->article()->associate($randomArticle->random())->save();
        });
    }
}
