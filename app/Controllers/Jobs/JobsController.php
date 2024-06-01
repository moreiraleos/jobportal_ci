<?php

namespace App\Controllers\Jobs;

use App\Controllers\BaseController;
use App\Models\ApplyedJob\ApplyedJob;
use App\Models\Category\Category;
use App\Models\Job\Job;
use App\Models\SavedJobs\SavedJob;

class JobsController extends BaseController
{

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function singleJob($id): ?string
    {



        $job = new Job();
        $singleJob = $job->find($id);

        // displaying related jobs
        $relatedJobs = $this->db->query("SELECT * FROM jobs WHERE 
                       id != {$id} AND category = '{$singleJob['category']}'
                        ORDER BY id DESC LIMIT 5")->getResult();


        $numRelatedJobs = $this->db->table("jobs")->where("id!=", $id)
            ->where('category', $singleJob['category'])->countAllResults();


        // categories
        $categories = new Category();
        $allcategories = $categories->findAll();

        if (isset(auth()->user()->id)) {
            // checkin for saved jobs
            $checkForSavedJobs = $this->db->table('savedJobs')
                ->where('user_id', auth()->user()->id)
                ->where('job_id', $id)
                ->countAllResults();

            // checkin for applyed jobs
            $checkForApplyedJob = $this->db->table("applyedjobs")
                ->where('user_id', auth()->user()->id)
                ->where('job_id', $id)
                ->countAllResults();
            return view('Jobs/single-job', compact('singleJob', 'relatedJobs', 'numRelatedJobs', 'allcategories', 'checkForSavedJobs', 'checkForApplyedJob'));
        } else {
            return view('Jobs/single-job', compact('singleJob', 'relatedJobs', 'numRelatedJobs', 'allcategories'));
        }
    }

    public function category($id): ?string
    {
        $jobsByCategory = $this->db->query("SELECT * FROM jobs WHERE category = {$id} ORDER BY id DESC")->getResult();

        $category = $this->db->query("SELECT * FROM categories WHERE id = {$id}")->getRow();


        $numRelatedJobs = $this->db->table("jobs")->where('category', $id)->countAllResults();

        return view('jobs/jobs-category', compact('jobsByCategory', 'category'));
    }

    public function saveJobs($id)
    {

        if (!isset(auth()->user()->id)) {
            return redirect()->to(base_url());
        }
        $saveJobs = new SavedJob();

        $data = [
            "user_id" => auth()->user()->id,
            "company_image" => $this->request->getPost('company_image'),
            "title" => $this->request->getPost('title'),
            "company_name" => $this->request->getPost('company_name'),
            "location" => $this->request->getPost('location'),
            "job_type" => $this->request->getPost('job_type'),
            "job_id" => $this->request->getPost('job_id')
        ];

        $saveJobs->save($data);

        if ($saveJobs) {
            return redirect()->to(base_url('jobs/single-jobs/' . $id))->with('save', 'Job saved successfully');
        }
    }

    public function applyJobs($id)
    {
        if (!isset(auth()->user()->id)) {
            return redirect()->to(base_url());
        }
        $applyedJob = new ApplyedJob();

        $data = [
            "user_id" => auth()->user()->id,
            "company_image" => $this->request->getPost('company_image'),
            "title" => $this->request->getPost('title'),
            "company_name" => $this->request->getPost('company_name'),
            "location" => $this->request->getPost('location'),
            "job_type" => $this->request->getPost('job_type'),
            "job_id" => $this->request->getPost('job_id'),
            "cv" => $this->request->getPost('cv'),
            "job_title" => $this->request->getPost('job_title'),
            "email" => auth()->user()->email
        ];

        if ($this->request->getPost('job_title') == "No job title" || $this->request->getPost('cv') == "CV not uploaded yet") {
            return redirect()->to(base_url('jobs/single-jobs/' . $id))->with('error', 'update your job title or CV');
        } else {
            $applyedJob->save($data);
            if ($applyedJob) {
                return redirect()->to(base_url('jobs/single-jobs/' . $id))->with('applyed', 'you applyed for this job successfully');
            }
        }
    }

    public function searchingForJobs()
    {
        $jobs = new Job();

        $title = $this->request->getPost('title');
        $location = $this->request->getPost('location');
        $job_type = $this->request->getPost('job_type');


        $searches = $jobs->like('title', $title)
            ->like('location', $location)
            ->like('job_type', $job_type)
            ->findAll();

        // var_dump($searches);
        // exit;


        return view('jobs/searches', compact('searches', 'title'));
    }
}
