<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // register
    public function create(){
        return view('users.create');
    }

    public function login(){
        return view('users.login');
    }

    // authenticate user for login
    public function authenticate(Request $request){

        $formFields = $request->validate([
           'email' => ['required', 'email'],
           'password' => 'required'
        ]);

        if (auth()->attempt($formFields)){
            $request->session()->regenerate();
            return redirect('/')->with('success', 'user logged in successfully');
        }

        return back()->withErrors(['email' => 'invalid credentials'])->onlyInput('email');
    }

    public function logout(Request $request){
        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerate();

        return redirect('/login')->with('success', 'user logout successfully');
    }

    public function store(Request $request){
        $formFields = $request->validate([
            'name' => ['required', 'min:4'],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => ['required', 'confirmed', 'min:5']
        ]);

        $formFields['password'] = bcrypt($formFields['password']);

        User::create($formFields);

        // redirect user to login page
        return redirect('/')->with('success', 'user created successfully!');
    }
}
