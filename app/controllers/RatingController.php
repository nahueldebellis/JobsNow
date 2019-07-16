<?php
use Phalcon\Mvc\Controller;

class RatingController extends Controller{
    public function indexAction(){

    }

    public function rateAction(){
        
        $emitter_id = $this->session->get('register');

        $emitter_user = Users::findFirstById($emitter_id); //da puntos
        $id = $this->request->getQuery("id");
        $user = Users::findFirstById($id); //recibe puntos
        if($emitter_id !== $id){
            $user->rating += $emitter_user->rating * 0.5;
            $user->update();
        }
        $this->response->redirect('profile/general?id='.$id);
    }

}



?>