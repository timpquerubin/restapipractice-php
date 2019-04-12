<?php

class Product {

     private $conn;
     private $table_name = "products";

     public $id;
     public $name;
     public $description;
     public $price;
     public $category_id;
     public $category_name;
     public $created;

     public function __construct($db) {
          $this->conn = $db;
     }

     function read() {

          $query = "SELECT C.NAME AS CATEGORY_NAME, P.ID, P.NAME, P.DESCRIPTION, P.PRICE, P.CATEGORY_ID, P.CREATED FROM ". $this->table_name ." P LEFT JOIN categories C ON P.CATEGORY_ID = C.ID ORDER BY P.CREATED";

          $stmt = $this->conn->prepare($query);
          $stmt->execute();

          return $stmt;
     }

     function readOne() {

          $query = "SELECT C.NAME AS CATEGORY_NAME, P.ID, P.NAME, P.DESCRIPTION, P.PRICE, P.CATEGORY_ID, P.CREATED FROM ". $this->table_name ." P LEFT JOIN categories C ON P.CATEGORY_ID = C.ID WHERE P.ID=? LIMIT 0,1";

          $stmt = $this->conn->prepare($query);
          $stmt->bindParam(1, $this->id);
          $stmt->execute();

          $row = $stmt->fetch(PDO::FETCH_ASSOC);

          $this->name = $row["NAME"];
          $this->price = $row["PRICE"];
          $this->description = $row["DESCRIPTION"];
          $this->category_id = $row["CATEGORY_ID"];
          $this->category_name = $row["CATEGORY_NAME"];
     }

     public function readPaging($from_record_num, $records_per_page) {

          $query = "SELECT C.NAME AS CATEGORY_NAME, P.ID, P.NAME, P.DESCRIPTION, P.PRICE, P.CATEGORY_ID, P.CREATED FROM ". $this->table_name ." P LEFT JOIN categories C ON P.CATEGORY_ID = C.ID ORDER BY P.CREATED DESC LIMIT ?, ?;";

          $stmt = $this->conn->prepare($query);

          $stmt->bindParam(1, $from_record_num, PDO::PARAM_INT);
          $stmt->bindParam(2, $records_per_page, PDO::PARAM_INT);

          $stmt->execute();
          return $stmt;
     }

     public function count() {
          $query = "SELECT COUNT(*) AS TOTAL_ROWS FROM ". $this->table_name .";";

          $stmt = $this->conn->prepare($query);
          $stmt->execute();
          $row = $stmt->fetch(PDO::FETCH_ASSOC);

          return $row["TOTAL_ROWS"];
     }

     function search($keywords) {

          $query = "SELECT C.NAME AS CATEGORY_NAME, P.ID, P.NAME, P.DESCRIPTION, P.PRICE, P.CATEGORY_ID, P.CREATED FROM ". $this->table_name ." P LEFT JOIN categories C ON P.CATEGORY_ID = C.ID WHERE P.NAME LIKE ? OR P.DESCRIPTION LIKE ? OR C.NAME LIKE ? ORDER BY P.CREATED DESC;";

          $stmt = $this->conn->prepare($query);

          $keywords = htmlspecialchars(strip_tags($keywords));
          $keywords = "%{$keywords}%";

          $stmt->bindParam(1, $keywords);
          $stmt->bindParam(2, $keywords);
          $stmt->bindParam(3, $keywords);

          $stmt->execute();
          return $stmt;
     }

     function create() {

          $query = "INSERT INTO ". $this->table_name ." SET NAME=:name, PRICE=:price, DESCRIPTION=:description, CATEGORY_ID=:category_id, CREATED=:created";

          $stmt = $this->conn->prepare($query);

          $this->name = htmlspecialchars(strip_tags($this->name));
          $this->price = htmlspecialchars(strip_tags($this->price));
          $this->description = htmlspecialchars(strip_tags($this->description));
          $this->category_id = htmlspecialchars(strip_tags($this->category_id));
          $this->created = htmlspecialchars(strip_tags($this->created));

          $stmt->bindParam(":name", $this->name);
          $stmt->bindParam(":price", $this->price);
          $stmt->bindParam(":description", $this->description);
          $stmt->bindParam(":category_id", $this->category_id);
          $stmt->bindParam(":created", $this->created);

          if($stmt->execute()) {
               return true;
          }

          return false;
     }

     function update() {

          $query = "UPDATE ". $this->table_name ." SET NAME=:name, PRICE=:price, DESCRIPTION=:description, CATEGORY_ID=:category_id WHERE id=:id";

          $stmt = $this->conn->prepare($query);

          $this->name = htmlspecialchars(strip_tags($this->name));
          $this->price = htmlspecialchars(strip_tags($this->price));
          $this->description = htmlspecialchars(strip_tags($this->description));
          $this->category_id = htmlspecialchars(strip_tags($this->category_id));
          $this->id = htmlspecialchars(strip_tags($this->id));

          $stmt->bindParam(":name", $this->name);
          $stmt->bindParam(":price", $this->price);
          $stmt->bindParam(":description", $this->description);
          $stmt->bindParam(":category_id", $this->category_id);
          $stmt->bindParam(":id", $this->id);

          if($stmt->execute()) {
               return true;
          }

          return false;
     }

     function delete() {

          $query = "DELETE FROM ". $this->table_name ." WHERE ID=?";

          $stmt = $this->conn->prepare($query);

          $this->id = htmlspecialchars(strip_tags($this->id));

          $stmt->bindParam(1, $this->id);

          if($stmt->execute()) {
               return true;
          }

          return false;
     }
}

?>
