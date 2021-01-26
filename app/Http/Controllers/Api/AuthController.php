<?php
/**
 * Created by PhpStorm.
 * User: OMP
 * Date: 26/01/2021
 * Time: 01:10 PM
 */


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
                'me' => [
                    'name'          => $me->name,
                    'mobile'        => $me->mobile,
                    'avatar'        => $me->avater,
                    'created_at'    => $me->created_at,
                    ],
                'status' => true
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
            'mobile' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only(['mobile', 'password']);

        if (! $token = Auth::attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        return $this->respondWithToken($token);
    }

    protected function respondWithToken($token)
    {
        $user = auth()->guard('api')->user();
        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
            ],
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60
        ], 200);
    }
}
