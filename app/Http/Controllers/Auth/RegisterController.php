<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Redirect;

class RegisterController extends Controller
{
    public function __construct(Request $request) 
    {
        parent::__construct();

        $this->request = $request;
    }

    public function register()
    {
        $this->beginTransaction();
        try {
            if ($this->request->method() == 'POST') {
                $validation = [
                    'email' => 'required|email|unique:App\Models\User,email',
                    'password' => 'required|min:8'
                ];
                $datas = $this->request->all();
                $validator = Validator::make($datas, $validation);

                if ($validator->fails()) {
                    return Redirect::to('register')
                        ->withErrors($validator) // send back all errors to the login form
                        ->withInput($this->request->except('password'));
                }

                $datas['date_now'] = $this->dateNow;
                $this->userRepository->register($datas);

                $this->commit();
                flashy()->success('Registered successfully. Login with your account now.');
                return redirect(route('login'));
            }
        } catch (\Exception $ex) {
            $this->rollback();
            echo (string) $ex;exit;
            flashy()->error($ex->getMessage() . ' (' . $ex->getLine() . ')');
            return redirect(route('register'));
        }
        
    	return view('auth/register');
    }

    public function create()
    {
        $this->beginTransaction();
        try {
            $userId = Auth::id();
            $dateStringNow = date_string_now();
            $datas = $this->request->all();
            $validation = [
                'id_no' => 'required',
                'password' => 'required'
            ];
            $validator = Validator::make($datas, $validation);
            if($validator->fails()){
                $validatorErrors = processValidatorErrors($validator->errors()->getMessages());
                return $this->returnError($validatorErrors);                
            }
            
            if ($exist = $this->accountRepository->findWhere([
                ['user_id', '=', $userId],
                ['id_no', '=', $datas['id_no']],
                ['deleted_at', '=', NULL]
            ], true)) {
                return $this->returnError("Id no (" . $datas['id_no'] . ") exist.");
            }

            $params = [
                'user_id' => $userId,
                'id_no' => $datas['id_no'],
                'password' => $datas['password'],
                'created_by' => $userId,
                'created_at' => $dateStringNow
            ];

            $this->accountRepository->create($params);

            $this->commit();
            return $this->returnSuccess('Added successfully.');
        } catch (\Exception $ex) {
            $this->rollback();
            return $this->returnError($ex->getMessage() . ' (' . $ex->getLine() . ')');
        }
    }
}