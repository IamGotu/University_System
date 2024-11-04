<?php
// Start the session
session_start();

// Include the database connection
include("../database/db_connect.php");

// Check if the 'add_instructor' form was submitted
if (isset($_POST['add_instructor'])) {
    $ID = $_POST['ID'];
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'] ? "'" . $_POST['middle_name'] . "'" : "NULL";
    $last_name = $_POST['last_name'];
    $street_number = $_POST['street_number'] ? "'" . $_POST['street_number'] . "'" : "NULL";
    $street_name = $_POST['street_name'] ? "'" . $_POST['street_name'] . "'" : "NULL";
    $apt_number = $_POST['apt_number'] ? "'" . $_POST['apt_number'] . "'" : "NULL";
    $city = $_POST['city'];
    $state = $_POST['state'];
    $postal_code = $_POST['postal_code'] ? "'" . $_POST['postal_code'] . "'" : "NULL";
    $date_of_birth = $_POST['date_of_birth'];
    $dept_name = !empty($_POST['dept_name']) ? "'" . $_POST['dept_name'] . "'" : "NULL";
    $salary = $_POST['salary'];

    // Check if the instructor already exists
    $check_sql = "SELECT * FROM instructor WHERE ID='$ID'";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        // If instructor already exists, set an error message
        $_SESSION['status'] = 'error';
        $_SESSION['message'] = 'Instructor already exists';
    } else {
        // Insert the new instructor into the database
        $sql = "INSERT INTO instructor (ID, first_name, middle_name, last_name, street_number, street_name, apt_number, city, state, postal_code, date_of_birth, dept_name, salary) VALUES ('$ID', '$first_name', '$middle_name', '$last_name', '$street_number', '$street_name', '$apt_number', '$city', '$state', '$postal_code', '$date_of_birth', $dept_name, '$salary')";
        if ($conn->query($sql) === TRUE) {
            $_SESSION['status'] = 'success';
            $_SESSION['message'] = 'Instructor added successfully';
        } else {
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Error: ' . $conn->error;
        }
    }
    header('Location: instructor_form.php');
    exit();
}

// Check if the 'update_instructor' form was submitted
if (isset($_POST['update_instructor'])) {
    $ID = $_POST['ID']; // Current ID
    $new_ID = $_POST['new_ID']; // New ID
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
    $street_number = $_POST['street_number'];
    $street_name = $_POST['street_name'];
    $apt_number = $_POST['apt_number'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $postal_code = $_POST['postal_code'];
    $date_of_birth = $_POST['date_of_birth'];
    $dept_name = !empty($_POST['dept_name']) ? "'" . $_POST['dept_name'] . "'" : "NULL";
    $salary = $_POST['salary'];

    // Check if the new_ID already exists in another row
    if ($ID !== $new_ID) {
        $check_id_sql = "SELECT * FROM instructor WHERE ID = '$new_ID'";
        $check_id_result = $conn->query($check_id_sql);
        
        if ($check_id_result->num_rows > 0) {
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'The new Time Slot ID already exists. Please use a different ID.';
            header('Location: instructor_form.php');
            exit();
        }
    }
    // Check if the new ID already exists
    $check_sql = "SELECT * FROM instructor WHERE ID = '$new_ID' AND ID != '$ID'";
    $check_result = $conn->query($check_sql);

    // Only proceed if the new ID does not exist
    if ($check_result->num_rows > 0) {
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Instructor ID already exists';
        } else {
        // Update the instructor data in the database
        $sql = "UPDATE instructor
                SET ID='$new_ID', first_name='$first_name',
                middle_name='$middle_name', last_name='$last_name',
                street_number='$street_number', street_name='$street_name', apt_number='$apt_number', city='$city', state='$state', postal_code='$postal_code', date_of_birth='$date_of_birth', dept_name=$dept_name,
                salary='$salary' WHERE ID='$ID'";

        if ($conn->query($sql) === TRUE) {
            $_SESSION['status'] = 'success';
            $_SESSION['message'] = 'Instructor updated successfully';
        } else {
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Error: ' . $conn->error;
        }
    } 
    header('Location: instructor_form.php');
    exit();
}

// Check if the 'delete_instructor' form was submitted
if (isset($_POST['delete_instructor'])) {
    $ID = $_POST['ID'];

    // Delete the instructor from the database
    $sql = "DELETE FROM instructor WHERE ID='$ID'";
    if ($conn->query($sql) === TRUE) {
        $_SESSION['status'] = 'success';
        $_SESSION['message'] = 'Instructor deleted successfully';
    } else {
        $_SESSION['status'] = 'error';
        $_SESSION['message'] = 'Error: ' . $conn->error;
    }
    header('Location: instructor_form.php');
    exit();
}

// Close the database connection
$conn->close();
?>