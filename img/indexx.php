<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Outpatient Appointment System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }

        header {
            background-color: #007bff;
            color: #fff;
            text-align: center;
            padding: 20px 0;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        h2 {
            margin-top: 20px;
        }

        form {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="date"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 10px 20px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ccc;
        }

        th {
            background-color: #f2f2f2;
        }

        a {
            text-decoration: none;
            color: #007bff;
        }

        a:hover {
            text-decoration: underline;
        }

        .doctors-list {
            margin-top: 20px;
        }

        .doctor-item {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 4px;
        }
    </style>
</head>

<body>
    <header>
        <h1>Patient Appointment System</h1>
    </header>
    <div class="container">
        <h2>Book an Appointment</h2>
        <form method="POST" action="routes/appointments.php"> 
            <label for="doctor_id">Doctor:</label>
            <select name="doctor_id" required style="width: 100%;">
                <?php
                include_once 'routes/doctors.php';
 
                $stmt = $doctor->read();
                $num = $stmt->rowCount();

                if ($num > 0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
                    }
                } else {
                    echo '<option value="">No doctors found</option>';
                }
                ?>
            </select>

            <label for="patient_name">Patient Name:</label>
            <input type="text" name="patient_name" required>

            <label for="appointment_date">Appointment Date:</label>
            <input type="date" name="appointment_date" required>

            <input type="submit" value="Book Appointment">
        </form>

        <h2>Doctors List</h2>
        <div class="doctors-list">
            <?php
            include_once 'routes/doctors.php';
 
            $stmt = $doctor->read();
            $num = $stmt->rowCount();

            if ($num > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo '<div class="doctor-item">';
                    echo '<h3>' . $row['name'] . '</h3>';
                    echo '<p>ID: ' . $row['id'] . '</p>';
                    echo '<p>Max Patients: ' . $row['max_patients'] . '</p>';
                    echo '<p>Available Days: ' . $row['available_days'] . '</p>';
                    echo '</div>';
                }
            } else {
                echo "<p>No doctors found.</p>";
            }
            ?>
        </div>

        <h2>Appointment List</h2>
<table>
    <tr>
        <th>Doctor</th>
        <th>Patient Name</th>
        <th>Appointment Date</th>
        <th>Actions</th>
    </tr>
    <?php
    include_once 'includes/db_connection.php';
    include_once 'models/Appointment.php';
    $database = new DBConnection();
    $db = $database->getConnection();

    $appointment = new Appointment($db); 
    $sql = "SELECT a.*, d.name AS doctor_name FROM appointments a
            LEFT JOIN doctors d ON a.doctor_id = d.id
            ORDER BY a.appointment_date ASC";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $num = $stmt->rowCount();

    if ($num > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo '<tr>';
            echo '<td>' . $row['doctor_name'] . '</td>';
            echo '<td>' . $row['patient_name'] . '</td>';
            echo '<td>' . $row['appointment_date'] . '</td>';
            echo '<td>'; 
            echo '<a href="remove_appointment.php?id=' . $row['id'] . '">Remove</a>';
             
            echo ' | ';
            echo '<a href="update_appointment.php?id=' . $row['id'] . '">Update</a>';
            echo '</td>';
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="4">No appointments found.</td></tr>';
    }
    ?>
</table>



 





    </div>
</body>

</html>