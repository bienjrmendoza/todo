<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Todo extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'todos';

    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'password'
    ];

    public static function getAll()
    {
        $query = self;

        if (!empty($input)) {
            $fields = (new self)->getFillable();

            foreach ($fields AS $field) {
                if ($request->filled($field)) {
                    $query->where($field, 'LIKE', '%'. $request->input($field) . '%');
                }
            }
        }

        $order = $request->input('order', 'id');
        $sort = $request->input('sort', 'asc');
        $page = $request->input('page', 1);

        return $query->orderBy($order, $sort)->paginate(10, ['*'], 'page', $page);
    }

    public static function getOne($id)
    {
        return self::where('id', '=', $id)->first();
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}
