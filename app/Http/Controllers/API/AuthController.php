<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    use ApiResponse;

    public function register(Request $request)
    {
        DB::beginTransaction();
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:155',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
            ]);
            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
            ]);
            $token = $user->createToken('API Token')->plainTextToken;
            DB::commit();
            return $this->successResponse(compact('user', 'token'), "User registered successfully!");
        } catch (\Throwable $exception) {
            DB::rollBack();
            Log::error('Error::Place@AuthController@register - ' . $exception->getMessage());
            return $this->errorResponse($exception->getMessage(), "Registration failed!", 422);
        }
    }

    /**
     * User Login
     * @throws ValidationException
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
        return $this->successResponse([
            'user' => $user,
            'token' => $user->createToken('API Token')->plainTextToken,
        ], "Login successful!");
    }

    /**
     * Get Authenticated User Profile
     */
    public function profile(Request $request)
    {
        return $this->successResponse([
            'user' => $request->user(),
        ], "Profile detail fetched successfully");
    }

    public function updateProfile(Request $request)
    {
        DB::beginTransaction();
        try {
            $user = auth()->user();

            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
                'dob' => 'required|date',
                'mobile' => ['required', 'digits_between:7,12', Rule::unique('users')->ignore($user->id)],
                'address' => 'required|string|max:255',
                'state_id' => 'required|exists:states,id',
                'district_id' => 'required|exists:districts,id',
                'city_id' => 'required|exists:cities,id',
                'pincode_id' => 'required|exists:pincodes,id',
                'password' => 'nullable|string|min:6',
                'active_status' => 'required|boolean',
            ]);

            if (!empty($validatedData['password'])) {
                $validatedData['password'] = Hash::make($validatedData['password']);
            } else {
                unset($validatedData['password']);
            }

            $user->update($validatedData);
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Profile updated successfully',
                'data' => ['user' => $user->fresh()]
            ]);
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Error::AuthController@updateProfile - ' . $exception->getMessage());
            return $this->errorResponse($exception->getMessage(), "Profile updation failed!", 500);
        }
    }

    /**
     * User Logout
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return $this->successResponse([], "Logged out successfully!");
    }
}
