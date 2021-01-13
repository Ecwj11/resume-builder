<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Validator;
use Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class ResumeController extends Controller
{
    private $firstYear;
    private $endYear;

	public function __construct(Request $request) 
    {
        parent::__construct();

        $this->request = $request;
    }

    public function viewResume() 
    {
        $token = $this->request->route('token');
        $tokenArr = $this->hashidsHelper->decodeHex($token);
        
        if (!isset($tokenArr[0])) {
            $error = "Invalid resume token.";
            return view('home/resume_error', compact('error'));
        }

        $resume = $this->resumeRepository->find($tokenArr);
        if (!$resume) {
            $error = "Resume not found.";
            return view('home/resume_error', compact('error'));
        }

        $educations = $this->educationRepository->getEducationsByResumeId($resume->id);

        $workExperiences = $this->workExperienceRepository->getWorkExperiencesByResumeId($resume->id);

        $yearList = [];
        for ($i = $this->endYear; $i >= 1980; $i --) {
            $yearList[$i] = $i;
        }

        return view('home/resume_view_public',compact('yearList', 'resume', 'educations', 'workExperiences'));
    }
    
}