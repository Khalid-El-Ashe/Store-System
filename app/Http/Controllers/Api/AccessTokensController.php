<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

class AccessTokensController extends Controller
{
    public function store(Request $request) {
        $request->validate([
            'email'=> 'required|email|max:255',
            'password'=> 'required|string|min:6',
            'device_name'=> 'string|max:255',
            'abillities'=> 'nullable|array' // todo the polices الصلاحيات
        ]);

        $user = User::where('email', $request->email)->first();
        if($user && Hash::check($request->password, $user->password)) {
            // i need make token to user model
            $device_name = $request->post('device_name', $request->userAgent());
            $token = $user->createToken($device_name, $request->post('abillities'));

            return response()->json(['code'=> 100, 'token' => $token->plainTextToken, 'user' => $user], Response::HTTP_OK);
        //     $token = $user->createToken('Laravel Password Guard Client')->accessToken;
        //     return response()->json(['token' => $token], Response::HTTP_OK); // 200
        // } else {
        //     return response()->json(['error' => 'UnAuthorised', Response::HTTP_UNAUTHORIZED]); // 401
        }
            return response()->json(['code'=> 401,'message' => 'Invalid Credentials'], Response::HTTP_UNAUTHORIZED);
    }

    //todo so now i need make function Delete the Toeken when the user is logedout --> and this operation is name (revoke token)
    public function distroy($token = null) {
        $user = Auth::guard('sanctum')->user();

        // if you need revoke from all tokens (logout from all devices)
        // $user->tokens()->delete();

        // i need check the token
        if($token === $token) {
            $user->currentAccessToken()->delete();
            return;
        }

        // now the token is Hashing i need decrypt the token
        $personalAccessToken = PersonalAccessToken::findToken($token);
            if($user->id == $personalAccessToken->tokenable_id && get_class($user) == $personalAccessToken->tokenable_type) {
                $personalAccessToken->delete();
            }
        $user->tokens()->where('token', $token)->delete(); // i get all tokens for this user

    }
}
