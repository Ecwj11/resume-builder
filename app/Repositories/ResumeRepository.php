<?php

namespace App\Repositories;

use App\Models\Resume;
use App\Repositories\Interfaces\ResumeRepositoryInterface;
use DB;
use Illuminate\Support\Facades\Auth;

class ResumeRepository implements ResumeRepositoryInterface
{
    public function all()
    {
        return Resume::all();
    }
    
    public function find($id)
    {
        return Resume::find($id);
    }
    
    public function findWhere($whereParam = [], $isOne = false)
    {
        if ($isOne) {
            return Resume::where($whereParam)->orderBy('id', 'desc')->get()->first();
        }
        return Resume::where($whereParam)->get()->all();
    }

    public function create($param = [])
    {
        if ($param != [] && ! is_null($param)) {
            $model = new Resume();
            
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
            $model = Resume::find($id);
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
            $models = Resume::where($whereParam)->get()->all();
            foreach ($models as $model) {
                foreach ($param as $key => $value) {
                    $model->$key = $value;
                }
                $model->save();
            }
        }
        
        return $models;
    }

    public function newResume($datas)
    {
        $userId = Auth::id();
        $params = [
            'user_id' => $userId,
            'label' => $datas['label'],
            'name' => $datas['name'],
            'job_title' => $datas['job_title'],
            'email' => $datas['email'],
            'contact_number' => $datas['contact_number'],
            'address' => $datas['address'],
            'description' => $datas['description'],
            'profile_picture' => $datas['profile_picture'],
            'created_at' => $datas['date_now'],
            'created_by' => $userId
        ];

        return $this->create($params);
    }

    public function listing($request, $hashidsHelper) 
    {
        $draw = $request->input("draw");
        $start = $request->input("start");
        $length = $request->input("length");
        $searchValue = $request->input("search")["value"];

        $searchSql = "";
        $params = [Auth::id()];

        $order = $request->input("order");
        $columns = $request->input("columns");
        $counter = 0;
        $orderSql = ' ORDER BY ';
        if ($order) {
            foreach ($order as $key) {
                $orderDir = $key['dir'];
                $orderColumn = $columns[$key['column']]['data'];
                $orderSql = $orderSql . $orderColumn . ' ' . $orderDir;

                $counter++;
                if ($counter < sizeof($order)) {
                    $orderSql = $orderSql . ', ';
                }
            }
        }

        if ($orderSql == ' ORDER BY ') {
            $orderSql = $orderSql . ' resumes.id DESC';
        }

        $resumeEditUrl = route('resumeEdit');
        $columns = [
            'resumes.id',
            'resumes.id AS no',
            'resumes.label',
            "DATE_FORMAT(resumes.created_at, '%Y-%m-%d %H:%i %p') AS created_at",
            "resumes.job_title",
            'resumes.email',
            'resumes.contact_number',
            'resumes.address',
            "CONCAT('<a href=\"javascript:void(0);\" data-href=\"/storage/users/', resumes.user_id, '/', resumes.profile_picture, '\" onclick=\"viewImage(this);\"><img src=\"/storage/users/', resumes.user_id, '/', resumes.profile_picture, '\" width=\"100px\"></a>') AS profile_picture",
            "CONCAT('
            <a href=\"$resumeEditUrl/', resumes.id, '\" onclick=\"edit(this);\" target=\"_blank\"><i class=\"fa fa-edit text-warning\"></i></a>&nbsp;
            <a href=\"javascript:void(0);\" onclick=\"share(this);\" data-url=\"_share_url_\"><i class=\"fa fa-share\"></i></a>&nbsp;
            <a href=\"javascript:void(0);\" onclick=\"deleteResume(this);\" data-id=\"', resumes.id, '\" data-label=\"', resumes.label, '\"><i class=\"fa fa-trash text-danger\"></i></a>
            ') AS action"
        ];

        $sqlCol = implode(',', $columns);
        if ($searchValue) {
            $searchSql .= " AND ( " ;
            $i = 0;
            foreach ($columns as $col) {
                if (strpos($col, ' AS ') !== false) {
                    $cols = explode(' AS ', $col);
                    if (in_array($cols[1], ['no', 'status'])) {
                        continue;
                    }
                }
                $searchKey = ':searchValue' . $i;
                $searchSql .= "$col LIKE $searchKey OR ";

                $param[$searchKey] = "%$searchValue%";
                $i ++;
            }                
            $searchSql = trim($searchSql, 'OR ');
            $searchSql .= ") ";
        }

        $sql = "SELECT $sqlCol 
                FROM resumes               
                WHERE resumes.deleted_at IS NULL AND resumes.user_id = ?
                $searchSql 
                $orderSql";

        if ($length > 0) {
            $sql .= " LIMIT $length OFFSET $start";
        }
        
        $datas = DB::select($sql, $params);

        $size = sizeof($datas);

        $sql = "SELECT COUNT(resumes.id) AS recordsFiltered 
                FROM resumes
                WHERE resumes.deleted_at IS NULL
                $searchSql";
        $recordsFiltered = (int) DB::select($sql, $params)[0]->recordsFiltered;

        $m = '';
        $diff = "";
        for ($i = 0; $i < $size; $i ++) {
            $data = $datas[$i];
            $datas[$i]->no = $i + 1;
            $token = $hashidsHelper->encodeHex($data->id);
            $action = $data->action;
            $viewResumeUrl = route('viewResume');
            $datas[$i]->action = str_replace('_share_url_', $viewResumeUrl . '/' . $token, $action);
        }

        return array(
            "draw" => $draw + 1,
            "recordsTotal" => $recordsFiltered,
            "recordsFiltered" => $recordsFiltered,
            "data" => $datas
        );
    }
}