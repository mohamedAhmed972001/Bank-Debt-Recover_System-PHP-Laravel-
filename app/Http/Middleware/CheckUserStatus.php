<?php 
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckUserStatus
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            if (Auth::user()->Status == 1) {
                return $next($request);
            }
            Auth::logout();
        }
        return redirect()->back()->with(['message' => 'حسابك معطل، يرجى التواصل مع الإدارة.']);
    }
}
