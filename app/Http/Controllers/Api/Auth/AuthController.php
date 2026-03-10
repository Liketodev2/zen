<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:255', 'exists:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'validation' => true
            ], 422);
        }

        $credentials = $request->only('email', 'password');

        try {

            if (!$token = Auth::guard('api')->attempt($credentials)) {
                return response()->json([
                    'error' => 'Unauthorized',
                    'validation' => false
                ], 401);
            }

            $user = Auth::guard('api')->user();

            $expiration = Carbon::createFromTimestamp(
                JWTAuth::setToken($token)->getPayload()->get('exp')
            )->utc();

            return response()->json([
                'user' => new UserResource($user),
                'token' => $token,
                'expires_at' => $expiration->toIso8601String(),
            ], 201);

        } catch (JWTException $e) {
            return response()->json([
                'error' => 'Could not create token'
            ], 500);
        }
    }



    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function refreshToken()
    {
        try {

            $newToken = Auth::guard('api')->refresh();

            $expiration = Carbon::createFromTimestamp(
                JWTAuth::setToken($newToken)->getPayload()->get('exp')
            )->utc();

            return response()->json([
                'token' => $newToken,
                'expires_at' => $expiration->toIso8601String(),
            ], 200);

        } catch (JWTException $e) {
            return response()->json([
                'error' => 'Could not refresh token',
                'validation' => false
            ], 401);
        }
    }



    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:60'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'validation' => true
            ], 422);
        }

        try {

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $token = JWTAuth::fromUser($user);

            $expiration = Carbon::createFromTimestamp(
                JWTAuth::setToken($token)->getPayload()->get('exp')
            )->utc();

            return response()->json([
                'user' => new UserResource($user),
                'token' => $token,
                'expires_at' => $expiration->toIso8601String(),
            ], 201);

        } catch (JWTException $e) {

            return response()->json([
                'error' => 'Could not create token',
                'validation' => false
            ], 500);

        } catch (\Exception $e) {

            return response()->json([
                'error' => 'User registration failed',
                'validation' => false
            ], 500);
        }
    }


    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        Auth::guard('api')->logout();
        return response()->json(['success' => 'Logged out successfully'], 200);
    }


    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUser()
    {
        $user = Auth::guard('api')->user();
        return response()->json(new UserResource($user),200);
    }



    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => ['required', 'string', 'min:8'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'validation' => true], 422);
        }

        $user = JWTAuth::user();


        if (!Hash::check($request->get('current_password'), $user->password)) {
            return response()->json(['error' => 'The current password is incorrect', 'validation' => false], 422);
        }

        $user->password = Hash::make($request->get('new_password'));
        $user->save();

        return response()->json([
            'success' => 'Password successfully updated.',
        ], 200);
    }


    /**
     * Delete authenticated user account (API).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteAccount()
    {
        try {
            $user = Auth::guard('api')->user();
            if (!$user) {
                return response()->json(['error' => 'Unauthenticated'], 401);
            }

            // Invalidate token / logout
            try {
                Auth::guard('api')->logout();
            } catch (\Exception $e) {
                // ignore
            }

            $user->delete();

            return response()->json(['success' => 'Account deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unable to delete account', 'details' => $e->getMessage()], 500);
        }
    }
}
