<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(){
        return User::latest()->get();
    }

    public function store(UserRequest $request){

        $validated = $request->validated();

        if($validated){
            return User::create([
                'name' => request('name'),
                'email' => request('email'),
                'password' => bcrypt(request('password')),
            ]);
        }

    }

    public function update(User $user){

        $user->update([
            'name' => request('name'),
            'email' => request('email'),
            'password' => request('password') ? bcrypt(request('password')): $user->password,
        ]);
        
        return $user;

    }
}
