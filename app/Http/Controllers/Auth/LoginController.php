<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Customer;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $credentials['username'])->first();

        if (!$user) {
            return back()->withErrors([
                'username' => 'Username tidak ditemukan.',
            ])->onlyInput('username');
        }

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->role === 'admin') {
                return redirect()->route('dashboard');
            } elseif ($user->role === 'customer') {
                return redirect()->route('dashboard.pengunjung');
            } else {
                Auth::logout();
                return back()->withErrors([
                    'username' => 'Anda tidak mempunyai akses',
                ])->onlyInput('username');
            }
        }

        return back()->withErrors([
            'password' => 'Password yang dimasukkan salah.',
        ])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
