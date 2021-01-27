<?php
/**
 * Created by PhpStorm.
 * User: OMP
 * Date: 27/01/2021
 * Time: 01:34 AM
 */

namespace App\Http\Middleware;

use Closure;

class CorsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $headers = [
            'Access-Control-Allow-Origin'      => '*',
            'Access-Control-Allow-Methods'     => 'POST, GET, OPTIONS, PUT, DELETE',
            'Access-Control-Allow-Credentials' => 'true',
            'Access-Control-Max-Age'           => '86400',
            'Access-Control-Allow-Headers'     => 'Content-Type, Authorization, X-Requested-With'
        ];

        if ($request->isMethod('OPTIONS'))
        {
            return response()->json('{"method":"OPTIONS"}', 200, $headers);
        }

        $response = $next($request);
        foreach($headers as $key => $value)
        {
            $response->header($key, $value);
        }

        return $response;
    }
    // For security, you should probably specify a URL:
    // ->header('Access-Control-Allow-Origin', 'localhost')
    // ->header('Access-Control-Allow-Origin', '*');
    // ->header('Access-Control-Allow-Credentials', 'true)
    // ->header('Access-Control-Allow-Headers', 'X-PINGOTHER, Content-Type, Authorization, Content-Length, X-Requested-With')
    // ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS');
    // ->header('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type,X-Token-Auth, Authorization');

    // ->header(‘Access-Control-Allow-Origin’, ‘*’)
    // ->header(‘Access-Control-Allow-Methods’, ‘GET, POST, PUT, DELETE, OPTIONS’)
    // ->header(‘Access-Control-Allow-Headers’, ‘X-Requested-With, Content-Type, X-Token-Auth, Authorization’);
}