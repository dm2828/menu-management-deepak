<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Menu;

class menuStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $menuId): Response
    {
        $menu = Menu::find($menuId);

        if (!$menu || $menu->status !== '1') {
            // If the menu is not found or inactive, return a 404 response
            abort(404, 'Menu is inactive.');
        }

        return $next($request);
    }
}
