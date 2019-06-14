<?php
    use Phalcon\Mvc\Controller;

    class RegisterController extends Controller{
        public function indexAction(){

        }

        public function registerAction(){

            $user_type = $this->request->getPost('type');

            $pass = $this->request->getPost('pass');
            $pass = md5($pass);

            $user = new Users();
            
            $user->name = $this->request->getPost('name');
            $user->pass = $pass;
            $user->email = $this->request->getPost('email');
            $user->phone = $this->request->getPost('phone');
            $user->address = $this->request->getPost('address');
            $user->rotulo = $this->request->getPost('rotulo');
            $user->profile_photo = base64_encode(file_get_contents($this->request->getUploadedFiles()[0]->getTempName()));
        

            $success = $user->save();
            $user->refresh();
            if($user_type == "Employee")
                $this->createEmployee($user->id);
            else if($user_type == "Company")
                $this->createCompany($user->id);

            $this->success_status($success, 'Users');  
             
                
        }

        public function companyAction(){
            $this->view->disable();
            $company_data = [
                'nif' => 'text',
                'area' => 'text',
            ];

            $this->response->setContentType('application/json', 'UTF-8');
            echo json_encode($company_data);
        }

        public function employeeAction(){
            $this->view->disable();
            $employee_data = [
                'cv' => 'file',
                'education' => 'text',
                'experience'=> 'text',
            ];

            $this->response->setContentType('application/json', 'UTF-8');
            echo json_encode($employee_data);
        }

        private function createEmployee($user_id){
            echo($user_id);
            $employee = new Employee();
            
            $employee->user_id = $user_id;
            $employee->experience = $this->request->getPost('experience');
            $employee->education = $this->request->getPost('education');
            $employee->cv = base64_encode(file_get_contents($this->request->getUploadedFiles()[1]->getTempName()));

            $success = $employee->save();
            $this->success_status($success, 'employee');
        }

        private function createCompany($user_id){
            $company = new Company();
                
            $company->user_id = $user_id;
            $company->nif = $this->request->getPost('nif');
            $company->area = $this->request->getPost('area');

            $success = $company->save();
            $this->success_status($success, 'company');
        }

        private function success_status($status, $model){
            if (!$status) 
                echo "Sorry, the following problems were generated: ";
            else
                echo "saved $model";
        }

    }
?>