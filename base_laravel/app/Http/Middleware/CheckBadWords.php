<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckBadWords
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $badWords = config('blacklist.badwords');
        $title = $request->input('title');
        $content = $request->input('content');

        foreach ($badWords as $badWord) {
            if (stripos($title, $badWord) !== false || stripos($content, $badWord) !== false) {
                return redirect()->back()->withErrors(['message' => 'Content contains inappropriate words.']);
            }
        }

        return $next($request);
    }
}