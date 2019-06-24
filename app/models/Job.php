<?php
    use Phalcon\Mvc\Model;
    
    class Job extends Model{
        public $id;
        public $position;
        public $description;
        public $salary;
        public $company_id;
        public $employee_id;
        public $requirements;
    }
?>