<?php
    use Phalcon\Mvc\Controller, Model;

    class ProfileController extends Controller{
        public function indexAction(){
            $user_id = $this->request->getQuery('user_id');
            $user_info = Users::findFirst([
                "id = $user_id" 
            ]);

            //if($user_info->type == "Employee"){
            $aditional_info = Employee::findFirst([
                "user_id = $user_id",
            ]);
            /*}
            elseif($user_info->type == "Company"){
                $aditional_info = Company::findFirst([
                    "user_id = $user_id",
                ]);
            }*/
            $this->view->info = [
                'id' => $user_info->id,
                'name' => $user_info->name,
                'email'=> $user_info->email,
                'phone'=> $user_info->phone,
                'address'=> $user_info->address,
                'rotulo'=> $user_info->rotulo,
                'profile_photo'=> $user_info->profile_photo,
            ];

            foreach($aditional_info as $value){
                $this->view->info["$campo"] = $value;
            }

            //$this->view->disable();

        }

        public function cvAction(){
            $user_id = $this->request->getQuery('user_id');
            $employee_info = Employee::findFirst([
                "user_id = $user_id",
            ]);

           
            $data = base64_decode($employee_info->cv);

            $this->response->setHeader('Content-Type', 'application/pdf');
            $this->response->setHeader('Content-Disposition', 'attachment; filename="filename.pdf"');
            $this->response->setHeader('Content-Transfer-Encoding', 'binary');
            $this->response->setHeader('Accept-Ranges', 'bytes');
            $this->response->setContent($data);
            $this->response->send();
            
            /*$this->response->setHeader("Content-Type", "application/pdf");
            $this->response->setHeader("Content-Disposition", 'attachment; filename="filename.pdf"');
            $file = $employee_info->cv;
            $this->response->setFileToSend($file);*/
        }

    }
?>