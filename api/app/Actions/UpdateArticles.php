<?php

declare(strict_types=1);

namespace App\Actions;

use App\DTOs\ArticleDTO;
use App\Models\Article;
use Illuminate\Support\Collection;

class UpdateArticles
{
    /**
     * @param  Collection<int, ArticleDTO>  $articles
     */
    public function handle(Collection $articles): void
    {
        $articles->each(function (ArticleDTO $article): void {
            $model = Article::find($article->id);

            if ($model === null) {
                Article::create([
                    'id' => $article->id,
                    'title' => $article->title,
                    'link' => $article->link,
                    'points' => $article->points,
                    'created_at' => $article->createdAt,
                ]);
            } elseif (!$model->trashed() && $model->points !== $article->points) {
                $model->update([
                    'points' => $article->points,
                ]);
            }
        });
    }
}
