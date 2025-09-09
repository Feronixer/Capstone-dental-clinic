<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function showLoginForm(){
        return view("auth.login");
    }

    public function login(Request $request) {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'min:8'],

        ]);
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('admin-dashboard')->with('success','Login Successful');
        }

        return back()->withErrors([
            'error' => 'The provided credentials do not match our records.',
        ]);
    }

    public function register(Request $request) {
        $credential = $request->validate([
            'name' => ['required','string'],
            'email'=> ['required', 'email', 'string'],
            'password'=> ['required','string', 'min:8'],
        ]);

        User::create([
            'name'=> $request->name,
            'email'=> $request->email,
            'password'=> Hash::make($request->password),
        ]);
        return redirect()->route('login')->with('success','Success');
    }
    public function showRegisterForm(){
        return view('admin.register');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login')->with('success','Logged out successfully');
    }

    public function addUser(Request $request)
    {
        $credential = $request->validate([
            'username' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'confirm_password' => 'required|string|same:password',
            'role_id' => 'required|exists:roles,id'
        ]);

        User::create([
            'username' => $credential['username'],
            'name' => $credential['name'],
            'email' => $credential['email'],
            'password' => bcrypt($credential['password']),
            'role_id' => $credential['role_id']
        ]);

        return redirect()->route('admin-account-management')->with('success', 'User added successfully.');
    }
}
