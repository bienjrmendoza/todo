<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\UserDetail;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $order = $request->input('order', 'id');
        $sort = $request->input('sort', 'asc');
        $page = $request->input('page', 1);

        $users = User::with('user_detail')->paginate(10, ['*'], 'page', $page);

        return response()->json($users);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'username' => 'required',
            'password' => 'required|min:8|required_with:confirm_password|same:confirm_password',
            'usertype' => 'required|in:admin,user',
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required'
        ];
        
        $input = $request->input();

        $validator = Validator::make($input, $rules);

        if ($validator->passes()) {
            $user = User::create($input);

            $user_detail = new UserDetail($input);
            $user_detail->user()->associate($user);
            $user_detail->save();
        }

        return response()->json(['status' => 'Success']);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $user = User::getOne($id);

        if ($user) {
            return response()->json($user);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $user = User::getOne($id);
        
        if ($user) {
            $user->update($request->input());

            if ($user->user_detail) {
                $user->user_detail->update($request->input(['user_detail']));
            }

            return response()->json(['status' => 'Success']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $user = User::getOne($id);

        if ($user) {
            $user->delete();
            
            return response()->json(['status' => 'Success']);
        }
    }
}
