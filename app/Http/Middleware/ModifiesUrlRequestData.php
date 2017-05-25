<?php

namespace App\Http\Middleware;

use Validator;
use Closure;

class ModifiesUrlRequestData
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$request->has('url')) {
            return $next($request);            
        }

        $validator = Validator::make($request->only('url'), [
            'url' => 'url'
        ]);

        if ($validator->fails()) {
            $request->merge([
                'url' => 'http://' . $request->url
            ]);
        }

        return $next($request);
    }
}
