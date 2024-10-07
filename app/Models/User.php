<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'password',
        'usertype'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public static function getAll($request)
    {
        $query = self;

        if (!empty($request->input())) {
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

    public function toArray()
    {
        $array = parent::toArray();

        if ($this->created_at) {
            $array['created_at'] = $this->created_at->setTimezone('GMT+8')->format('F j, Y, g:i a');
        }

        if ($this->updated_at) {
            $array['updated_at'] = $this->updated_at->setTimezone('GMT+8')->format('F j, Y, g:i a');
        }

        return $array;
    }
}
