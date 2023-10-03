<?php
include_once 'C:\xampp\htdocs\outpatient_appointment_api\models\Doctor.php';
include_once 'C:\xampp\htdocs\outpatient_appointment_api\includes\db_connection.php';

$database = new DBConnection();
$db = $database->getConnection();

$doctor = new Doctor($db);
 
$method = $_SERVER['REQUEST_METHOD'];
 
if ($method === 'GET') { 
    $stmt = $doctor->read();
    $num = $stmt->rowCount();

    if ($num > 0) {
        $doctors_arr = array();
        $doctors_arr["doctors"] = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $doctor_item = array(
                "id" => $id,
                "name" => $name,
                "max_patients" => $max_patients,
                "available_days" => $available_days,
            );

            array_push($doctors_arr["doctors"], $doctor_item);
        }

        http_response_code(200); 
    } else {
        http_response_code(404);
        echo json_encode(array("message" => "No doctors found."));
    }
} 
if ($method === 'GET' && isset($_GET['id'])) {
    $doctor->id = $_GET['id'];
 
    $doctor->readOne();

    if ($doctor->name != null) {
        $doctor_item = array(
            "id" => $doctor->id,
            "name" => $doctor->name,
            "max_patients" => $doctor->max_patients,
            "available_days" => $doctor->available_days,
        );

        http_response_code(200);
        echo json_encode($doctor_item);
    } else {
        http_response_code(404);
        echo json_encode(array("message" => "Doctor not found."));
    }
}
