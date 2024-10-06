<?php
// Start the session
session_start();

// Include the database connection
include("../database/db_connect.php");

// Check if the 'add_course' form was submitted
if (isset($_POST['add_course'])) {
    $course_id = $_POST['course_id'];
    $title = $_POST['title'];
    $dept_name = $_POST['dept_name'];
    $credits = $_POST['credits'];

    // Check if the course already exists
    $check_sql = "SELECT * FROM course WHERE course_id='$course_id'";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        // If course already exists, set an error message
        $_SESSION['status'] = 'error';
        $_SESSION['message'] = 'Course already exists';
    } else {
        // Insert the new course into the database
        $sql = "INSERT INTO course (course_id, title, dept_name, credits) VALUES ('$course_id', '$title', '$dept_name', '$credits')";
        if ($conn->query($sql) === TRUE) {
            $_SESSION['status'] = 'success';
            $_SESSION['message'] = 'Course added successfully';
        } else {
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Error: ' . $conn->error;
        }
    }
    header('Location: course_form.php');
    exit();
}

// Check if the 'update_course' form was submitted
if (isset($_POST['update_course'])) {
    $course_id = $_POST['course_id']; // Current course_id
    $new_course_id = $_POST['new_course_id']; // New course_id
    $title = $_POST['title'];
    $dept_name = $_POST['dept_name'];
    $credits = $_POST['credits'];

    // Check if the new course_id already exists
    $check_sql = "SELECT * FROM course WHERE course_id='$new_course_id' AND course_id != '$course_id'";
    $check_result = $conn->query($check_sql);

    // Only proceed if the new course_id does not exist
    if ($check_result->num_rows === 0) {
        // Update the course data in the database
        $sql = "UPDATE course SET course_id='$new_course_id', title='$title', dept_name='$dept_name', credits='$credits' WHERE course_id='$course_id'";
        if ($conn->query($sql) === TRUE) {
            $_SESSION['status'] = 'success';
            $_SESSION['message'] = 'Course updated successfully';
        } else {
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Error: ' . $conn->error;
        }
    } else {
        $_SESSION['status'] = 'error';
        $_SESSION['message'] = 'Course ID already exists';
    }

    header('Location: course_form.php');
    exit();
}

// Check if the 'delete_course' form was submitted
if (isset($_POST['delete_course'])) {
    $course_id = $_POST['course_id'];

    // Delete the course from the database
    $sql = "DELETE FROM course WHERE course_id='$course_id'";
    if ($conn->query($sql) === TRUE) {
        $_SESSION['status'] = 'success';
        $_SESSION['message'] = 'Course deleted successfully';
    } else {
        $_SESSION['status'] = 'error';
        $_SESSION['message'] = 'Error: ' . $conn->error;
    }
    header('Location: course_form.php');
    exit();
}

// Close the database connection
$conn->close();
?>