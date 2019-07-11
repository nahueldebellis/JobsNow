<?php 
    use Phalcon\Mvc\Controller;
    class PostulationController extends Controller{
        public function initialize(){
            $this->assets->addCss('css/main_template.css');
            $this->view->setTemplateBefore('main_template');
            $this->assets->addCss("css/postulation.css");
        }

        public function indexAction(){ //show available jobs
            if($this->session->has("register"))
                $user_id = $this->session->get("register");
            else{
                $this->response->redirect('login');
                return;
            }

            $this->assets->addCss('css/postulation.css');

            $user_info = Users::findFirst(["id=$user_id"]);
            $type = $user_info->type;
            if($type == "Employee")
                $job_data = Job::find('employee_id is NULL');
            elseif($type == "Company"){
                $user_info = Company::findFirst(["user_id=$user_id"]);
                $job_data = Job::find("company_id=$user_info->id");
                $this->view->create = "/JobsNow/postulation/create";
                $this->view->company_id = $user_info->id;
            }
        
            $job_number = 0;
            foreach($job_data as $jobs){
                $company = Company::findFirst("id = $jobs->company_id");
                $company_name = Users::findFirst([
                    'columns' => ['name'],
                    "id = $company->user_id"
                ]);
                $offer[$job_number]["company_name"] = $company_name->name;
                foreach($jobs as $campo => $value)
                    if($campo != "employee_id")
                        $offer[$job_number][$campo] = $value;
                $job_number++;
            }

            $this->view->offer = $offer;
        }

        public function directAction(){
            $this->assets->addCss("css/postulation.css");
            if($this->session->has("register"))
                $user_id = $this->session->get("register");
            else{
                $this->response->redirect('login');
                return;
            }

            $employee = Employee::findFirst("user_id=$user_id");
            
            $all_postulations_accepted = [];
            $postulation_number = 0;
            
            $job_postulant = Job::find([
                'columns' => ['id', 'position', 'description', 'requirements', 'salary', 'company_id', 'employee_id'],
                "employee_id=$employee->id"
            ]);

            foreach($job_postulant as $job){
                foreach($job as $job_data_key=> $job_data_value){
                    $company = Company::findFirstById($job->company_id);
                    $user_name = Users::findFirstById($company->user_id);
                    $all_postulations_accepted[$postulation_number]['name'] = $user_name->name;
                    $all_postulations_accepted[$postulation_number][$job_data_key] = $job_data_value;
                }
                $postulation_number++;
            }

            $this->view->postulations = $all_postulations_accepted;
            
        }

        public function hireAction(){
            if($this->session->has("register"))
                $user_id = $this->session->get("register");
            else{
                $this->response->redirect('login');
                return;
            }

            $employee = $this->request->getPost('employee');
            $job_id = $this->request->getPost('job');

            $job = Job::findFirst("id=$job_id");

            $job->employee_id = $employee;
            $job->update();
            $this->response->redirect('postulation');
            return;
        }

        public function acceptAction(){
            if($this->session->has("register"))
                $user_id = $this->session->get("register");
            else{
                $this->response->redirect('login');
                return;
            }

            $job_id = $this->request->getPost('id');
            $job = Job::findFirstById($job_id);
            $job->state = 'Unactive';
            $job->update();
            
            $this->response->redirect('direct');
            return;
        }

        public function applyAction(){ // apply to one job and create postulation
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

        public function showAction(){ //show actual postulants to one job
            if($this->session->has("register"))
                $user_id = $this->session->get("register");
            else{
                $this->response->redirect('login');
                return;
            }
            $job_id = $this->request->getPost('id');//aca esta el trabajo
            $job_active = Job::findFirst("id=$job_id");
            if($job_active->state == 'Active'){
                $job_postulations = Postulations::find([
                    'conditions' => "job_id = $job_id"
                ]);
            }
            else{
                $job_postulations[0]->employee_id = $job_active->employee_id;
                $this->view->official = true;
            }
            

            $postulants = [];
            foreach($job_postulations as $job){
                $employee = Employee::findFirst("id = $job->employee_id");
                $user = Users::findFirst([
                    'columns' => ['name', 'email', 'rotulo'],
                    'conditions' => "id = $employee->user_id"
                ]);
                $arr = ['employee_id' => $employee->id];
                array_push($postulants, $arr);
                array_push($postulants, $user);
            }

            $this->view->job = $job_id;
            $this->view->postulation = $postulants;
        }

        public function createAction(){ //create job for a company and direct offer
            $company_id = $this->request->getPost("id");
            $position = $this->request->getPost("position");
            $description = $this->request->getPost("description");
            $salary = $this->request->getPost("salary");
            $requirements = $this->request->getPost("requirements");
            
            $postulant = $this->request->getPost("postulant");

            $job = new Job();
            
            $job->position = $position;
            $job->description = $description;
            $job->salary = $salary;
            $job->requirements = $requirements;
            $job->company_id = $company_id;
            $job->state = "Active";

            if($postulant){ //direct offer
                $user = Users::findFirst("email='$postulant'");
                $employee = Employee::findFirst("user_id=$user->id");
                $job->employee_id = $employee->id;
            }

            $job->save();
        }
    }
?>