<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Validator;
use Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;

class ResumeController extends Controller
{
	private $firstYear;
    private $endYear;
    
	public function __construct(Request $request) 
    {
        parent::__construct();

        $this->middleware('auth');

        $this->request = $request;

        $this->firstYear = 1980;
        $this->endYear = date('Y');
    }

    public function createResume()
    {
        $this->beginTransaction();
        try {
            $userId = Auth::id();
            $datas = $this->request->all();

            if ($this->request->method() == 'POST' && $this->request->ajax()) {
                $validation = [
                    'email' => 'required',
                    'contact_number' => 'required|min:10',
                    'address' => 'required',
                    'label' => 'required',
                    'name' => 'required',
                    'job_title' => 'required',
                    'description' => 'required',
                    'program.*' => 'required',
                    'institute.*' => 'required',
                    'start_date.*' => 'required',
                    'end_date.*' => 'required',
                    'grade.*' => 'required',
                    'position.*' => 'required',
                    'company.*' => 'required',
                    'we_description.*' => 'required',
                    'we_start_date.*' => 'required',
                    'we_end_date.*' => 'required',
                    'profile_picture'   => 'required|image|mimes:jpeg,jpg,png',
                ];
                $validator = Validator::make($datas, $validation);
                if($validator->fails()){
                    $validatorErrors = $this->processValidatorErrors($validator->errors()->getMessages());
                    return $this->returnError($validatorErrors);                
                }

                $datas['date_now'] = $this->dateNow;

                $profile = $this->request->file('profile_picture');
                $imageName = NULL;
                if(isset($profile)){
	                $imageName = 'profile-' . $this->dateNow . '-' . uniqid() . '.' . $profile->getClientOriginalExtension();

	                if(!Storage::disk('public')->exists('users/' . $userId . '/')){
	                    Storage::disk('public')->makeDirectory('users/' . $userId . '/');
	                }
	                $profileImage = Image::make($profile)->stream();
	                Storage::disk('public')->put('users/' . $userId . '/' . $imageName, $profileImage);
	            }

                //insert into resumes table
                $datas['profile_picture'] = $imageName;
                $resumeId = $this->resumeRepository->newResume($datas);

                //insert into educations table
                $this->educationRepository->newEducation($datas, $resumeId);

                //insert into work_experiences table
                $this->workExperienceRepository->newWorkExperience($datas, $resumeId);

                $this->commit();
                return $this->returnSuccess("Resume created successfully.");
            }
        } catch (\Exception $ex) {
            $this->rollback();
            echo (string) ($ex);exit;
            return $this->returnError($ex->getMessage() . ' (' . $ex->getLine() . ')');
        }
    }

    public function updateResume()
    {
        $this->beginTransaction();
        try {
            $userId = Auth::id();
            $datas = $this->request->all();

            if ($this->request->method() == 'POST' && $this->request->ajax()) {
                $validation = [
                	'id' => 'required|exists:resumes,id',
                    'email' => 'required',
                    'contact_number' => 'required|min:10',
                    'address' => 'required',
                    'label' => 'required',
                    'name' => 'required',
                    'job_title' => 'required',
                    'description' => 'required',
                    'program.*' => 'nullable',
                    'institute.*' => 'nullable',
                    'start_date.*' => 'nullable',
                    'end_date.*' => 'nullable',
                    'grade.*' => 'nullable',
                    'position.*' => 'nullable',
                    'company.*' => 'nullable',
                    'we_description.*' => 'nullable',
                    'we_start_date.*' => 'nullable',
                    'we_end_date.*' => 'nullable',
                ];
                $validator = Validator::make($datas, $validation);
                if($validator->fails()){
                    $validatorErrors = $this->processValidatorErrors($validator->errors()->getMessages());
                    return $this->returnError($validatorErrors);                
                }

                $datas['date_now'] = $this->dateNow;
                $resumeId = $datas['id'];

                $profile = $this->request->file('profile_picture');
                $imageName = NULL;
                if(isset($profile)){
	                $imageName = 'profile-' . $this->dateNow . '-' . uniqid() . '.' . $profile->getClientOriginalExtension();

	                if(!Storage::disk('public')->exists('users/' . $userId . '/')){
	                    Storage::disk('public')->makeDirectory('users/' . $userId . '/');
	                }
	                $profileImage = Image::make($profile)->stream();
	                Storage::disk('public')->put('users/' . $userId . '/' . $imageName, $profileImage);
	            }

                //update resumes table
                $updateParams = [
                	'label' => $datas['label'],
                	'name' => $datas['name'],
		            'job_title' => $datas['job_title'],
		            'email' => $datas['email'],
		            'contact_number' => $datas['contact_number'],
		            'address' => $datas['address'],
		            'description' => $datas['description'],
		            'updated_at' => $this->dateNow,
		            'updated_by' => $userId,
                ];
                if ($imageName != NULL) {
                	$updateParams['profile_picture'] = $imageName;
                }
                $this->resumeRepository->update($resumeId, $updateParams);

                $educations = $this->educationRepository->findWhere([
		            ['resume_id', '=', $resumeId],
		            ['deleted_at', '=', NULL]
		        ]);

                if ($educations) {
		        	foreach ($educations as $education) {
		        		$educationId = $education->id;
		        		if (!isset($datas['program_' . $educationId])) {
		        			$this->educationRepository->update($educationId, [
		        				'deleted_at' => $this->dateNow
		        			]);
		        		} else {
		        			$this->educationRepository->update($educationId, [
		        				'program' => $datas['program_' . $educationId],
				                'institute' => $datas['institute_' . $educationId],
				                'start_date' => $datas['start_date_' . $educationId],
				                'end_date' => isset($datas['end_date_' . $educationId]) ? $datas['end_date_' . $educationId] : null,
				                'grade' => $datas['grade_' . $educationId],
				                'updated_at' => $this->dateNow,
		        			]);
		        		}
		        	}
		        }

		        $workExperiences = $this->workExperienceRepository->findWhere([
		            ['resume_id', '=', $resumeId],
		            ['deleted_at', '=', NULL]
		        ]);

		        if ($workExperiences) {
		        	foreach ($workExperiences as $workExperience) {
		        		$workExperienceId = $workExperience->id;
		        		if (!isset($datas['position_' . $workExperienceId])) {
		        			$this->workExperienceRepository->update($workExperienceId, [
		        				'deleted_at' => $this->dateNow
		        			]);
		        		} else {
		        			$this->workExperienceRepository->update($workExperienceId, [
		        				'position' => $datas['position_' . $workExperienceId],
				                'company' => $datas['company_' . $workExperienceId],
				                'description' => $datas['we_description_' . $workExperienceId],
				                'start_date' => $datas['we_start_date_' . $workExperienceId],
				                'end_date' => isset($datas['we_end_date_' . $workExperienceId]) ? $datas['we_end_date_' . $workExperienceId] : null,
				                'updated_at' => $this->dateNow,
		        			]);
		        		}
		        	}
		        }

                //insert into educations table
                if (isset($datas['program']) && sizeof($datas['program']) > 0 && $datas['program'][0] != '' && $datas['program'][0] != NULL) {
                	$this->educationRepository->newEducation($datas, $resumeId);
                }

                //insert into work_experiences table
                if (isset($datas['position']) && sizeof($datas['position']) > 0 && $datas['position'][0] != '' && $datas['position'][0] != NULL) {
                	$this->workExperienceRepository->newWorkExperience($datas, $resumeId);
                }

                $this->commit();
                return $this->returnSuccess("Resume " . $datas['label'] . " updated successfully.");
            }
        } catch (\Exception $ex) {
            $this->rollback();
            echo (string) ($ex);exit;
            return $this->returnError($ex->getMessage() . ' (' . $ex->getLine() . ')');
        }
    }

    public function deleteResume()
    {
        $this->beginTransaction();
        try {
            $userId = Auth::id();
            $datas = $this->request->all();

            if ($this->request->method() == 'POST' && $this->request->ajax()) {
                $validation = [
                	'id' => 'required|exists:resumes,id'
                ];
                $validator = Validator::make($datas, $validation);
                if($validator->fails()){
                    $validatorErrors = $this->processValidatorErrors($validator->errors()->getMessages());
                    return $this->returnError($validatorErrors);                
                }

                $datas['date_now'] = $this->dateNow;
                $resumeId = $datas['id'];

                //update resumes table
                $updateParams = [
		            'deleted_at' => $this->dateNow,
		            'updated_at' => $this->dateNow,
		            'updated_by' => $userId,
                ];
                $this->resumeRepository->update($resumeId, $updateParams);

                $this->commit();
                return $this->returnSuccess("Resume " . $datas['label'] . " deleted successfully.");
            }
        } catch (\Exception $ex) {
            $this->rollback();
            echo (string) ($ex);exit;
            return $this->returnError($ex->getMessage() . ' (' . $ex->getLine() . ')');
        }
    }

    public function listing()
    {
    	try {
	    	$datas = $this->resumeRepository->listing($this->request, $this->hashidsHelper);	    	

	    	return $datas;
	    } catch (\Exception $ex) {
	    	return $this->returnError($ex->getMessage() . ' (' . $ex->getLine() . ')');
	    }
    }
}