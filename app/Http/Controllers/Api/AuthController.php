<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request){
        $request->validate([
            'username' => 'required|string|exists:users,name',
            'password' => 'required|string|min:8'
        ]);
    }
}
