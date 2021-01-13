<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Validator;
use Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
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

    public function home()
    {
        $dataColumns = [
            'no' => '#',
            'label' => 'Label',
            'profile_picture' => 'Profile',
            'created_at' => 'Created At',
            'job_title' => 'Job Title',
            'email' => 'Email',
            'contact_number' => 'Contact Number',
            'address' => 'Address',
            'action' => ''
        ];
        return view('home/resume_list', compact('dataColumns'));
    }

    public function resumeCreate()
    {
        $yearList = [];
        for ($i = $this->endYear; $i >= 1980; $i --) {
            $yearList[$i] = $i;
        }

        return view('home/home', compact('yearList'));
    }

    public function resumeEdit() 
    {
        $resumeId = $this->request->route('resume_id');
        $resume = $this->resumeRepository->find($resumeId);
        if (!$resume) {
            return redirect()->route('home');
        }

        $educations = $this->educationRepository->getEducationsByResumeId($resume->id);

        $workExperiences = $this->workExperienceRepository->getWorkExperiencesByResumeId($resume->id);

        $yearList = [];
        for ($i = $this->endYear; $i >= 1980; $i --) {
            $yearList[$i] = $i;
        }

        return view('home/resume_edit',compact('yearList', 'resume', 'educations', 'workExperiences'));
    }
    
}