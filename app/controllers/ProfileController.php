<?php
    use Phalcon\Mvc\Controller;

    class ProfileController extends Controller{
        public function initialize(){
            $this->assets->addCss('css/main_template.css');
            $this->assets->addJs('js/search.js');
            $this->view->setTemplateBefore('main_template');
        }

        public function indexAction(){
            $user_id = $this->checkRegister();
            $info = $this->getProfileData($user_id);

            $this->assets->addCss('css/profile.css');
            $this->view->edit_data = "/JobsNow/profile/update";
            $this->view->info = $info;
        }

        private function checkRegister(){
            if($this->session->has("register"))
                $user_id = $this->session->get('register');
            else{
                $this->view->disable();
                $this->response->redirect('login');
                return;
            }
            return $user_id;
        }


        public function generalAction(){
            $this->assets->addCss("css/profile.css");
            $user_actual = $this->checkRegister();
            $user_id = $this->request->getQuery('id');
            if($user_actual !== $user_id){
                $info = $this->getProfileData($user_id);
                $this->view->info = $info;
            }
            else{
                $this->view->disable();
                $this->response->redirect('profile');
                return;
            }
        }

        public function updateAction(){
            $user_id = $this->checkRegister();
            $this->assets->addCss('css/profile_update.css');
            
            $user = Users::findFirst([
                "columns" => ["name", "type", "email", "rotulo", "address", "phone"],
                "id=$user_id"   
            ]);

            $type = $user->type;

            if($type == "Employee")
                $aditional_info = ['cv' => 'file', 'experience' => 'text', 'education' => 'text']; ///ta mal, ta hardcodeado
            elseif($type == "Company")
                $aditional_info = ['nif' => 'text', 'area' => 'text']; /// ta mal, ta hardcodeado
            
            $this->view->user_info = $user;
            $this->view->aditional_info = $aditional_info;
        }


        public function editAction(){
            $user_id = $this->checkRegister();
            $employee = ['cv', 'experience', 'education'];
            $company = ['nif', 'area'];
            $user = ['name', 'email', 'phone', 'address', 'rotulo'];

            $req = $this->request->getPost();//getRawBody();
            $files = $this->request->getUploadedFiles();
            
            if($files[0]->getName() !== ""){
                $user_model = Users::findFirstById($user_id);
                $user_model->profile_photo = base64_encode(file_get_contents($files[0]->getTempName()));
                $user_model->update();         
            }
            if(isset($files[1]) && $files[1]->getName() !== ""){
                $employee_model = Employee::findFirst("user_id=$user_id");
                $employee_model->cv = base64_encode(file_get_contents($files[1]->getTempName()));
                $employee_model->update();         
            }
            
            foreach($req as $edit_type => $edit_data){
                echo "$edit_type-> $edit_data <br>";
                if($edit_data != Null || $edit_data != ''){
                    if($edit_type == 'pass'){
                        $edit_data = md5($edit_data);
                        $this->updateUser($user_id, $edit_type, $edit_data);
                    }
                    elseif(in_array($edit_type, $user))
                        $this->updateUser($user_id, $edit_type, $edit_data);
                    elseif(in_array($edit_type, $employee))
                        $this->updateEmployee($user_id, $edit_type, $edit_data);
                    elseif(in_array($edit_type, $company))
                        $this->updateCompany($user_id, $edit_type, $edit_data);
                }
            }

            $this->response->redirect('profile');
        }
        
        private function updateCompany($user_id, $company_edit, $edit_data){
            $company = Company::findFirst("user_id=$user_id");
            $company->$company_edit = $edit_data;
            $company->update();
        }

        private function updateEmployee($user_id, $employee_edit, $edit_data){
            $employee = Employee::findFirst("user_id=$user_id");
            $employee->$employee_edit = $edit_data;
            $employee->update();
        }

        private function updateUser($user_id, $user_edit, $edit_data){
            $user = Users::findFirstById($user_id);
            $user->$user_edit = $edit_data;
            $user->update();
        }

        private function getProfileData($user_id=0){
            $user_info = Users::findFirst([
                "id = $user_id" 
            ]);

            if($user_info->type == "Employee")
                $aditional_info = Employee::findFirst(["user_id = $user_id"]);
            elseif($user_info->type == "Company")
                $aditional_info = Company::findFirst(["user_id = $user_id"]);
            
            
            $info = [
                'id' => $user_info->id,
                'name' => $user_info->name,
                'rating' => $user_info->rating,
                'email'=> $user_info->email,
                'phone'=> $user_info->phone,
                'address'=> $user_info->address,
                'rotulo'=> $user_info->rotulo,
                'profile_photo'=> $user_info->profile_photo,
            ];
            foreach($aditional_info as $campo => $value)
                $info[$campo] = $value;
            
            return $info;
        }

        public function closeAction(){
            $this->session->destroy();
            $this->response->redirect('login');
            return;
        }

        public function cvAction(){
            $user_id = $this->request->getQuery('id');
            $employee_info = Employee::findFirst([
                "id = $user_id",
            ]);
            
            $data = base64_decode($employee_info->cv);

            $file = 'files/curriculum.pdf';
            file_put_contents($file, $data);

            if (file_exists($file)) {
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="'.basename($file).'"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($file));
                readfile($file);
                exit;       
            }
        }

    }
?>