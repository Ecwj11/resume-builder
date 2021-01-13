<?php

namespace App\Repositories;

use App\Models\Role;
use App\Repositories\Interfaces\RoleRepositoryInterface;
use DB;
use Illuminate\Support\Facades\Auth;

class RoleRepository implements RoleRepositoryInterface
{
    public function all()
    {
        return Role::all();
    }
    
    public function find($id)
    {
        return Role::find($id);
    }
    
    public function findWhere($whereParam = [], $isOne = false)
    {
        if ($isOne) {
            return Role::where($whereParam)->orderBy('id', 'desc')->get()->first();
        }
        return Role::where($whereParam)->get()->all();
    }

    public function create($param = [])
    {
        if ($param != [] && ! is_null($param)) {
            $model = new Role();
            
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
            $model = Role::find($id);
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
            $models = Role::where($whereParam)->get()->all();
            foreach ($models as $model) {
                foreach ($param as $key => $value) {
                    $model->$key = $value;
                }
                $model->save();
            }
        }
        
        return $models;
    }
}