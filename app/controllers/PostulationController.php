<?php 
    use Phalcon\Mvc\Controller;
        class PostulationController extends Controller{
            public function indexAction(){
                if($this->session->has("register"))
                    $user_id = $this->session->get("register");
                else{
                    $this->response->redirect('login');
                    return;
                }

                $user_info = Users::findFirst(["id=$user_id"]);
                $type = $user_info->type;

                if($type == "Employee")
                    $job_data = Job::find();
                elseif($type == "Company"){
                    $user_info = Company::findFirst(["user_id=$user_id"]);
                    $job_data = Job::find("company_id=$user_info->id");
                    $this->view->create = "/JobsNow/postulation/create";
                    $this->view->company_id = $user_info->id;
                }
                
            
                $job_number = 0;
                foreach($job_data as $jobs){
                    foreach($jobs as $campo => $value)
                        if($campo != "employee_id")
                        $offer[$job_number][$campo] = $value;
                    $job_number++;
                }

                $this->view->offer = $offer;
            }

            public function applyAction(){
                if($this->session->has("register"))
                    $user_id = $this->session->get("register");
                else{
                    $this->response->redirect('login');
                    return;
                }

                $employee_id = Employee::findFirst([
                    'columns' => ['id'],
                    'conditions' => "user_id = $user_id"
                ]);

                $employee_id = $employee_id['id'];

                $job_id = $this->request->getPost('id');
                
                $postulation_exists = Postulations::findFirst([
                    "employee_id = $employee_id AND job_id = $job_id"
                ]);
                
                if(!$postulation_exists){
                    $postulation = new Postulations();
        
                    $postulation->job_id = $job_id;
                    $postulation->employee_id = $employee_id;
                    $postulation->save();
                    
                    $this->response->redirect('Postulation');
                    return;
                }
                else{
                    echo 'Error: You had already applied for this job';
                }
            }

            public function showAction(){
                if($this->session->has("register"))
                    $user_id = $this->session->get("register");
                else{
                    $this->response->redirect('login');
                    return;
                }

                $job_id = $this->request->getPost('id');
                $job_postulations = Postulations::find([
                    'conditions' => "job_id = $job_id"
                ]);
                $postulants = [];
                foreach($job_postulations as $job){
                    $employee = Employee::findFirst("id = $job->employee_id");
                    $user = Users::findFirst([
                        'columns' => ['name', 'email', 'rotulo'],
                        'conditions' => "id = $employee->user_id"
                    ]);
                    array_push($postulants, $user);
                    
                }
                $this->view->postulation = $postulants;
            }

            public function createAction(){
                $company_id = $this->request->getPost("id");
                $position = $this->request->getPost("position");
                $description = $this->request->getPost("description");
                $salary = $this->request->getPost("salary");
                $requirements = $this->request->getPost("requirements");

                $job = new Job();
                
                $job->position = $position;
                $job->description = $description;
                $job->salary = $salary;
                $job->requirements = $requirements;
                $job->company_id = $company_id;
                $job->state = "Active";

                $job->save();
            }
        }
?>