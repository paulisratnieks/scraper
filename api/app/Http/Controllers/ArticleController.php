<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ArticleController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Article::latest()->paginate(10));
    }

    public function destroy(Article $article): Response
    {
        $article->delete();

        return response()->noContent();
    }
}
