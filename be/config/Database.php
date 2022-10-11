<?php
class Database {

    private $host = 'localhost';
    private $db_name = 'todo';
    private $db_user = 'dp';
    private $db_password = 'dp123';
    private $db;

    public function connect() {

        $this->db = null;
        try {
            $this->db = new PDO('mysql:host='.$this->host.';dbname='.$this->db_name,$this->db_user, $this->db_password);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//            print_r("DB CONNECTED"."\n\n");
//            print_r('mysql:host='.$this->host.';'.$this->db_name." | ".$this->db_user." | ".$this->db_password."\n\n");
//            print_r($this->db);
            return $this->db;
        } catch (PDOException $e) {
            echo 'Connection ERROR'.$e->getMessage();
//            print_r("DB LOST\n\n");
            die();
        }


    }

}