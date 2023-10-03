<?php
class DBConnection {
    private $host = "localhost";
    private $db_name = "outpatient_appointment_system";
    private $user = "root";
    private $password = "";
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host={$this->host};dbname={$this->db_name}", $this->user, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo "Connection Error: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
