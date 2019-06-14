<?php
    use Phalcon\Mvc\Controller;

    class ProfileController extends Controller{
        public function indexAction(){
            $user_id = $this->request->getQuery('user_id');
            $user_info = Users::findFirst([
                "id = $user_id" 
            ]);

            //$this->view->disable();
            $this->view->info = [
                'name' => $user_info->name,
                'email'=> $user_info->email,
                'phone'=> $user_info->phone,
                'address'=> $user_info->address,
                'rotulo'=> $user_info->rotulo,
                'profile_photo'=> $user_info->profile_photo
            ];
        }

        public function imageAction(){
            $user_id = $this->request->getQuery('user_id');
            $user_info = Users::findFirst([
                "id = $user_id" 
            ]);

            $this->response->setHeader("Content-Type", "image/jpeg");
            $this->response->setContent(base64_decode( $user_info->profile_photo ));
        }

    }
?>