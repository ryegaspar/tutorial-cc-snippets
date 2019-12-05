<?php

namespace App\Transformers\Snippets;

use App\Snippet;
use App\Step;
use League\Fractal\TransformerAbstract;

class StepTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @param Step $step
     * @return array
     */
    public function transform(Step $step)
    {
        return [
            'uuid'  => $step->uuid,
            'order' => (float)$step->order,
            'title' => $step->title ?: '',
            'body'  => $step->body ?: ''
        ];
    }
}
