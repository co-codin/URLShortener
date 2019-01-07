<?php

namespace App\Http\Middleware;

use Closure;
use Validator;

class ModifiesUrlRequestData
{
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
