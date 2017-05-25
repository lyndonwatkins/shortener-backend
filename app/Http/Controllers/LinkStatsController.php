<?php

namespace App\Http\Controllers;

use Cache;
use App\Link;
use Illuminate\Http\Request;

class LinkStatsController extends Controller
{
    public function show(Request $request)
    {
        $code = $request->get('code');

        $link = Cache::remember("stats.{$code}", 10, function () use ($code) {
            return Link::byCode($code)->first();
        });

        if ($link === null) {
            return response(null, 404);
        }

        return $this->linkResponse($link, [
            'requested_count' => (int) $link->requested_count,
            'used_count' => (int) $link->used_count,
            'last_requested' => $link->last_requested->toDateTimeString(),
            'last_used' => $link->last_used ? $link->last_used->toDateTimeString() : null,
        ]);
    }
}
