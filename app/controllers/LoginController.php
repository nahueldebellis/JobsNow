<?php
    use Phalcon\Mvc\Controller;

    class LoginController extends Controller{
        public function indexAction(){
            
        }


        public function loginAction(){
            $email = $this->request->getPost('email');
            $pass = $this->request->getPost('pass');
            $pass = md5($pass);
            if($user_exist = Users::findFirst($email, $pass)){
                /// ok
            }
            else{
                echo 'User not exist';
            }
        }
    }

?>