<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;

class ArticlesTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_route(): void
    {
        $articlesPerPage = 10;
        /**
         * @var Collection<int, Article> $articles
         */
        $articles = Article::factory($articlesPerPage + 1)->create();

        $this->actingAs(User::factory()->create())
            ->getJson('articles')
            ->assertOk()
            ->assertJson([
                'data' => $articles
                    ->sortByDesc('created_at')
                    ->values()
                    ->take($articlesPerPage)
                    ->toArray(),
            ])
            ->assertJsonCount($articlesPerPage, 'data');
    }
}
