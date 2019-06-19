<?php
    use Phalcon\Mvc\Controller;

    class ProfileController extends Controller{
        public function indexAction(){
            if($this->session->has("register"))
                $user_id = $this->session->get('register');
            else{
                $this->view->disable();
                $this->response->redirect('login');
                return;
            }

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
                'email'=> $user_info->email,
                'phone'=> $user_info->phone,
                'address'=> $user_info->address,
                'rotulo'=> $user_info->rotulo,
                'profile_photo'=> $user_info->profile_photo,
            ];
            foreach($aditional_info as $campo => $value)
                $info[$campo] = $value;
            $this->view->info = $info;
            
        }

        public function closeAction(){
            $this->session->destroy();
            $this->response->redirect('login');
            return;
        }

        public function cvAction(){
            $user_id = $this->request->getQuery('user_id');
            $employee_info = Employee::findFirst([
                "user_id = $user_id",
            ]);

           
            $data = base64_encode($employee_info->cv);

            $this->response->setHeader('Content-Type', 'application/pdf');
            $this->response->setHeader('Content-Disposition', 'filename="filename.pdf"');
            $this->response->setHeader('Content-Transfer-Encoding', 'binary');
            $this->response->setHeader('Accept-Ranges', 'bytes');
            $this->response->setContent($data);
            $this->response->send();
        }

    }
?>