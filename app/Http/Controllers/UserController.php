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
        try {
            //dd($request->input('is_active'));
            // Validate the incoming request data
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:8',
                'is_user' => 'nullable|boolean',
                'is_admin' => 'nullable|boolean', 
                'is_active' => ['required', 'boolean', function ($attribute, $value, $fail) {
                    if (!filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE)) {
                        $fail($attribute.' must be true or false.');
                    }
                }],
            ]);

            // Create a new user instance
            $user = new User();
            $user->name = $validatedData['name'];
            $user->email = $validatedData['email'];
            $user->password = bcrypt($validatedData['password']); // Hash the password
            $user->is_active = $request->has('is_active'); // Set is_active based on checkbox state
            $user->is_user = $request->has('is_user');
            $user->is_admin = $request->has('is_admin');
            $user->save();

            // Redirect to the user index page with a success message
            return redirect()->route('users.index')->with('success', 'User created successfully');
        } catch (\Exception $e) {
            // Log the exception
            \Log::error('Error creating user: ' . $e->getMessage());

            // Redirect back with an error message
            return redirect()->back()->with('error', 'An error occurred while creating the user: ' . $e->getMessage());
        }
    }
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }
    public function update(Request $request, User $user)
    {
        try {
            // Validate the incoming request data
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,'.$user->id,
                'password' => 'nullable|string|min:8',
                'is_user' => 'nullable|boolean',
                'is_admin' => 'nullable|boolean', 
                'is_active' => ['boolean', function ($attribute, $value, $fail) {
                    if (!filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE)) {
                        $fail($attribute.' must be true or false.');
                    }
                }],
            ]);

            // Update the user instance
            $user->name = $validatedData['name'];
            $user->email = $validatedData['email'];
            
            // Update the password if provided
            if (!empty($validatedData['password'])) {
                $user->password = bcrypt($validatedData['password']);
            }

            $user->is_active = $request->has('is_active'); // Set is_active based on checkbox state
            $user->is_user = $request->has('is_user');
            $user->is_admin = $request->has('is_admin');
            $user->save();

            // Redirect to the user index page with a success message
            return redirect()->route('users.index')->with('success', 'User updated successfully');
        } catch (\Exception $e) {
            // Log the exception
            \Log::error('Error updating user: ' . $e->getMessage());

            // Redirect back with an error message
            return redirect()->back()->with('error', 'An error occurred while updating the user: ' . $e->getMessage());
        }
    }
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('error', 'User deleted!');
    }
}
