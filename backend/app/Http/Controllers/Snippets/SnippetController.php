<?php

namespace App\Http\Controllers\Snippets;

use App\Http\Controllers\Controller;
use App\Snippet;
use App\Transformers\Snippets\SnippetTransformer;
use Illuminate\Http\Request;

class SnippetController extends Controller
{
    /**
     * SnippetController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth:api'])
            ->only('store');
    }

    public function show(Snippet $snippet)
    {
        //TODO: authorize

        return fractal()
            ->item($snippet)
            ->transformWith(new SnippetTransformer())
            ->parseIncludes([
                'steps',
                'author',
                'user'
            ])
            ->toArray();
    }

    /**
     * @param Request $request
     * @return array
     */
    public function store(Request $request)
    {
        $snippet = $request->user()->snippets()->create();

        return fractal()
            ->item($snippet)
            ->transformWith(new SnippetTransformer())
            ->toArray();
    }
}
