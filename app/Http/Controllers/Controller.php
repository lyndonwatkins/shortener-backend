<?php

namespace App\Http\Controllers;

use App\Link;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    protected function linkResponse(Link $link, $merge = [])
    {
        return response()->json([
            'data' => array_merge([
                'original_url' => $link->original_url,
                'shortened_url' => $link->shortenedUrl(),
                'code' => $link->code,
            ], $merge)
        ], 200);
    }
}
