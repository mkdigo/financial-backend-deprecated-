<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class ApiProtectedRoute
{
  /**
  * Handle an incoming request.
  *
  * @param  \Illuminate\Http\Request  $request
  * @param  \Closure  $next
  * @return mixed
  */
  public function handle(Request $request, Closure $next)
  {
    try {
      $user = JWTAuth::parseToken()->authenticate();
    } catch(\Exception $e) {
      if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
        return response()->json(['success' => false, 'message' => 'Token Invalid'], 401);
      } else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
        try {
          $newToken = JWTAuth::parseToken()->refresh();
          return response()->json(['success' => false, 'access_token' => $newToken, 'message' => 'Token Expired'], 200);
        } catch(\Exception $e) {
          return response()->json(['success' => false, 'message' => 'The token has been blacklisted'], 401);
        }
      } else {
        return response()->json(['success' => false, 'message' => 'Token not found'], 401);
      }
    }
    return $next($request);
  }
}
