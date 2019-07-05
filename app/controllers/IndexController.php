<?php
    use Phalcon\Mvc\Controller;

    class IndexController extends Controller
    {
        public function initialize(){
            $this->assets->addCss("css/main_page.css");
        }
        
        public function indexAction()
        {
            
        }
    }
?>
