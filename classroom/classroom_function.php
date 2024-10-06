<?php
// Start the session
session_start();

// Include the database connection
include("../database/db_connect.php");

// Check if the 'add_classroom' form was submitted
if (isset($_POST['add_classroom'])) {
    $building = $_POST['building'];
    $room_number = $_POST['room_number'];
    $capacity = $_POST['capacity'];

    // Check if the classroom already exists
    $check_sql = "SELECT * FROM classroom WHERE building='$building' AND room_number='$room_number'";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        // If classroom already exists, set an error message
        $_SESSION['status'] = 'error';
        $_SESSION['message'] = 'Classroom already exists in Building ' . $building . ' with Room Number ' . $room_number;
    } else {
        // Insert the new classroom into the database
        $sql = "INSERT INTO classroom (building, room_number, capacity) VALUES ('$building', '$room_number', '$capacity')";
        if ($conn->query($sql) === TRUE) {
            $_SESSION['status'] = 'success';
            $_SESSION['message'] = 'Classroom added successfully';
        } else {
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Error: ' . $conn->error;
        }
    }
    header('Location: classroom_form.php');
    exit();
}

// Check if the 'update_classroom' form was submitted
if (isset($_POST['update_classroom'])) {
    $building = $_POST['building'];
    $room_number = $_POST['room_number'];
    $capacity = $_POST['capacity'];

        // Update the classroom data in the database
        $sql = "UPDATE classroom SET capacity='$capacity' WHERE building='$building' AND room_number='$room_number'";
        if ($conn->query($sql) === TRUE) {
            $_SESSION['status'] = 'success';
            $_SESSION['message'] = 'Classroom updated successfully';
        } else {
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Error: ' . $conn->error;
        }
        header('Location: classroom_form.php');
        exit();
    }

// Check if the 'delete_classroom' form was submitted
if (isset($_POST['delete_classroom'])) {
    $building = $_POST['building'];
    $room_number = $_POST['room_number'];

    // Delete the classroom from the database
    $sql = "DELETE FROM classroom WHERE building='$building' AND room_number='$room_number'";
    if ($conn->query($sql) === TRUE) {
        $_SESSION['status'] = 'success';
        $_SESSION['message'] = 'Classroom deleted successfully';
    } else {
        $_SESSION['status'] = 'error';
        $_SESSION['message'] = 'Error: ' . $conn->error;
    }
    header('Location: classroom_form.php');
    exit();
}

// Close the database connection
$conn->close();
?>