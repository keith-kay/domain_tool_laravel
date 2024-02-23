<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Hash;
use Session;

class CustomAuthController extends Controller
{
    public function login()
    {
        return view("auth.login");
    }
    public function registration()
    {
        return view("auth.registration");
    }
    public function registerUser(Request $request){
        $request->validate([
            'name'=>'required',
            'email'=>'required|email|unique:users',
            'password'=>'required|min:6'
        ]);
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $res = $user->save();
        if($res){
            return back()->with('success','You have registered successfully');
        }else{
            return back()->with('fail','Something went wrong');
        }
    }
    public function loginUser(Request $request){
        $request->validate([
            'email'=>'required|email',
            'password'=>'required|min:6'
        ]);
        $user = User::where('email','=',$request->email)->first();
        if($user){
            if(Hash::check($request->password, $user->password)){
                $request->session()->put('loggedId', $user->id);
                return redirect('dashboard');
            }else{
                return back()->with('fail','Password is not correct');
            }
        }else {
            return back()->with('fail',"Email doesn't exist");
        }
    }
    public function dashboard() {
        return view('auth.dashboard');
    }
}
