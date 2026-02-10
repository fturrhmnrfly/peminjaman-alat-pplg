<?php

namespace App\Http\Controllers;

use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function logout()
    {
        $user = auth()->user();
        if ($user) {
            LogAktivitas::catat('Logout', 'Auth', "User {$user->nama} logout");
        }
        Auth::logout();
        return redirect('/login');
    }
}
