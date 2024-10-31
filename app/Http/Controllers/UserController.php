<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index()
    {
        $users = User::with('roles')->get(); // Eager load roles
        $roles = Role::all(); // Fetch all roles

        return Inertia::render('Admin/Users/Index', [
            'users' => $users,
            'roles' => $roles, // Pass roles to the view
        ]);
    }


    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $roles = Role::all(); // Get all roles
        return Inertia::render('Admin/Users/Create', [
            'roles' => $roles
        ]);
    }

    /**
     * Store a newly created user in the database.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|exists:roles,name',
        ]);

        // Create the new user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']), // Hash the password
        ]);

        // Assign the selected role to the user
        $user->assignRole($validated['role']);

        // Redirect to the users index with success message
        return redirect()->route('users.index')->with('success', 'User created successfully');
    }

    /**
     * Update an existing user.
     */
    public function update(Request $request, User $user)
    {
        // Validate the request data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,  // Ensure email is unique except for this user
            'password' => 'nullable|string|min:8|confirmed',  // Password is optional
            'role' => 'required|exists:roles,name',
        ]);

        // Update user details
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'] ? Hash::make($validated['password']) : $user->password,  // Update password if provided
        ]);

        // Update the user's role
        $user->syncRoles([$validated['role']]);

        // Redirect back with success message
        return redirect()->route('users.index')->with('success', 'User updated successfully');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        // Remove all roles associated with the user before deleting
        $user->roles()->detach();
        $user->delete();

        // Redirect back with success message
        return redirect()->route('users.index')->with('success', 'User deleted successfully');
    }
}