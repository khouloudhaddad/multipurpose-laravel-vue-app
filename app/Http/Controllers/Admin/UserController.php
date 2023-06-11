<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;

class UserController extends Controller
{
    public function index(){
       // dd(UserResource::collection(User::latest()->get()));
        return User::latest()->get();
    }

    public function store(){

        request()->validate([
            'name' => 'required|regex:/^[\pL\s\-]+$/u',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
        ]);

        return User::create([
                'name' => request('name'),
                'email' => request('email'),
                'password' => bcrypt(request('password')),
        ]);

    }

    public function update(User $user){

        request()->validate([
            'name' => 'required|regex:/^[\pL\s\-]+$/u',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'password' => 'sometimes|min:8',
        ]);

        $user->update([
            'name' => request('name'),
            'email' => request('email'),
            'password' => request('password') ? bcrypt(request('password')): $user->password,
        ]);

        return $user;

    }

    public function delete(User $user){
        $user->delete();

        return response()->noContent();
    }
}
