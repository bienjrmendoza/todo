<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use Validator;

use App\Models\User;
use App\Models\UserDetail;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $rules = [
            'username' => 'required|unique:users,username',
            'password' => 'required|min:8|required_with:confirm_password|same:confirm_password',
            'usertype' => 'required|in:admin,user',
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required'
        ];
        
        $input = $request->input();

        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            $data = [
                'status' => 'Fail',
                'errors' => $validator->errors()->toArray()
            ];

            return response()->json($data);
        }

        $user = new User($input);
        $user->password = Hash::make($input['password']);
        $user->save();

        $user_detail = new UserDetail($input);
        $user_detail->password = Hash::make($input['password']);
        $user_detail->user()->associate($user);
        $user_detail->save();

        return response()->json(['status' => 'Success']);
    }

    public function login(Request $request)
    {
        $rules = [
            'username' => 'required|exists:users,username',
            'password' => 'required',
        ];
        
        $input = $request->input();

        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            $data = [
                'status' => 'Fail',
                'errors' => $validator->errors()->toArray()
            ];

            return response()->json($data);
        }

        $user = User::where('username', '=', $input['username'])->first();

        if ($user) {
            if (Hash::check($input['password'], $user->password)) {
                $token = $user->createToken('YourAppName')->plainTextToken;

                $data = [
                    'status' => 'Success',
                    'token' => $token,
                    'user' => $user
                ];

                return response()->json(['status' => 'Success']);
            } else {
                $data = [
                    'status' => 'Fail',
                    'errors' => 'Incorrect Password'
                ];

                return response()->json($data);
            }
        }
    }    
}
