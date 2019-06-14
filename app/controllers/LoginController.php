<?php
    use Phalcon\Mvc\Controller;

    class LoginController extends Controller{
        public function indexAction(){
            
        }


        public function loginAction(){
            $email = $this->request->getPost('email');
            $pass = $this->request->getPost('pass');
            $pass = md5($pass);
            $user_exist = Users::find([
                "email = '$email' AND pass = '$pass'"
            ]);
            if(count($user_exist) != 0){
                echo "Profile";
            }
            else{
                echo 'User not exist';
            }
        }
    }

?>