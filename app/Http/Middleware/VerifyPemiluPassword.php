<?php

namespace App\Http\Middleware;

use App\Models\Pemilu;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyPemiluPassword
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $slug = $request->route('slug');
        $pemilu = Pemilu::where('slug', $slug)->first();

        if ($pemilu && $pemilu->is_private && auth()->user()->role != 'admin') {
            if (!$request->session()->has('pemilu_' . $pemilu->slug .'_verified')) {
                return redirect()->route('user.dashboard')->with('error', 'Password diperlukan untuk mengakses pemilu ini');
            }
        }

        return $next($request);
    }
}
