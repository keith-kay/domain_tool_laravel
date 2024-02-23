<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }
    public function create()
    {
        return view('users.create');
    }
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'is_user' => 'required|boolean',
            'is_admin' => 'required|boolean',
            'is_active' => 'nullable|boolean', // Assuming is_active is optional
        ]);

        // Create a new User instance
        $user = new User();
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->password = bcrypt($validatedData['password']);
        $user->is_user = $validatedData['is_user'];
        $user->is_admin = $validatedData['is_admin'];
        $user->is_active = $request->has('is_active'); // Set is_active based on checkbox

        // Save the user to the database
        $user->save();

        // Redirect the user to the index page with a success message
        return redirect()->route('users.index')->with('success', 'User added successfully!');
    }
}
