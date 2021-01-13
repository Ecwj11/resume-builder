<?php

namespace App\Repositories;

use App\Models\WorkExperience;
use App\Repositories\Interfaces\WorkExperienceRepositoryInterface;
use DB;
use Illuminate\Support\Facades\Auth;

class WorkExperienceRepository implements WorkExperienceRepositoryInterface
{
    public function all()
    {
        return WorkExperience::all();
    }
    
    public function find($id)
    {
        return WorkExperience::find($id);
    }
    
    public function findWhere($whereParam = [], $isOne = false)
    {
        if ($isOne) {
            return WorkExperience::where($whereParam)->orderBy('id', 'desc')->get()->first();
        }
        return WorkExperience::where($whereParam)->get()->all();
    }

    public function create($param = [])
    {
        if ($param != [] && ! is_null($param)) {
            $model = new WorkExperience();
            
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
            $model = WorkExperience::find($id);
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
            $models = WorkExperience::where($whereParam)->get()->all();
            foreach ($models as $model) {
                foreach ($param as $key => $value) {
                    $model->$key = $value;
                }
                $model->save();
            }
        }
        
        return $models;
    }

    public function bulkInsert($param = [])
    {
        if ($param != [] && ! is_null($param)) {
            $model = DB::table('work_experiences')->insert($param);
            
            return $model;
        }
    }

    public function newWorkExperience($datas, $resumeId)
    {
        $params = [];
        $size = sizeof($datas['position']);
        for ($i = 0; $i < $size; $i ++) {
            if ($datas['position'][$i] != '' && $datas['position'][$i] != NULL) {
                $params[] = [
                    'resume_id' => $resumeId,
                    'position' => $datas['position'][$i],
                    'company' => $datas['company'][$i],
                    'description' => $datas['we_description'][$i],
                    'start_year' => $datas['we_start_year'][$i],
                    'end_year' => isset($datas['we_end_year'][$i]) ? $datas['we_end_year'][$i] : null,
                    'created_at' => $datas['date_now'],
                ];
            }
        }

        return $this->bulkInsert($params);
    }

    public function getWorkExperiencesByResumeId($resumeId)
    {
        $model = WorkExperience::where([
            ['resume_id', '=', $resumeId],
            ['deleted_at', '=', NULL]
        ])->orderBy('start_year')->get()->all();

        return $model;
    }
}