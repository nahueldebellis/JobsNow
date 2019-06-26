<?php
    use Phalcon\Mvc\Controller;

    class LoginController extends Controller{
        public function initialize(){
            
        }
        
        public function indexAction(){
            if($this->session->has("register")){
                $this->view->disable();
                $this->response->redirect('profile');
            }
        }

        public function loginAction(){
            $email = $this->request->getPost('email');
            $pass = $this->request->getPost('pass');
            $pass = md5($pass);
            $user_exist = Users::find([
                "email = '$email' AND pass = '$pass'"
            ]);
            if(count($user_exist) != 0){
                $user_info = $user_exist[0];
                $this->session->set("register", "$user_info->id");
                $this->response->redirect('profile');
                $this->view->disable();
            }
            else{
                echo 'User not exist';
            }
        }
    }

?>