<?php
include_once '../models/Appointment.php';
include_once '../models/Doctor.php';  
include_once '../includes/db_connection.php';

$database = new DBConnection();
$db = $database->getConnection();

$appointment = new Appointment($db);
$doctor = new Doctor($db);  
 
$method = $_SERVER['REQUEST_METHOD'];
 
if ($method === 'POST') { 
    if (
        isset($_POST['doctor_id']) &&
        isset($_POST['patient_name']) &&
        isset($_POST['appointment_date'])
    ) {
        $appointmentDate = date_create_from_format('Y-m-d', $_POST['appointment_date']);
         
        $currentDate = new DateTime();
        $currentDate->modify('+15 days');
        
        if ($appointmentDate <= $currentDate) { 
            if ($appointmentDate->format('w') != 0) {
                $appointment->doctor_id = $_POST['doctor_id'];
                $appointment->patient_name = $_POST['patient_name'];
                $appointment->appointment_date = $_POST['appointment_date'];
 
                if ($appointment->create()) { 
                    $doctor->id = $_POST['doctor_id'];
                    $doctor->readOne();
                    
                    if ($doctor->max_patients > 0) {
                        $doctor->max_patients--;
                        $doctor->update();
                    }

                    http_response_code(201);
                    echo json_encode(array("message" => "Appointment booked successfully."));
                } else {
                    http_response_code(503);
                    echo json_encode(array("message" => "Unable to book appointment."));
                }
            } else {
                http_response_code(400);
                echo json_encode(array("message" => "Appointments are not available on Sundays."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Appointments can only be booked within 15 days from the current date."));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "Incomplete data. Please provide doctor_id, patient_name, and appointment_date."));
    }
}
?>
