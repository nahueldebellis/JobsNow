<?php
    use Phalcon\Mvc\Controller;

    class EmployeeController extends Controller{
        public function indexAction(){
            $employee_id = $this->request->getQuery('id', 'int'); // levanta el id de un get y lo guarda 
            $employee = Employee::findFirst($employee_id); // busca en la bd al employee con el id anterior
            $user = Users::findFirst($employee->id_user); // busca al user correspondiente al employee anterior


            // lo muestra en view
            $this->view->info = [
                'user' => $user,
                'employee' => $employee,        
            ];
        }

        public function updateCvAction(){

        }

        public function updatePersonalData(){

        }

    }

?>