<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function showProfile($name, $ape)
    {
        //$user = User::findOrFail($id);
        $user['name'] = $name;
        $user['ape'] = $ape;
        return view('user.profile', ['user' => $user]);
    }
}
