<?php
    use Phalcon\Mvc\Controller;

    class LoginController extends Controller{
        public function initialize(){
            
        }
        
        public function indexAction(){
            $this->assets->addCss("css/main_template.css");
            $this->assets->addCss("css/login.css");
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
                $this->flashSession->error("User or password inccorrect");
                $this->response->redirect('login/index');
            }
        }
    }

?>