<?php

namespace App\Http\Controllers\Snippets;

use App\Http\Controllers\Controller;
use App\Snippet;
use App\Step;
use App\Transformers\Snippets\StepTransformer;
use Illuminate\Http\Request;

class StepController extends Controller
{
    public function update(Snippet $snippet, Step $step, Request $request)
    {
        //TODO: authorize

        $step->update($request->only('title', 'body'));
    }

    public function store(Snippet $snippet, Request $request)
    {
        //TODO: authorize

        $step = $snippet->steps()->create(array_merge(
            $request->only('title', 'body'), [
            'order' => 1
        ]));

        return fractal()
            ->item($step)
            ->transformWith(new StepTransformer)
            ->toArray();
    }
}
