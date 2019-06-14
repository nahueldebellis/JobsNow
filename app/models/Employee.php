<?php
    use Phalcon\Mvc\Model;

    class Employee extends Model{
        public $id;
        public $user_id;
        public $experience;
        public $education;
        public $cv;
    }
?>