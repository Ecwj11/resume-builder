<?php

namespace App\Repositories\Interfaces;

interface ResumeRepositoryInterface
{
    public function all();

    public function find($id);

    public function findWhere($whereParam = [], $isOne = false);    

    public function create($param = []);

    public function update($id, $param = []);

    public function updateWhere($whereParam = [], $param = []);

    public function newResume($datas);

    public function listing($request, $hashidsHelper);
}