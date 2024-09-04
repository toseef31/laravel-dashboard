<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function store(Request $request)
    {
        try{
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
                'role' => ['required', 'string'],
                'is_active' => 'required|boolean',
            ]);

            $twoFactorSecret = Str::uuid()->toString();

            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'role' => $validatedData['role'],
                'is_active' => $validatedData['is_active'],
                'two_factor_secret' => $twoFactorSecret,
            ]);

            return $this->sendResponse('User Created Successfully', $user, 201);
        } catch (\Exception $e) {
            return $this->sendError('Failed to create user',$e->getMessage(), 500);
        }
    }
    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 15); # Default to 15 items per page
            $users = User::paginate($perPage);
            return $this->sendPaginatedResponse($users, 200);
        } catch (\Exception $e) {
            return $this->sendError('Failed to fetch users', $e->getMessage(), 500);
        }
    }

    public function deleteUser(Request $request)
    {
        try {
            $user = User::find($request->user_id);

            if (!$user) {
                return $this->sendError('User not found', 'User does not exisit', 404);
            }
            $user->delete();
            return $this->sendResponse('User deleted successfully', null, 200);
        } catch (\Exception $e) {
            return $this->sendError('Failed to delete user', $e->getMessage(), 500);
        }
    }
}