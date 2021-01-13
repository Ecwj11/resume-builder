<?php

namespace App\Repositories;

use App\Models\Education;
use App\Repositories\Interfaces\EducationRepositoryInterface;
use DB;
use Illuminate\Support\Facades\Auth;

class EducationRepository implements EducationRepositoryInterface
{
    public function all()
    {
        return Education::all();
    }
    
    public function find($id)
    {
        return Education::find($id);
    }
    
    public function findWhere($whereParam = [], $isOne = false)
    {
        if ($isOne) {
            return Education::where($whereParam)->orderBy('id', 'desc')->get()->first();
        }
        return Education::where($whereParam)->get()->all();
    }

    public function create($param = [])
    {
        if ($param != [] && ! is_null($param)) {
            $model = new Education();
            
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
            $model = Education::find($id);
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
            $models = Education::where($whereParam)->get()->all();
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
            $model = DB::table('educations')->insert($param);
            
            return $model;
        }
    }

    public function newEducation($datas, $resumeId)
    {
        $params = [];
        $size = sizeof($datas['program']);
        for ($i = 0; $i < $size; $i ++) {
            if ($datas['program'][$i] != '' && $datas['program'][$i] != NULL) {
                $params[] = [
                    'resume_id' => $resumeId,
                    'program' => $datas['program'][$i],
                    'institute' => $datas['institute'][$i],
                    'start_date' => $datas['start_date'][$i],
                    'end_date' => isset($datas['end_date'][$i]) ? $datas['end_date'][$i] : null,
                    'grade' => $datas['grade'][$i],
                    'created_at' => $datas['date_now'],
                ];
            }
        }

        return $this->bulkInsert($params);
    }

    public function getEducationsByResumeId($resumeId)
    {
        $model = Education::where([
            ['resume_id', '=', $resumeId],
            ['deleted_at', '=', NULL]
        ])->orderBy('start_date')->get()->all();

        return $model;
    }
}