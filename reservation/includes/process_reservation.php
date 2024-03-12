<?php
include('dbconnection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $customers_id = $_POST['id'];
    $reservation_date = date('Y-m-d');
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $room_type = $_POST['room_type'];
    $num_people = $_POST['num_people'];
    $special_requests = $_POST['special_requests'];
    $license_plate = $_POST['license_plate'];

    $response = array();

    $checkQuery = "SELECT * FROM reservations WHERE start_date = '$start_date' AND end_date = '$end_date' AND room_type = '$room_type'";
    $checkResult = mysqli_query($conn, $checkQuery);

    if ($checkResult) {
        if (mysqli_num_rows($checkResult) > 0) {
            $response['success'] = false;
            $response['message'] = 'This reservation already exists';
        } else {
            // Start transaction to ensure data consistency
            mysqli_begin_transaction($conn);

            // Insert the reservation if it doesn't exist
            $sql = "INSERT INTO reservations (customers_id, reservation_date, start_date, end_date, room_type, num_people, special_requests, vehicle_license_plate) VALUES ('$customers_id', '$reservation_date', '$start_date', '$end_date', '$room_type', '$num_people', '$special_requests', '$license_plate')";
            $result = mysqli_query($conn, $sql);

            if ($result) {
                // Get the ID of the newly inserted reservation
                $reservation_id = mysqli_insert_id($conn);

                // Insert a record into the plates table with the reservation_id
                $insertPlateSql = "INSERT INTO plates (customer_id, reservation_id, plate, active) VALUES ('$customers_id', '$reservation_id', '$license_plate', false)";
                $plateResult = mysqli_query($conn, $insertPlateSql);
                
                if ($plateResult) {
                    // Commit the transaction if both inserts are successful
                    mysqli_commit($conn);
                    $response['success'] = true;
                    $response['message'] = 'Reservation and plate record added successfully';
                } else {
                    // Rollback the transaction if plate insertion fails
                    mysqli_rollback($conn);
                    $response['success'] = false;
                    $response['message'] = 'Failed to add plate record: ' . mysqli_error($conn);
                }
            } else {
                // Rollback the transaction if reservation insertion fails
                mysqli_rollback($conn);
                $response['success'] = false;
                $response['message'] = 'Reservation failed. ' . mysqli_error($conn);
            }
        }
    } else {
        $response['success'] = false;
        $response['message'] = 'Unable to check the reservation';
    }

    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
