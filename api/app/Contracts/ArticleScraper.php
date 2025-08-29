<?php

declare(strict_types=1);

namespace App\Contracts;

use App\DTOs\ArticleDTO;
use Illuminate\Support\Collection;

interface ArticleScraper
{
    /**
     * @return Collection<int, ArticleDTO>
     */
    public function handle(): Collection;
}
