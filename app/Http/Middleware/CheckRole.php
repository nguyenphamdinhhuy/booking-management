<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $requiredRole): Response
    {
        if (!Auth::check()) {
            return redirect('/login');
        }
    
        $userRole = Auth::user()->role;

        switch ($userRole) {
            case "admin":
                // Admin: cho phép luôn
                break;

            case "staff":
                if ($requiredRole != 'staff') {
                    return redirect('/')->with('error', 'Chỉ nhân viên hoặc admin mới được truy cập.');
                }
                break;

            case "user":
                if ($requiredRole != 'user') {
                    return redirect('/')->with('error', 'Bạn không có quyền truy cập.');
                }
                break;

            default:
                return redirect('/')->with('error', 'Vai trò không hợp lệ.');
        }


        return $next($request);
    }
    
}
