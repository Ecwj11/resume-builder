<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\App;
use DB;
use Auth;
use Carbon\Carbon;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $educationRepository;
    public $resumeRepository;
    public $roleRepository;
    public $userRepository;
    public $workExperienceRepository;

    public $hashidsHelper;

    public $transactionBegan;
    public $dateNow;

    public function __construct() 
    {
    	$this->educationRepository = App::make("App\Repositories\EducationRepository");
    	$this->resumeRepository = App::make("App\Repositories\ResumeRepository"); 
    	$this->roleRepository = App::make("App\Repositories\RoleRepository");
    	$this->userRepository = App::make("App\Repositories\UserRepository");
    	$this->workExperienceRepository = App::make("App\Repositories\WorkExperienceRepository");

    	$this->hashidsHelper = App::make("App\Helpers\HashidsHelper");

    	$this->transactionBegan = false;

    	$this->dateNow = Carbon::now();
    }

    public function beginTransaction() {
    	DB::beginTransaction();
    	$this->transactionBegan = true;
    }

    public function commit() {
    	DB::commit();
    	$this->transactionBegan = false;
    }

    public function rollback() {
    	DB::rollback();
    	$this->transactionBegan = false;
    }

    public function insertLog($logType, $source, $log, $content, $userId = null) 
    {
        $cdate = $this->dateNow;
        $content = (is_null($content) || $content == "") ? str_replace(" ", "", str_replace("\n", "", request()->getContent())) : $content;
        $content = is_array($content) ? json_encode($content) : $content;
        $header = str_replace(" ", "", str_replace("\r\n", ";", json_encode(getallheaders())));
        $actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $ip = isset($_SERVER["HTTP_CF_CONNECTING_IP"]) ? $_SERVER["HTTP_CF_CONNECTING_IP"] : $_SERVER["REMOTE_ADDR"];    

        $data = [
            "user_id" => $userId,
            "log_type" => $logType,
            "log" => $log,
            "header" => $header,
            "body" => $content,
            "url" => $actual_link,
            "source" => $source,
            "ip_address" => $ip,
            "created_at" => $cdate,
        ];

        $logId = DB::table('logs')->insertGetId($data);
        if (isset($this->beganTransaction) && $this->beganTransaction) {
            DB::commit();
        }
        return true;
    }

    public function returnSuccess($data) {
		$response = ['data' => $data, 'status' => true];
		return response($response);
	}

	public function returnError($errorMessage, $key = 'error') {
		if ($key != 'error') {
			$error = (object) [$key => $errorMessage, "error" => $errorMessage];
		} else {
			$error = (object) ["error" => $errorMessage];
		}
        $response = ['errors' => $error, 'status' => false];
        return response($response, 422);
	}

	public function processValidatorErrors($errors) {
	    $validatorErrors = [];
	    $i = 0;
	    foreach($errors as $key => $value) {
	        if ($i == 0) {
	            $validatorErrors['error'] = $value[0];
	        }
	        $validatorErrors[$key] = $value[0];
	        $i ++;
	    }    

	    return (object) $validatorErrors;
	}
}
