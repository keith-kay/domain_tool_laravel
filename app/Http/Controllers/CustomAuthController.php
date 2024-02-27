<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Domain;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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
    
    public function loginUser(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|min:6'
    ]);

    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        // Authentication successful
        return redirect()->intended('dashboard');
    } else {
        // Authentication failed
        return back()->withInput()->with('fail', 'Invalid email or password');
    }
}
    public function dashboard(Request $request)
{
    // Retrieve all domains with their associated companies
    $domains = Domain::with('company')->get();

    // Get current date
    $currentDate = Carbon::now();

    // Initialize counts
    $activeCount = 0;
    $expiredCount = 0;

    // Loop through domains to calculate counts
    foreach ($domains as $domain) {
        $expiryDate = Carbon::parse($domain->expiry_date);

        if ($expiryDate->gt($currentDate)) {
            // Domain is active
            $activeCount++;
        } else {
            // Domain has expired
            $expiredCount++;
        }
    }

    // Retrieve the currently authenticated user
    $user = Auth::user();

    // Log the name of the logged-in user
    Log::info('Logged-in username: ' . ($user ? $user->name : 'No user logged in'));

    // Check if a user is logged in
    if ($user) {
        // User is logged in, retrieve the user's name
        $userName = $user->name;
    } else {
        // User is not logged in, set the username to empty or handle the case accordingly
        $userName = '';
    }

    // Pass counts, domains, and user name to the view
    return view('auth.dashboard', [
        'activeCount' => $activeCount,
        'expiredCount' => $expiredCount,
        'domains' => $domains,
        'userName' => $userName,
    ]);
}

    protected function redirectToDashboard()
    {
        return redirect()->route('dashboard');
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}

