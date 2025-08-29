<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Testing\PendingCommand;
use Tests\TestCase;

use function Safe\file_get_contents;

class ScrapeArticlesCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_scrape_articles_command(): void
    {
        $newArticle = [
            'id' => 45050958,
            'title' => 'The Math Behind GANs',
            'link' => $this->scrapeUrl().'/item?id=45041185',
            'points' => 10,
            'created_at' => Carbon::createFromTimestamp(1756381355),
        ];
        $existingArticle = [
            'id' => 45012995,
            'title' => 'Areal, Are.na&#x27;s new typeface',
            'link' => 'https://www.are.na/editorial/introducing-areal-are-nas-new-typeface',
            'points' => 1,
            'created_at' => Carbon::createFromTimestamp(1756123898),
        ];
        Article::create($existingArticle);
        $existingArticleUpdatedPoints = 151;
        $existingArticleDeleted = [
            'id' => 1756428267,
            'title' => 'Claude Sonnet will ship in Xcode',
            'link' => 'https://developer.apple.com/documentation/xcode-release-notes/xcode-26-release-notes',
            'points' => 1,
            'created_at' => Carbon::createFromTimestamp(1756123898),
        ];
        $existingModelDeleted = Article::create($existingArticleDeleted);
        $existingModelDeleted->delete();

        Http::fake(
            collect(range(1, 4))->mapWithKeys(fn (int $page): array => [
                $this->scrapeUrl().'?p='.$page => Http::response(file_get_contents(__DIR__.'/Mocks/Responses/HackerNewsResponsePage'.$page.'.html')),
            ])->toArray()
        );

        /**
         * @var PendingCommand $output
         */
        $output = $this->artisan('app:scrape-articles');
        $output->assertSuccessful();
        unset($output); // prevent destructor crash at teardown
        $this->assertDatabaseCount('articles', 3);
        $this->assertDatabaseHas('articles', $newArticle);
        $this->assertDatabaseHas('articles', $existingArticleDeleted);
        $this->assertDatabaseHas('articles', [
            ...$existingArticle,
            'points' => $existingArticleUpdatedPoints,
        ]);
    }

    public function test_scrape_articles_command_target_server_errors(): void
    {
        Http::fake([
            $this->scrapeUrl().'*' => Http::response(status: 500),
        ]);

        /**
         * @var PendingCommand $output
         */
        $output = $this->artisan('app:scrape-articles');
        $output->assertFailed()
            ->expectsOutput('Scraping failed with error: HTTP request returned status code 500');
    }

    private function scrapeUrl(): string
    {
        return string_or_fail(config('app.hacker_news_url'));
    }
}
