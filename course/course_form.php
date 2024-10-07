<?php
// Start the session
session_start();

// Include the database connection
include("../database/db_connect.php");

// Query to fetch course data
$sql = "SELECT * FROM course";
$result = $conn->query($sql);

// Query to fetch department data (dept_name)
$sql_departments = "SELECT DISTINCT dept_name FROM department";
$result_departments = $conn->query($sql_departments);

// Store classroom data in an array
$departments = [];
if ($result_departments->num_rows > 0) {
    while ($row = $result_departments->fetch_assoc()) {
        $departments[] = $row['dept_name'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course</title>
    <link rel="stylesheet" href="../resources/css/style.css">
</head>
<body>
    <?php include('../components/sidebar.php'); ?>

    <div class="main-content">
        <h1>Course</h1>
        
        <?php
            // Check for status and message
            if (isset($_SESSION['status']) && isset($_SESSION['message'])) {
                $status = $_SESSION['status'];
                $message = $_SESSION['message'];
                echo "<div class='$status'>$message</div>";

                // Clear the message after displaying it
                unset($_SESSION['status']);
                unset($_SESSION['message']);
            }
        ?>
        <br>

        <!-- Button to Open the Modal for Adding Course -->
        <button id="openModalBtn">Add New Course</button>

        <!-- The Modal for Adding Course -->
        <div id="myModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <!-- Form to Add Course -->
                <form method="POST" action="/course/course_function.php">
                <br>
                    <label for="course_id">Course ID:</label>
                    <input type="text" id="course_id" name="course_id" required><br><br>

                    <label for="title">Title:</label>
                    <input type="text" id="title" name="title" required><br><br>

                    <label for="dept_name">Department:</label>
                    <select id="dept_name" name="dept_name" required>
                        <option value="">Select Department</option>
                        <?php foreach ($departments as $dept_name): ?>
                            <option value="<?php echo htmlspecialchars($dept_name); ?>"><?php echo $dept_name; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <br><br>

                    <label for="credits">Credits:</label>
                    <input type="text" id="credits" name="credits"><br><br>

                    <div class="button-container">
                        <input type="submit" name="add_course" value="Add Course">
                        <button type="button" id="cancelAddBtn">Cancel</button>
                    </div>
                </form>
            </div>
        </div>

        <br><br>

        <!-- Display Courses -->
        <table style="width:100%">
            <thead>
                <tr>
                    <th>Course ID</th>
                    <th>Title</th>
                    <th>Department</th>
                    <th>Credits</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0) : ?>
                    <?php while ($row = $result->fetch_assoc()) : ?>
                        <tr>
                            <td style="text-align: center;"><?php echo $row['course_id']; ?></td>    
                            <td style="text-align: center;"><?php echo $row['title']; ?></td>
                            <td style="text-align: center;"><?php echo $row['dept_name']; ?></td>
                            <td style="text-align: center;"><?php echo $row['credits']; ?></td>
                            <td>
                                <!-- Edit and Delete buttons -->
                                <div class="button-container">
                                    <button class="edit-btn" data-course_id="<?php echo $row['course_id']; ?>" data-title="<?php echo $row['title']; ?>" data-dept_name="<?php echo $row['dept_name']; ?>" data-credits="<?php echo $row['credits']; ?>">Edit</button>
                                    <button class="delete-btn" data-course_id="<?php echo $row['course_id']; ?>">Delete</button>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="5">No Courses found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- The Modal for Editing Course -->
        <div id="editModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <!-- Form to Edit Course -->
                <form method="POST" action="/course/course_function.php">
                    <br>
                    <input type="hidden" id="edit-course_id" name="course_id" required>

                    <label for="edit-new_course_id">Course ID:</label>
                    <input type="text" id="edit-new_course_id" name="new_course_id" required><br><br>

                    <label for="edit-title">Title:</label>
                    <input type="text" id="edit-title" name="title" required><br><br>

                    <label for="edit-dept_name">Department:</label>
                    <select id="edit-dept_name" name="dept_name" required>
                        <option value="">Select Department</option>
                        <?php foreach ($departments as $dept_name): ?>
                            <option value="<?php echo htmlspecialchars($dept_name); ?>"><?php echo $dept_name; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <br><br>

                    <label for="edit-credits">Credits:</label>
                    <input type="text" id="edit-credits" name="credits" required><br><br>

                    <div class="button-container">
                        <input type="submit" name="update_course" value="Update Course">
                        <button type="button" id="cancelEditBtn">Cancel</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- The Modal for Deleting Course -->
        <div id="deleteModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <!-- Confirmation for Deleting course -->
                <p>Are you sure you want to delete this course?</p>
                <form method="POST" action="/course/course_function.php">
                    <input type="hidden" id="delete-course_id" name="course_id">

                    <div class="button-container">
                        <input type="submit" name="delete_course" value="Delete Course">
                        <button type="button" id="cancelDeleteBtn">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

<script src="../resources/js/script.js"></script>
<script>
    // Get all edit buttons
    var editButtons = document.getElementsByClassName("edit-btn");
    for (let i = 0; i < editButtons.length; i++) {
        editButtons[i].onclick = function() {
            var course_id = this.getAttribute("data-course_id");
            var title = this.getAttribute("data-title");
            var dept_name = this.getAttribute("data-dept_name");
            var credits = this.getAttribute("data-credits");

            document.getElementById("edit-course_id").value = course_id;
            document.getElementById("edit-new_course_id").value = course_id;
            document.getElementById("edit-title").value = title;
            document.getElementById("edit-dept_name").value = dept_name;
            document.getElementById("edit-credits").value = credits;

            editModal.style.display = "block";
        }
    }

    // Get all delete buttons
    var deleteButtons = document.getElementsByClassName("delete-btn");
    for (let i = 0; i < deleteButtons.length; i++) {
        deleteButtons[i].onclick = function() {
            var course_id = this.getAttribute("data-course_id");

            document.getElementById("delete-course_id").value = course_id;

            deleteModal.style.display = "block";
        }
    }
</script>

<?php
// Close the database connection
$conn->close();
?>