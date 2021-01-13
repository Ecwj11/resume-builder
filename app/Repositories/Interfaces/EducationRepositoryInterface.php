<?php

namespace App\Repositories\Interfaces;

interface EducationRepositoryInterface
{
    public function all();

    public function find($id);

    public function findWhere($whereParam = [], $isOne = false);    

    public function create($param = []);

    public function update($id, $param = []);

    public function updateWhere($whereParam = [], $param = []);

    public function bulkInsert($param = []);

    public function newEducation($datas, $resumeId);

    public function getEducationsByResumeId($resumeId);
}