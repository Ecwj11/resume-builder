<?php

namespace App\Repositories;

use App\Models\Role;
use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface
{
    public function all()
    {
        return User::all();
    }
    
    public function find($id)
    {
        return User::find($id);
    }
    
    public function findWhere($whereParam = [], $isOne = false)
    {
        if ($isOne) {
            return User::where($whereParam)->orderBy('id', 'desc')->get()->first();
        }
        return User::where($whereParam)->get()->all();
    }

    public function create($param = [])
    {
        if ($param != [] && ! is_null($param)) {
            $model = new User();
            
            foreach ($param as $key => $value) {
                $model->$key = $value;
            }
            $model->save();
            
            return $model->id;
        }
    }
    
    public function update($id, $param = [])
    {
        if ($param != [] && ! is_null($param)) {
            $model = User::find($id);
            foreach ($param as $key => $value) {
                $model->$key = $value;
            }
            $model->save();
        }
        
        return true;
        
    }
    
    public function updateWhere($whereParam = [], $param = [])
    {
        if ($param != [] && ! is_null($param) && $whereParam != [] && ! is_null($whereParam)) {
            $models = User::where($whereParam)->get()->all();
            foreach ($models as $model) {
                foreach ($param as $key => $value) {
                    $model->$key = $value;
                }
                $model->save();
            }
        }
        
        return $models;
    }

    public function register($datas)
    {
        $role = Role::where('role', Role::ROLE_USER)->get()->first();
        $params = [
            'email' => $datas['email'],
            'password' => Hash::make($datas['password']),
            'created_at' => $datas['date_now'],
            'role_id' => $role->id
        ];
        $model = new User();
        foreach ($params as $key => $value) {
            $model->$key = $value;
        }
        $model->save();
        
        return $model;
    }
}