<?php

declare(strict_types=1);

namespace App\Actions;

use App\Contracts\ArticleScraper;
use App\DTOs\ArticleDTO;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;
use Throwable;

class ScrapeHackerNews implements ArticleScraper
{
    /**
     * @return Collection<int, ArticleDTO>
     *
     * @throws Throwable
     */
    public function handle(): Collection
    {
        /**
         * @var Collection<int, ArticleDTO> $articles
         */
        $articles = collect();
        $allArticlesScraped = false;

        for ($page = 1; !$allArticlesScraped; $page++) {
            $nextArticles = $this->scrapePage($page);
            $articles = $articles->merge($nextArticles);

            $allArticlesScraped = $nextArticles->isEmpty();
        }

        return $articles;
    }

    /**
     * @return Collection<int, ArticleDTO>
     *
     * @throws Throwable
     */
    private function scrapePage(int $page): Collection
    {
        $response = Http::get(string_or_fail(config('app.hacker_news_url')).'?p='.$page);
        $response->throw();

        $crawler = new Crawler($response->body());

        // Select all rows with id's and next sibling
        $tableRowNodes = $crawler->filter('#bigbox table tr[id], #bigbox table tr[id] + tr');

        if ($tableRowNodes->count() === 0) {
            /** @var Collection<int, ArticleDTO> */
            return collect();
        }

        return collect(range(0, $tableRowNodes->count() - 1))
            ->chunk(2)
            ->map(function (Collection $chunk) use ($tableRowNodes): ArticleDTO {
                $titleRowIndex = (int) $chunk->first();
                $metaRowIndex = (int) $chunk->last();

                $titleRow = $tableRowNodes->eq($titleRowIndex);
                $metaRow = $tableRowNodes->eq($metaRowIndex);

                $titleNode = $titleRow->filter('.title a');
                $pointsNode = $metaRow->filter('.score');

                $id = (int) $titleRow->attr('id');
                $title = $titleNode->text();
                $link = $titleNode->attr('href');
                $link = $this->isFullUrl(string_or_fail($link))
                    ? string_or_fail($link)
                    : string_or_fail(config('app.hacker_news_url')).'/'.$link;
                $createdAt = str($metaRow->filter('span.age')->attr('title'))
                    ->after(' ')
                    ->toString();
                $createdAt = Carbon::createFromTimestamp($createdAt);
                $points = 0;
                if ($pointsNode->count() > 0) {
                    $points = str($pointsNode->text())->before(' ')->toInteger();
                }

                return new ArticleDTO($id, $title, $link, $points, $createdAt);
            })
            ->values();
    }

    private function isFullUrl(string $url): bool
    {
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }
}
