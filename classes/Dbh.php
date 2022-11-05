<?php

//Create a Class called Dbh
class Dbh {

    private $hostname;
    private $username;
    private $password;
    private $database;

    protected function connect(){
        $this->hostname = "localhost";
        $this->username = "root";
        $this->password = "";
        $this->database = "zuriphp";
        $connect =  new mysqli($this->hostname, $this->username, $this->password, $this->database);

        if($connect->connect_error){
            die("Connection Failed " .$connect->connect_error);
        }

        return $connect;
    }

}


