<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class SignupController extends Controller
{
    //
    public function index(){
        return view('view');
    }

    public function save(Request $req){

        $validate = $req->validate([
            'name'=>'required|alpha',
            'email'=>'required|email|unique:users,email',
            'password'=>'required'
        ]);

        $date = date("Y-m-d H:i:s");
        $user = new User();
        $user->insert([
            'name'=> $req->input('name'),
            'email'=> $req->input('email'),
            'password'=> Hash::make($req->input('password')),
            'created_at' => $date,
            'updated_at' => $date
        ]);
        return redirect('/admin/users');
    }
}
