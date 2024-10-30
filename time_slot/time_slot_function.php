<?php
// Start the session
session_start();

// Include the database connection
include("../database/db_connect.php");

// Check if the 'add_time_slot' form was submitted
if (isset($_POST['add_time_slot'])) {
    $time_slot_id = $_POST['time_slot_id'];
    $day = $_POST['day'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    // Check if the time_slot_id already exists
    $check_sql = "SELECT * FROM time_slot WHERE time_slot_id='$time_slot_id'";
    $check_result = $conn->query($check_sql);

    // Only proceed to insert if the time_slot_id does not already exist
    if ($check_result->num_rows > 0) {
        $_SESSION['status'] = 'error';
        $_SESSION['message'] = 'Time slot ID already exists';
    } else {
        // Insert the new time slot into the database
        $sql = "INSERT INTO time_slot (time_slot_id, day, start_time, end_time) VALUES ('$time_slot_id', '$day', '$start_time', '$end_time')";
        if ($conn->query($sql) === TRUE) {
            $_SESSION['status'] = 'success';
            $_SESSION['message'] = 'Time Slot added successfully';
        } else {
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Error: ' . $conn->error;
        }
    }
    header('Location: time_slot_form.php');
    exit();
}

// Check if the 'update_time_slot' form was submitted
if (isset($_POST['update_time_slot'])) {
    $time_slot_id = $_POST['time_slot_id']; // Current time_slot_id
    $new_time_slot_id = $_POST['new_time_slot_id']; // New time_slot_id
    $day = $_POST['day'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    // Check if the new_time_slot_id already exists in another row
    if ($time_slot_id !== $new_time_slot_id) {
        $check_id_sql = "SELECT * FROM time_slot WHERE time_slot_id = '$new_time_slot_id'";
        $check_id_result = $conn->query($check_id_sql);
        
        if ($check_id_result->num_rows > 0) {
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'The new Time Slot ID already exists. Please use a different ID.';
            header('Location: time_slot_form.php');
            exit();
        }
    }

    // Check if the time_slot_ID already exists in another row
    $check_sql = "SELECT * FROM time_slot 
                  WHERE time_slot_id = '$new_time_slot_id' AND time_slot_id != '$time_slot_id'";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        // Prevent updating if the time_slot_ID already exists
        $_SESSION['status'] = 'error';
        $_SESSION['message'] = 'Time slot already exists.';
    } else {
        // Proceed with updating the time_slot data, including time_slot_id if necessary
        $sql = "UPDATE time_slot 
                SET time_slot_id='$new_time_slot_id', day='$day', start_time='$start_time', end_time='$end_time' 
                WHERE time_slot_id='$time_slot_id'";

        if ($conn->query($sql) === TRUE) {
            $_SESSION['status'] = 'success';
            $_SESSION['message'] = 'Time Slot updated successfully';
        } else {
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Error: ' . $conn->error;
        }
    }

    header('Location: time_slot_form.php');
    exit();
}

// Check if the 'delete_time_slot' form was submitted
if (isset($_POST['delete_time_slot'])) {
    $time_slot_id = $_POST['time_slot_id'];

    // Delete the time_slot from the database
    $sql = "DELETE FROM time_slot WHERE time_slot_id='$time_slot_id'";
    if ($conn->query($sql) === TRUE) {
        $_SESSION['status'] = 'success';
        $_SESSION['message'] = 'Time Slot deleted successfully';
    } else {
        $_SESSION['status'] = 'error';
        $_SESSION['message'] = 'Error: ' . $conn->error;
    }
    header('Location: time_slot_form.php');
    exit();
}

// Close the database connection
$conn->close();
?>