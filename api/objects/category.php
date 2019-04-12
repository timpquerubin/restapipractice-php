<?php

class Category {

     private $conn;
     private $table_name = "categories";

     public $id;
     public $name;
     public $description;
     public $created;

     public function __construct($db) {
          $this->conn = $db;
     }

     public function readAll() {

          $query = "SELECT ID, NAME, DESCRIPTION FROM ". $this->table_name ." ORDER BY NAME;";

          $stmt = $this->conn->prepare($query);
          $stmt->execute();

          return $stmt;
     }
}

?>
