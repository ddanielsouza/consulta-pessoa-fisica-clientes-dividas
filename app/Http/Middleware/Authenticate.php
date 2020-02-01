<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;
use App\Utils\Facades\APIAuth;
class Authenticate
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $token = $request->header('Authorization');
        if(APIAuth::checkAuth($token)){
            return $next($request);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Usuário não autenticado'
            ]);
        }
        
        
    }
}
