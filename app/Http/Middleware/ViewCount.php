<?php

namespace App\Http\Middleware;

use App\View;
use Closure;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\Post\ShowRequest;


class ViewCount
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
        if ($request->post->views()->where('user_id', Auth::user()->id)->count() === 0  ||
          ((strtotime(Carbon::now()) - (strtotime((View::orderBy('created_at', 'desc')->where('user_id', Auth::user()->id)
               ->first()->created_at)))) > 3600))
        {

            $request->post->views()->create(array_merge([
                'user_id' => Auth::user()->id,
            ], $request->all()));

        }

        return $next($request);
    }
}
