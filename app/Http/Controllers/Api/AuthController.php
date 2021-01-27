<?php
/**
 * Created by PhpStorm.
 * User: OMP
 * Date: 26/01/2021
 * Time: 01:10 PM
 */


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('api', ['except' => ['login']]);
    }

    public function me()
    {
        $me = auth()->guard('api')->user();
        if($me) {
            return response()->json([
                'me' => new UserResource($me)
            ], 200);
        }
        return response()->json(['message' => 'not authorized', 'status' => false], HTTP_URL_STRIP_ALL);
    }

    /**
     * Store a new user.
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'mobile' => 'required|string|unique:users,mobile',
            'avatar' => 'nullable|string|max:150',
            'password' => 'required|confirmed|min:6',
        ]);
        try {

            $user = new User;
            $user->name = $request->input('name');
            $user->mobile = $request->input('mobile');
            $plainPassword = $request->input('password');
            $user->password = app('hash')->make($plainPassword);

            $user->save();

            return response()->json(['user' => $user, 'message' => 'CREATED'], 201);
        } catch (\Exception $e) {
            return response()->json(['messagesdf' => 'User Registration Failed!'], 409);
        }

    }

    /**
     * Get a JWT via given credentials.
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $this->validate($request, [
            'mobile' => ['required', 'string','regex:/^09(1[0-9]|3[1-9]|2[1-9])( |-)?[0-9]{3}( |-)?[0-9]{4}$/'],
            'password' => 'required|string',
        ]);

        $credentials = $request->only(['mobile', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        return $this->respondWithToken($token);
    }

    public function logout()
    {
        auth('api')->logout();
        return response()->json(['message' => __('User logged out!')], 200);
    }

    protected function respondWithToken($token)
    {
        $user = auth()->guard('api')->user();
        $now = new \DateTime("now", new \DateTimeZone('UTC'));
        $life_time = auth()->guard('api')->factory()->getTTL() * 60;
        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
            ],
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => ($now->getTimestamp() + (int) ($life_time > 0 ? $life_time : 31536000))
        ], 200);
    }
}
