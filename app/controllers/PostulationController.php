<?php 
    use Phalcon\Mvc\Controller;
        class PostulationController extends Controller{

            private $user_id;
            private $user_info_type;
            private $type;

            public function indexAction(){
                if($this->session->has("register"))
                    $this->user_id = $this->session->get("register");
                else{
                    $this->response->redirect('login');
                    return;
                }

                $user_info = Users::findFirst(["id=$this->user_id"]);
                $this->type = $user_info->type;

                if($this->type == "Employee"){
                    $user_info = Employee::findFirst(["user_id=$this->user_id"]);
                    $postulation_data = Postulations::findFirst(["employee_id=$user_info->id"]);
                }
                elseif($this->type == "Company"){
                    $user_info = Company::findFirst(["user_id=$this->user_id"]);
                    $postulation_data = Postulations::findFirst(["company_id=$user_info->id"]);
                }
                
                $user_info_type = $user_info->type;
                
                $job_data = Job::findFirst(["id=$postulation_data->job_id"]);
                foreach($postulation_data as $campo => $value)
                    $offer[$campo] = $value;

                foreach($job_data as $campo => $value)
                    $offer[$campo] = $value;

                $this->view->offer = $offer;
            }
            

            public function createAction(){
                $position = $this->request->getPost("position");
                $description = $this->request->getPost("description");
                $salary = $this->request->getPost("salary");
                $requirements = $this->request->getPost("requirements");

                $job = new Job();
                
                $job->position = $position;
                $job->description = $description;
                $job->salary = $salary;
                $job->requirements = $requirements;
                $job->save();
                
                $postulation = new Postulation();
                $company_id = $user_info_type->id;
                $postulation->company_id = $company_id;
                $postulation->job_id = $job->id;
                $postulation->save();
            }
        }
?>