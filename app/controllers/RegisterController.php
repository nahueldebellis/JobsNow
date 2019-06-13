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
            $user->profile_photo = $this->request->getPost('profile_photo');

            $success = $user->save();

            $this->success_status($success, 'Users');   
            if($user_type == "Employee")
                $this->dispatcher();  
//                $this->createEmployee($user->id);
            else if($user_type == "Company")
                $this->createCompany($user->id);
                
        }

        public function companyAction(){
            $this->view->disable();
            $company_data = [
                'nif' => 'text',
                'area' => 'text',
            ];
            
            $this->response->setContentType('application/json', 'UTF-8');
            $this->response->setContent(json_encode($company_data));
        }

        public function employeeAction(){
            $this->view->disable();
            $employee_data = [
                'cv' => 'file',
                'education' => 'text',
                'experience'=> 'text',
            ];

            $this->response->setContentType('application/json');
            $this->response->setContent(json_encode($employee_data));
        }

        private function createEmployee($user_id){

            $employee = new Employee();
            
            $employee->user_id = $user_id;
            $employee->nif = $this->request->getPost('experience');
            $employee->area = $this->request->getPost('education');
            $employee->cv = $this->request->getPost('cv');

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