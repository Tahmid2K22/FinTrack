<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class User extends Model
{
    use HasFactory;

    protected $table = 'users';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
    ];

    protected $hidden = [
        'password',
    ];

    public static function findById($id)
    {
        return DB::selectOne('SELECT * FROM users WHERE id = :id', ['id' => $id]);
    }

    public static function findByEmail($email)
    {
        return DB::selectOne('SELECT * FROM users WHERE email = :email', ['email' => $email]);
    }

    public static function getMaxId()
    {
        $result = DB::selectOne('SELECT NVL(MAX(id), 0) AS max_id FROM users');
        return $result->max_id ?? 0;
    }

    public static function createUser(array $data)
    {
        $id = self::getMaxId() + 1;

        DB::statement(
            'INSERT INTO users (id, name, email, password, role, is_active, created_at, updated_at)
             VALUES (:id, :name, :email, :password, :role, :is_active, SYSTIMESTAMP, SYSTIMESTAMP)',
            [
                'id'         => $id,
                'name'       => $data['name'],
                'email'      => $data['email'],
                'password'   => $data['password'],
                'role'       => $data['role'] ?? 'employee',
                'is_active'  => $data['is_active'] ?? 1,
            ]
        );

        return self::findById($id);
    }

    public static function emailExists($email)
    {
        $result = DB::select('SELECT COUNT(*) AS cnt FROM users WHERE email = :email', ['email' => $email]);
        return $result[0]->cnt > 0;
    }
}
