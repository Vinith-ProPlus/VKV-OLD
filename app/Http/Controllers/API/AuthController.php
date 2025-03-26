<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Otp;
use App\Models\User;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Random\RandomException;
use Throwable;

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
        } catch (Throwable $exception) {
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
        $user = $request->user();
        $user->image = generate_file_url($user->image);
        return $this->successResponse(compact('user'), "Profile detail fetched successfully");
    }

    public function updateProfile(Request $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $user = auth()->user();
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'alternate_mobile' => ['nullable', 'digits_between:7,12', Rule::unique('users')->ignore($user->id)],
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            if ($request->hasFile('image')) {
                $oldImage = $user->image;
                $newImage = $validatedData['image'] = $request->file('image')?->store('users', 'public');
            }
            $user->update($validatedData);
            DB::commit();
            if (isset($oldImage)) {
                Storage::disk('public')->delete($oldImage);
            }
            $user = $user->fresh();
            $user->image = generate_file_url($user->image);
            return $this->successResponse(compact('user'), "Profile updated successfully");
        } catch (Exception $exception) {
            DB::rollBack();
            if(isset($newImage)){
                Storage::disk('public')->delete($newImage);
            }
            Log::error('Error::AuthController@updateProfile - ' . $exception->getMessage());
            return $this->errorResponse($exception->getMessage(), "Profile update failed!", 500);
        }
    }

    public function deleteAccount(): JsonResponse
    {
        DB::beginTransaction();
        try {
            $user = auth()->user();
            $user->update(['deleted_by' => Auth::user()->id, 'remember_token'=> null]);
            $user->delete();
            DB::commit();
            return $this->successResponse([], "Profile deleted successfully");
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error('Error::AuthController@deleteAccount - ' . $exception->getMessage());
            return $this->errorResponse($exception->getMessage(), "Profile deletion failed!", 500);
        }
    }

    /**
     * Send OTP for Forgot Password
     * @param Request $request
     * @return JsonResponse
     * @throws RandomException
     */
    public function sendForgotPasswordOTP(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), "Validation Failed!", 422);
        }

        $user = User::where('email', $request->email)->first();
        $otp = random_int(100000, 999999);
        $expiresAt = Carbon::now()->addMinutes(10); // OTP valid for 10 minutes

        // Save OTP in the database (soft-delete old OTPs if they exist)
        Otp::where('user_id', $user->id)->where('module', 'reset password')->delete();
        $otp = Otp::create([
            'user_id' => $user->id,
            'otp' => $otp,
            'module' => 'reset password',
            'expires_at' => $expiresAt,
        ]);

        // Send OTP via Email
        Mail::raw("Your OTP for password reset is: $otp. It is valid for 10 minutes.", static function ($message) use ($user) {
            $message->to($user->email)
                ->subject("Password Reset OTP");
        });

        return $this->successResponse(compact('otp'), "OTP sent successfully to your email!");
    }

    /**
     * Verify OTP for Password Reset
     */
    public function verifyForgotPasswordOTP(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|digits:6',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), "Validation Failed!", 422);
        }

        $user = User::where('email', $request->email)->first();
        $token = $user->createToken('API Token')->plainTextToken;
        $record = Otp::where('user_id', $user->id)
            ->where('otp', $request->otp)
            ->where('module', 'reset password')
            ->first();

        if (!$record || Carbon::now()->greaterThan($record->expires_at)) {
            return $this->errorResponse("Invalid or expired OTP!", "Verification failed!", 422);
        }

        $record->delete();

        return $this->successResponse(compact('user', 'token'), "OTP verified successfully!");
    }

    /**
     * Reset Password after OTP Verification
     */
    public function changePassword(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'old_password' => 'sometimes|required|string',
            'password' => 'required|string|min:8|confirmed|different:old_password',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), "Validation Failed!", 422);
        }

        $user = User::findOrFail($request->user_id);

        if ($request->filled('old_password') && !Hash::check($request->old_password, $user->password)) {
            return $this->errorResponse("Old password is incorrect!", "Validation Failed!", 422);
        }

        $user->update(['password' => Hash::make($request->password)]);
        $token = $user->createToken('API Token')->plainTextToken;
        return $this->successResponse(compact('user', 'token'), "Password reset successfully!");
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
