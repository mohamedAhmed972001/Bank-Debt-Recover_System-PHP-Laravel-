<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {

    // قواعد التحقق
    $request->validate([
        'email' => 'required|email',
    ], [
        // رسائل الخطأ المخصصة
        'email.required' => 'البريد الإلكتروني مطلوب.',
        'email.email' => 'يرجى ادخال بيانات صحيحة',
        'password.required' => 'كلمة المرور مطلوبة.',
        'password.password' => 'يرجى ادخال بيانات صحيحة',
    ]);

    // محاولة تسجيل الدخول
    if (!Auth::attempt($request->only('email', 'password'), $request->filled('remember'))) {
        return back()->withErrors([
            'email' => 'يرجى ادخال بيانات صحيحة',
            'password' => 'يرجى ادخال بيانات صحيحة',
        ]);
    }


        $request->authenticate();

        $request->session()->regenerate();

        
        return redirect()->intended(RouteServiceProvider::HOME);

    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
