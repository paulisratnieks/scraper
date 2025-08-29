<?php

declare(strict_types=1);

namespace App\DTOs;

use Carbon\Carbon;

class ArticleDTO
{
    public function __construct(
        public int $id,
        public string $title,
        public string $link,
        public int $points,
        public Carbon $createdAt,
    ) {}
}
