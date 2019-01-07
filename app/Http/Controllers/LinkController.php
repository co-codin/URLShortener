<?php

namespace App\Http\Controllers;

use App\Link;
use Illuminate\Http\Request;

class LinkController extends Controller
{
    public function show(Request $request)
    {
        $code = $request->get('code');

        $link = Link::byCode($code)->firstOrFail();

        $link->increment('used_count');

        return $this->linkResponse($link);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'url' => 'required|url',
        ], [
            'url.required' => 'Please enter a URL to shorten.',
            'url.url' => 'Hmm, that doesn\'t look like a valid URL.'
        ]);

        $link = Link::firstOrNew([
            'original_url' => $request->get('url')
        ]);

        if (!$link->exists) {
            $link->save();
        }

        $link->increment('requested_count');

        return $this->linkResponse($link);
    }
}
