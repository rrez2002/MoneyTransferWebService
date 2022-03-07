<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Auth;
use Hash;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Request;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum', ['except' => ['login', 'register']]);
    }

    /**
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        $user = User::create($data);

        Auth::onceUsingId($user->id);

        $token = Auth::user()->createToken($request->ip())->plainTextToken;

        return new JsonResponse(['token' => $token], Response::HTTP_CREATED);
    }

    /**
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        $user = User::firstWhere('phone', $credentials['phone']);

        if ($user && Hash::check($credentials['password'], $user->password)) {
            Auth::onceUsingId($user->id);

            $token = Auth::user()->createToken($request->ip())->plainTextToken;
            return new JsonResponse(['token' => $token], Response::HTTP_OK);
        }

        return new JsonResponse(['error' => 'bad request'], Response::HTTP_BAD_REQUEST);
    }

    /**
     * @return JsonResponse
     */
    public function logout()
    {
        Auth::user()->currentAccessToken()->delete();
        return new JsonResponse(['message' => 'logout success'], Response::HTTP_OK);
    }

    /**
     * refresh token
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function refresh(Request $request)
    {
        $token = Auth::user()->createToken($request->ip())->plainTextToken;
        Auth::user()->currentAccessToken()->delete();
        return new JsonResponse(['token' => $token], Response::HTTP_OK);
    }

}
