<?php
class Doctor {
    private $conn;
    private $table_name = "doctors";

    public $id;
    public $name;
    public $max_patients;
    public $available_days;

    public function __construct($db) {
        $this->conn = $db;
    } 
    public function create() {
        $query = "INSERT INTO {$this->table_name} (name, max_patients, available_days) VALUES (:name, :max_patients, :available_days)";

        $stmt = $this->conn->prepare($query);

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->max_patients = htmlspecialchars(strip_tags($this->max_patients));
        $this->available_days = htmlspecialchars(strip_tags($this->available_days));

        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":max_patients", $this->max_patients);
        $stmt->bindParam(":available_days", $this->available_days);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
 
    public function read() {
        $query = "SELECT * FROM {$this->table_name}";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    public function getAllDoctors() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
     
    public function readOne() {
        $query = "SELECT * FROM {$this->table_name} WHERE id = :id LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->name = $row['name'];
            $this->max_patients = $row['max_patients'];
            $this->available_days = $row['available_days'];
        }
    }
 
    public function update() {
        $query = "UPDATE {$this->table_name} 
                  SET name = :name, max_patients = :max_patients, available_days = :available_days
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->max_patients = htmlspecialchars(strip_tags($this->max_patients));
        $this->available_days = htmlspecialchars(strip_tags($this->available_days));

        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":max_patients", $this->max_patients);
        $stmt->bindParam(":available_days", $this->available_days);
        $stmt->bindParam(":id", $this->id);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    } 
    public function delete() {
        $query = "DELETE FROM {$this->table_name} WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(":id", $this->id);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
