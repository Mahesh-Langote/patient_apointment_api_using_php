<?php
class Appointment {
    private $conn;
    private $table_name = "appointments";

    public $id;
    public $doctor_id;
    public $patient_name;
    public $appointment_date;

    public function __construct($db) {
        $this->conn = $db;
    } 
    public function create() {
        $query = "INSERT INTO {$this->table_name} (doctor_id, patient_name, appointment_date) VALUES (:doctor_id, :patient_name, :appointment_date)";

        $stmt = $this->conn->prepare($query);

        $this->doctor_id = htmlspecialchars(strip_tags($this->doctor_id));
        $this->patient_name = htmlspecialchars(strip_tags($this->patient_name));
        $this->appointment_date = htmlspecialchars(strip_tags($this->appointment_date));

        $stmt->bindParam(":doctor_id", $this->doctor_id);
        $stmt->bindParam(":patient_name", $this->patient_name);
        $stmt->bindParam(":appointment_date", $this->appointment_date);

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
    public function readOne() {
        $query = "SELECT * FROM {$this->table_name} WHERE id = :id LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->doctor_id = $row['doctor_id'];
            $this->patient_name = $row['patient_name'];
            $this->appointment_date = $row['appointment_date'];
        }
    } 
    public function update() {
        $query = "UPDATE {$this->table_name} 
                  SET doctor_id = :doctor_id, patient_name = :patient_name, appointment_date = :appointment_date
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $this->doctor_id = htmlspecialchars(strip_tags($this->doctor_id));
        $this->patient_name = htmlspecialchars(strip_tags($this->patient_name));
        $this->appointment_date = htmlspecialchars(strip_tags($this->appointment_date));

        $stmt->bindParam(":doctor_id", $this->doctor_id);
        $stmt->bindParam(":patient_name", $this->patient_name);
        $stmt->bindParam(":appointment_date", $this->appointment_date);
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
