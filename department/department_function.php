<?php
// Start the session
session_start();

// Include the database connection
include("../database/db_connect.php");

// Check if the 'add_department' form was submitted
if (isset($_POST['add_department'])) {
    $dept_name = $_POST['dept_name'];
    $building = !empty($_POST['building']) ? "'" . $_POST['building'] . "'" : "NULL";
    $budget = $_POST['budget'];

    // Check if the department already exists
    $check_sql = "SELECT * FROM department WHERE dept_name='$dept_name'";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        // If department already exists, set an error message
        $_SESSION['status'] = 'error';
        $_SESSION['message'] = 'Department already exists';
    } else {
        // Insert the new department into the database
        $sql = "INSERT INTO department (dept_name, building, budget) VALUES ('$dept_name', $building, '$budget')";
        if ($conn->query($sql) === TRUE) {
            $_SESSION['status'] = 'success';
            $_SESSION['message'] = 'Department added successfully';
        } else {
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Error: ' . $conn->error;
        }
    }
    header('Location: department_form.php');
    exit();
}

// Check if the 'update_department' form was submitted
if (isset($_POST['update_department'])) {
    $dept_name = $_POST['dept_name'];
    $new_dept_name = $_POST['new_dept_name'];
    $building = !empty($_POST['building']) ? "'" . $_POST['building'] . "'" : "NULL";
    $budget = $_POST['budget'];

    // Ensure that you're not updating to a duplicate department
    $check_sql = "SELECT * FROM department WHERE dept_name='$new_dept_name' AND dept_name !='$dept_name'";
    $check_result = $conn->query($check_sql);

    // Only proceed if the new building and room_number do not exist
    if ($check_result->num_rows === 0) {
        // Update the department data in the database
        $sql = "UPDATE department SET dept_name='$new_dept_name', building=$building, budget='$budget' WHERE dept_name='$dept_name'";
        if ($conn->query($sql) === TRUE) {
            $_SESSION['status'] = 'success';
            $_SESSION['message'] = 'Department updated successfully';
        } else {
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Error: ' . $conn->error;
        }
    } else {
        $_SESSION['status'] = 'error';
        $_SESSION['message'] = 'Department already exists';
    }
    header('Location: department_form.php');
    exit();
}

// Check if the 'delete_department' form was submitted
if (isset($_POST['delete_department'])) {
    $dept_name = $_POST['dept_name'];

    // Delete the department from the database
    $sql = "DELETE FROM department WHERE dept_name='$dept_name'";
    if ($conn->query($sql) === TRUE) {
        $_SESSION['status'] = 'success';
        $_SESSION['message'] = 'Department deleted successfully';
    } else {
        $_SESSION['status'] = 'error';
        $_SESSION['message'] = 'Error: ' . $conn->error;
    }
    header('Location: department_form.php');
    exit();
}

// Close the database connection
$conn->close();
?>