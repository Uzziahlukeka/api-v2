<?php 

class Bids{

    private $con;
    private $table='bids';
    public function __construct($db){
        $this->con=$db;
    }

    

}