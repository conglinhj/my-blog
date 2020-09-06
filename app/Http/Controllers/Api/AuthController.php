<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{

    private const UNKNOWN_AGENT_NAME = 'unknown';

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException | AuthenticationException
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw new AuthenticationException('The provided credentials are incorrect.');
        }

        $token_name = $request->header('User-Agent', self::UNKNOWN_AGENT_NAME);
        if ($currentAccessToken = $user->currentAccessToken()) {
            $currentAccessToken->delete();
        }
        $user->tokens()->where('name', $token_name)->delete();
        $new_token = $user->createToken($token_name)->plainTextToken;

        return new JsonResponse([
            'data' => $user,
            'access_token' => $new_token
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $user = new User(
            $request->only([
                'name',
                'email',
                'password'
            ])
        );
        $user->save();
        $token_name = $request->header('User-Agent', self::UNKNOWN_AGENT_NAME);

        return new JsonResponse([
            'data' => $user,
            'access_token' => $user->createToken($token_name)->plainTextToken
        ]);
    }

    /**
     * @param Request $request
     * @return void
     */
    public function logout(Request $request)
    {
        $user = $request->user();
        if ($user) {
            if ($user->currentAccessToken()) {
                $user->currentAccessToken()->delete();
            }
            $token_name = $request->header('User-Agent', self::UNKNOWN_AGENT_NAME);
            $user->tokens()->where('name', $token_name)->delete();
        }
    }
}
