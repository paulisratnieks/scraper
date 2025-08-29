<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Actions\UpdateArticles;
use App\Contracts\ArticleScraper;
use Exception;
use Illuminate\Console\Command;

class ScrapeArticles extends Command
{
    /**
     * @var string
     */
    protected $signature = 'app:scrape-articles';

    public function handle(ArticleScraper $scraper, UpdateArticles $updateArticles): int
    {
        try {
            $articles = $scraper->handle();
            $updateArticles->handle($articles);
        } catch (Exception $e) {
            $this->error('Scraping failed with error: '.$e->getMessage());

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
