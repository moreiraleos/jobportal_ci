<?php

namespace App\Controllers\Admins;

use App\Controllers\BaseController;
use App\Models\Admin\Admin;
use App\Models\ApplyedJob\ApplyedJob;
use App\Models\Category\Category;
use App\Models\Job\Job;
use CodeIgniter\HTTP\ResponseInterface;

class AdminsController extends BaseController
{

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }
    public function login()
    {
        return view('admins/login');
    }

    public function checkLogin()
    {
        $session = session();
        $admin = new Admin();
        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');
        $data = $admin->where('email', $email)->first();
        if (!empty($data)) {
            $pass = $data['password'];
            $authenticatePassword = password_verify($password, $pass);
            if ($authenticatePassword) {
                $ses_data = [
                    'id' => $data['id'],
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'isLoggedIn' => TRUE
                ];
                $session->set($ses_data);
                return redirect()->to(base_url("admins/index"));
            } else {
                $session->setFlashdata('msg', 'Password is incorrect');
                return redirect()->to(base_url('admins/login'));
            }
        } else {
            $session->setFlashdata("msg", "Email does not exist.");
            return redirect()->to(base_url('admins/login'));
        }
    }

    public function index()
    {
        $session = session();
        $numJobs = $this->db->table("jobs")->countAllResults();
        $numCategories = $this->db->table('categories')->countAllResults();
        $numAdmins = $this->db->table("admins")->countAllResults();
        $numApps = $this->db->table("applyedjobs")->countAllResults();
        return view('admins/index', compact('session', 'numJobs', 'numCategories', 'numAdmins', 'numApps'));
    }

    public function logout()
    {
        $session = session();
        // $ses_data = [
        //     "id" => "",
        //     "name" => "",
        //     "email" => "",
        //     "isLoggedIn" => FALSE,
        // ];
        // $session->set($ses_data);
        session_destroy();
        return redirect()->to(base_url("admins/login"));
    }

    public function displayAdmins()
    {
        $session = session();
        $admin = new Admin();
        $allAdmins = $admin->findAll();
        return view("admins/all-admins", compact('session', 'allAdmins'));
    }

    public function createAdmins()
    {
        $session = session();
        return view("admins/create-admins", compact("session"));
    }

    public function storeAdmins()
    {
        $admin = new Admin();

        $password = password_hash($this->request->getPost("password"), PASSWORD_DEFAULT);

        $data = [
            "name" => $this->request->getPost("username"),
            "email" => $this->request->getPost("email"),
            "password" => $password
        ];

        $admin->save($data);

        if ($admin) {
            return redirect()->to(base_url("admins/all-admins"))->with("save", "Admin created successfully");
        }
    }

    public function displayCategories()
    {
        $session = session();

        $categories = new Category();

        $allCategories = $categories->findAll();

        return view("admins/all-categories", compact("session", "allCategories"));
    }

    public function createCategories()
    {
        $session = session();
        return view("admins/create-category", compact("session"));
    }

    public function storategories()
    {
        $category = new Category();
        $data = [
            "name" => $this->request->getPost("name"),
        ];

        $category->save($data);
        if ($category) {
            return redirect()->to(base_url("admins/all-categories"))->with("save", "Category created with sucessfully");
        }
    }

    public function editCategories($id)
    {
        $session = session();
        $category = (new Category())->find($id);
        if ($category) {
            return view("admins/edit-categories", compact('category', 'session'));
        }
    }

    public function updateCategories($id)
    {
        $category = new Category();
        $data = [
            "name" => $this->request->getPost("name")
        ];
        $category->update($id, $data);
        if ($category) {
            return redirect()->to(url_to("categories.all"))->with("update", "Category updated successfully");
        }
        return redirect()->to(url_to("categories.all"))->with("error", "An error occured while updating");
    }

    public function deleteCategories($id)
    {
        $category = new Category();
        $category->delete($id);
        if ($category) {
            return redirect()->to(url_to("categories.all"))->with("delete", "Category deleted successfully");
        }
        return redirect()->to(url_to("categories.all"))->with("error", "An error occured while updating");
    }

    public function displayJobs()
    {
        $session = session();
        $job = new Job();
        $jobs =  $job->findAll();

        return view("admins/all-jobs", compact("jobs", "session"));
    }

    public function createJobs()
    {
        $session = session();
        $categories = (new Category())->findAll();
        return view("admins/create-jobs", compact('session', 'categories'));
    }

    public function storeJobs()
    {
        $job = new Job();

        $job_title = $this->request->getVar("job_title");
        $job_region = $this->request->getVar("job_region");
        $job_type = $this->request->getVar("job_type");
        $company = $this->request->getVar("company");
        $vacancy = $this->request->getVar("vacancy");
        $salary = $this->request->getVar("salary");
        $experience = $this->request->getVar("experience");
        $application_deadline = $this->request->getVar("application_deadline");
        $job_description = $this->request->getVar("job_description");
        $responsibilities = $this->request->getVar("responsibilities");
        $education_experience = $this->request->getVar("education_experience");
        $other_benifits = $this->request->getVar("other_benifits");
        $category = $this->request->getPost("category");
        $gender = $this->request->getPost("gender");

        // echo "<pre>";
        // var_dump($this->request->getPost());
        // exit;

        $image = $this->request->getFile("image");
        $image->move("public/assets/images");
        $imageName = $image->getClientName();

        $data = [
            "title" => $job_title,
            "location" =>  $job_region,
            "company_image" => $imageName,
            "company_name" => $company,
            "job_type" => $job_type,
            "vacancy" => $vacancy,
            "experience" => $experience,
            "salary" => $salary,
            "gender" => $gender,
            "category" => $this->request->getPost("category"),
            "application_deadline" => $application_deadline,
            "job_description" => $job_description,
            "responsibilities" => $responsibilities,
            "education_experience" => $education_experience,
            "other_benifits" => $other_benifits,
        ];

        $job->save($data);
        if ($job) {
            return redirect()->to(base_url("admins/all-jobs"))->with("save", "Job created with sucessfully");
        }
    }

    public function deleteJobs($id)
    {
        $job = new Job();
        $jobDelete = $job->find($id);
        if (file_exists("public/assets/images/" . $jobDelete["company_image"])) {
            unlink("public/assets/images/" . $jobDelete["company_image"]);
        }
        $job->delete($id);
        if ($job) {
            return redirect()->to(base_url("admins/all-jobs"))->with("delete", "Job deleted with sucessfully");
        }
    }

    public function displayApps()
    {
        $session = session();
        $apps = new ApplyedJob();
        $allApps = $apps->findAll();
        return view("admins/all-apps", compact('session', 'allApps'));
    }

    public function deleteApps($id)
    {
        $app = new ApplyedJob();
        $appDelete = $app->find($id);

        if (file_exists("public/assets/cvs/" . $appDelete["cv"])) {
           
            unlink("public/assets/cvs/" . $appDelete["cv"]);
        }
        $app->delete($id);
        if ($app) {
            return redirect()->to(base_url("admins/all-apps"))->with("delete", "Application deleted successfully");
        }
    }
}
