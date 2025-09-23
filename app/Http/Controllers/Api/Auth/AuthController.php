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
            return response()->json(['errors' => $validator->errors(), 'validation' => true], 422);
        }

        $user = User::query()->where('email','=', $request->email)->first();

        $credentials = $request->only('email', 'password');

        try {
            if (!$token = Auth::guard('api')->attempt($credentials)) {
                return response()->json(['error' => 'Unauthorized', 'validation' => false], 401);
            }

            $expiration = Carbon::createFromTimestamp(JWTAuth::setToken($token)->getPayload()->get('exp'));

            return response()->json([
                'user' => new UserResource($user),
                'token' => $token,
                'expires_at' => $expiration->toDateTimeString(),
            ], 201);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token'], 500);
        }
    }



    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function refreshToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'refresh_token' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'validation' => true], 422);
        }

        try {
            $newAccessToken = JWTAuth::setToken($request->refresh_token)->refresh();
            $expiration = Carbon::createFromTimestamp(JWTAuth::setToken($newAccessToken)->getPayload()->get('exp'));
            return response()->json([
                'token' => $newAccessToken,
                'expires_at' => $expiration->toDateTimeString(),
            ], 200);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not refresh token', 'validation' => false], 500);
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
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'validation' => true], 422);
        }

        try {


            $user = User::query()->create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $token = JWTAuth::fromUser($user);

            return response()->json([
                'user' => new UserResource($user),
                'token' => $token,
            ], 201);

        }catch (\Exception $exception){
            return response()->json(['error' => $exception->getMessage(), 'validation' => false], 422);
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
}
