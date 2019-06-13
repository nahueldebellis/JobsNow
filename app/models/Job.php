<?php
    use Phalcon\Mvc\Model;
    
    class Job extends Model{
        public $id;
        public $position;
        public $description;
        public $salary;
        public $requeriments;
        public $state;
    }
?>