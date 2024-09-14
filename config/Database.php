<?php
namespace uzziah;
use PDO;
use PDOException;

class Database{
    private $host = 'database';
    private $db_name = 'log2';
    private $db_port = 3306; // Assuming default port for MySQL
    private $username = 'uzh';
    private $password = 'test';
    private $conn;

    // DB connect 
    public function connect(){
        $this->conn = null;

        try{
            // Include port number in the DSN if it's not the default port
            $this->conn = new PDO('mysql:host='.$this->host.';port=' . $this->db_port . ';dbname='.$this->db_name, $this->username, $this->password);
            // Set attributes for showing errors  
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e){
            echo 'Connection error: '.$e->getMessage();
        }
        
        return $this->conn;
    }
}

