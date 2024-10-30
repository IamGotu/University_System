<?php
// Start the session
session_start();

// Include the database connection
include("../database/db_connect.php");

// Check if the 'add_student' form was submitted
if (isset($_POST['add_student'])) {
    $ID = $_POST['ID'];
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
    $dept_name = !empty($_POST['dept_name']) ? "'$dept_name'" : "NULL";
    $tot_cred = $_POST['tot_cred'];

    // Check if the student already exists
    $check_sql = "SELECT * FROM student WHERE ID='$ID'";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        // If student already exists, set an error message
        $_SESSION['status'] = 'error';
        $_SESSION['message'] = 'Student already exists';
    } else {
        // Insert the new student into the database
        $sql = "INSERT INTO student (ID, first_name, middle_name, last_name, dept_name, tot_cred) VALUES ('$ID', '$first_name', '$middle_name', '$last_name', $dept_name, '$tot_cred')";
        if ($conn->query($sql) === TRUE) {
            $_SESSION['status'] = 'success';
            $_SESSION['message'] = 'Student added successfully';
        } else {
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Error: ' . $conn->error;
        }
    }
    header('Location: student_form.php');
    exit();
}

// Check if the 'update_student' form was submitted
if (isset($_POST['update_student'])) {
    $ID = $_POST['ID']; // Current ID
    $new_ID = $_POST['new_ID']; // New ID
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
    $dept_name = !empty($_POST['dept_name']) ? "'$dept_name'" : "NULL";
    $tot_cred = $_POST['tot_cred'];

    // Check if the new ID already exists
    $check_sql = "SELECT * FROM student WHERE ID='$new_ID' AND ID != '$ID'";
    $check_result = $conn->query($check_sql);

    // Only proceed if the new ID does not exist
    if ($check_result->num_rows === 0) {
        // Update the student data in the database
        $sql = "UPDATE student SET ID='$new_ID', first_name='$first_name', middle_name='$middle_name', last_name='$last_name', dept_name=$dept_name, tot_cred='$tot_cred' WHERE ID='$ID'";
        if ($conn->query($sql) === TRUE) {
            $_SESSION['status'] = 'success';
            $_SESSION['message'] = 'Student updated successfully';
        } else {
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Error: ' . $conn->error;
        }
    } else {
        $_SESSION['status'] = 'error';
        $_SESSION['message'] = 'Student ID already exists';
    }

    header('Location: student_form.php');
    exit();
}

// Check if the 'delete_student' form was submitted
if (isset($_POST['delete_student'])) {
    $ID = $_POST['ID'];

    // Delete the student from the database
    $sql = "DELETE FROM student WHERE ID='$ID'";
    if ($conn->query($sql) === TRUE) {
        $_SESSION['status'] = 'success';
        $_SESSION['message'] = 'Student deleted successfully';
    } else {
        $_SESSION['status'] = 'error';
        $_SESSION['message'] = 'Error: ' . $conn->error;
    }
    header('Location: student_form.php');
    exit();
}

// Close the database connection
$conn->close();
?>