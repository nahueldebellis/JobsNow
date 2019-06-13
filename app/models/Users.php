<?php
    use Phalcon\Mvc\Model;

    class Users extends Model{
        public $id;
        public $name;
        public $email;
        public $phone;
        public $address;
        public $rotulo;
        public $profile_photo;
        public $pass;
    }
?>