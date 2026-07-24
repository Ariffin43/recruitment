<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $userRole = Auth::user()->role;

        if (!in_array($userRole, $roles)) {

            $redirects = [
                'pelamar' => 'pelamar.dashboard',
                'hod'     => 'hod.dashboard',
                'hrd'     => 'hrd.dashboard',
                'gm'      => 'gm.approval',
            ];

            return isset($redirects[$userRole]) ? redirect()->route($redirects[$userRole])->with([
                    'title'      => 'Forbidden',
                    'notifikasi' => 'You dont have permission',
                    'type'       => 'warning',
                ])
            : redirect('/');
        }

        return $next($request);
    }
}