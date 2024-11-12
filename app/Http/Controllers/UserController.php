<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all(); // Fetch all users
        return view('users.index', compact('users'));
    }

   
    public function create()
    {
        return view('users.create'); // Show form to create new user
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully');
    }

    public function profile()
    {
        $user = Auth::user();
        return view('profile', compact('user'));
    }

    // Update the profile information
    public function updateProfile(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'password' => 'nullable|string|min:8|confirmed',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Check if the file exists and is valid
        if ($request->hasFile('profile_image')) {
            Log::info('File exists');  // Log that the file is detected

            if ($request->file('profile_image')->isValid()) {
                Log::info('File is valid');  // Log that the file is valid

                // Delete old image if it exists
                if ($user->profile_image && Storage::exists('public/' . $user->profile_image)) {
                    Storage::delete('public/' . $user->profile_image);
                }

                // Store the new image
                $path = $request->file('profile_image')->store('profile_images', 'public');
                $user->profile_image = $path;
            } else {
                Log::error('File is not valid');  // Log if the file is not valid
                return back()->withErrors(['profile_image' => 'Uploaded file is not valid.']);
            }
        } else {
            Log::error('No file detected in the request');  // Log if the file is not detected
        }

        $user->save();

        return redirect()->route('profile')->with('success', 'Profile updated successfully.');
    }
}
