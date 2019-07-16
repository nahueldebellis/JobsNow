<?php
use Phalcon\Mvc\Controller;

class SearchController extends Controller{
    public function initialize(){
        $this->assets->addJs('js/search.js');
    }
    public function indexAction(){
        $search_query = $this->request->getJsonRawBody();
        $search_query = $search_query->search;
        
        $out = $this->getSearchData($search_query);        
            
        $this->view->disable();
        $this->response->setContentType('application/json', 'UTF-8');
        echo json_encode($out);
    }    

    public function resultsAction(){
        $this->assets->addCss("css/main_template.css");
        $this->assets->addCss("css/results.css");
        $this->view->setTemplateBefore('main_template');
        $search_query = $this->request->getPost('search');
        $out = $this->getSearchData($search_query);        
        $this->view->search_results = ($out);
    }

    private function getSearchData($search_query){
        $search_result = Users::find([
            "columns" => 'id, name, rotulo, profile_photo',
            "conditions" => "rotulo LIKE '%$search_query%' OR name LIKE '%$search_query%' OR email LIKE '%$search_query%'"
        ]);
        $out = [];
        foreach($search_result as $user){
            array_push($out, $user);
        }
        
        return $out;
    }
}



?>