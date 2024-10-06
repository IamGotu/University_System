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

    // Insert the new time_slot into the database
    $sql = "INSERT INTO time_slot (time_slot_id, day, start_time, end_time) VALUES ('$time_slot_id', '$day', '$start_time', '$end_time')";
    if ($conn->query($sql) === TRUE) {
        $_SESSION['status'] = 'success';
        $_SESSION['message'] = 'Time Slot added successfully';
    } else {
        $_SESSION['status'] = 'error';
        $_SESSION['message'] = 'Error: ' . $conn->error;
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

    // Log the received values for debugging
    error_log("time_slot_id: $time_slot_id, new_time_slot_id: $new_time_slot_id, day: $day, start_time: $start_time, end_time: $end_time");

    // Update the time_slot data in the database
    $sql = "UPDATE time_slot SET time_slot_id='$new_time_slot_id', day='$day', start_time='$start_time', end_time='$end_time' WHERE time_slot_id='$time_slot_id'";
    
    if ($conn->query($sql) === TRUE) {
        $_SESSION['status'] = 'success';
        $_SESSION['message'] = 'Time Slot updated successfully';
    } else {
        $_SESSION['status'] = 'error';
        $_SESSION['message'] = 'Error: ' . $conn->error; // Capture specific error message
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