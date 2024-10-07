<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Validator;
use Auth;

use App\Models\Todo;

class TodosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $todos = Todo::getAll($request);

        return response()->json($todos);
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
            'todo' => 'required',
            'description' => 'required'
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

        $todo = new Todo($input);
        $todo->status = 'Pending';
        $todo->save();

        return response()->json(['status' => 'Success']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $todo = Todo::getOne($id);

        if ($todo) {
            return response()->json($todo);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $todo = Todo::getOne($id);
        
        if ($todo) {
            $todo->update($request->input());

            return response()->json(['status' => 'Success']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $todo = Todo::getOne($id);

        if ($todo) {
            $todo->delete();
            
            return response()->json(['status' => 'Success']);
        }
    }
}
