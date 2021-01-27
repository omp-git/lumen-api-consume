<?php
/**
 * Created by PhpStorm.
 * User: OMP
 * Date: 26/01/2021
 * Time: 03:34 PM
 */

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Http\Resources\Api\UserCollectionResource;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('api', ['except' => []]);
    }

    public function index(Request $request)
    {
        $this->validate($request, ['page' => 'nullable|numeric|min:0', 'offset' => 'nullable|numeric|min:0']);
        $users = User::select('name', 'mobile', 'avatar', 'created_at')->whereActive(true);
        if($request->filled('page')) {
            $offset = $request->filled('offset') ? $request->input('offset') :config('setting.offset');
            $users->offset(($request->input('page') - 1) * $offset)->limit($offset);
            $users = $users->paginate($offset);
        }
        else {
            $users = $users->get();
        }
        return response()->json(['data' => (new UserCollectionResource($users))->response()->getData(true)], 200);
    }
}