<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
  /**
  * Create a new AuthController instance.
  *
  * @return void
  */
  public function __construct()
  {
    // $this->middleware('auth:api', ['except' => ['login']]);
  }

  /**
  * Get a JWT via given credentials.
  *
  * @return \Illuminate\Http\JsonResponse
  */
  public function login()
  {
    $credentials = request(['username', 'password']);

    if (! $token = auth('api')->attempt($credentials)) {
      return response()->json([
        'success' => false,
        'message' => 'Unauthorized'
      ], 401);
    }

    return $this->respondWithToken($token);
  }

  /**
  * Get the authenticated User.
  *
  * @return \Illuminate\Http\JsonResponse
  */
  public function me()
  {
    return response()->json([
      'success' => true,
      'data' => auth('api')->user()
    ]);
  }

  /**
  * Log the user out (Invalidate the token).
  *
  * @return \Illuminate\Http\JsonResponse
  */
  public function logout()
  {
    auth('api')->logout();

    return response()->json([
      'success' => true,
      'message' => 'Successfully logged out'
    ]);
  }

  /**
  * Refresh a token.
  *
  * @return \Illuminate\Http\JsonResponse
  */
  public function refresh()
  {
    return $this->respondWithToken(auth('api')->refresh());
  }

  /**
  * Get the token array structure.
  *
  * @param  string $token
  *
  * @return \Illuminate\Http\JsonResponse
  */
  protected function respondWithToken($token)
  {
    return response()->json([
      'success' => true,
      'access_token' => $token,
      'token_type' => 'bearer',
      'expires_in' => auth('api')->factory()->getTTL() * 60
      ]);
    }
  }
