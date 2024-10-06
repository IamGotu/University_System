<?php
// Start the session
session_start();

// Include the database connection
include("../database/db_connect.php");

// Query to fetch instructor data
$sql = "SELECT * FROM instructor";
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
    <title>Instructor</title>
    <link rel="stylesheet" href="../resources/css/style.css">
</head>
<body>
    <?php include('../components/sidebar.php'); ?>

    <div class="main-content">
        <h1>Instructor</h1>
        
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

        <!-- Button to Open the Modal for Adding Instructor -->
        <button id="openModalBtn">Add New Instructor</button>

        <!-- The Modal for Adding Instructor -->
        <div id="myModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <!-- Form to Add Instructor -->
                <form method="POST" action="/instructor/instructor_function.php">
                <br>
                    <label for="ID">Instructor ID:</label>
                    <input type="text" id="ID" name="ID" required><br><br>

                    <label for="first_name">First Name:</label>
                    <input type="text" id="first_name" name="first_name" required><br><br>

                    <label for="middle_name">Middle Name:</label>
                    <input type="text" id="middle_name" name="middle_name" required><br><br>

                    <label for="last_name">Last Name:</label>
                    <input type="text" id="last_name" name="last_name" required><br><br>

                    <label for="dept_name">Department:</label>
                    <select id="dept_name" name="dept_name" required>
                        <option value="">Select Department</option>
                        <?php foreach ($departments as $dept_name): ?>
                            <option value="<?php echo htmlspecialchars($dept_name); ?>"><?php echo $dept_name; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <br><br>

                    <label for="tot_cred">Total Credits:</label>
                    <input type="text" id="tot_cred" name="tot_cred"><br><br>

                    <div class="button-container">
                        <input type="submit" name="add_instructor" value="Add Instructor">
                        <button type="button" id="cancelAddBtn">Cancel</button>
                    </div>
                </form>
            </div>
        </div>

        <br><br>

        <!-- Display instructors -->
        <table style="width:100%">
            <thead>
                <tr>
                    <th>Instructor ID</th>
                    <th>First Name</th>
                    <th>Middle Name</th>
                    <th>Last Name</th>
                    <th>Department</th>
                    <th>Total Credits</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0) : ?>
                    <?php while ($row = $result->fetch_assoc()) : ?>
                        <tr>
                            <td style="text-align: center;"><?php echo $row['ID']; ?></td>    
                            <td style="text-align: center;"><?php echo $row['first_name']; ?></td>
                            <td style="text-align: center;"><?php echo $row['middle_name']; ?></td>
                            <td style="text-align: center;"><?php echo $row['last_name']; ?></td>
                            <td style="text-align: center;"><?php echo $row['dept_name']; ?></td>
                            <td style="text-align: center;"><?php echo $row['tot_cred']; ?></td>
                            <td>
                                <!-- Edit and Delete buttons -->
                                <div class="button-container">
                                    <button class="edit-btn" data-ID="<?php echo $row['ID']; ?>" data-first_name="<?php echo $row['first_name']; ?>" data-middle_name="<?php echo $row['middle_name']; ?>" data-last_name="<?php echo $row['last_name']; ?>" data-dept_name="<?php echo $row['dept_name']; ?>" data-tot_cred="<?php echo $row['tot_cred']; ?>">Edit</button>
                                    <button class="delete-btn" data-ID="<?php echo $row['ID']; ?>">Delete</button>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="7">No instructors found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- The Modal for Editing Instructor -->
        <div id="editModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <!-- Form to Edit Instructor -->
                <form method="POST" action="/instructor/instructor_function.php">
                    <br>
                    <input type="hidden" id="edit-ID" name="ID" required>

                    <label for="edit-new_ID">Instructor ID:</label>
                    <input type="text" id="edit-new_ID" name="new_ID" required><br><br>

                    <label for="edit-first_name">First Name:</label>
                    <input type="text" id="edit-first_name" name="first_name" required><br><br>

                    <label for="edit-middle_name">Middle Name:</label>
                    <input type="text" id="edit-middle_name" name="middle_name" required><br><br>

                    <label for="edit-last_name">Last Name:</label>
                    <input type="text" id="edit-last_name" name="last_name" required><br><br>

                    <label for="edit-dept_name">Department:</label>
                    <select id="edit-dept_name" name="dept_name" required>
                        <option value="">Select Department</option>
                        <?php foreach ($departments as $dept_name): ?>
                            <option value="<?php echo htmlspecialchars($dept_name); ?>"><?php echo $dept_name; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <br><br>

                    <label for="edit-tot_cred">Total Credits:</label>
                    <input type="text" id="edit-tot_cred" name="tot_cred" required><br><br>

                    <div class="button-container">
                        <input type="submit" name="update_instructor" value="Update Instructor">
                        <button type="button" id="cancelEditBtn">Cancel</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- The Modal for Deleting Instructor -->
        <div id="deleteModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <!-- Confirmation for Deleting instructor -->
                <p>Are you sure you want to delete this instructor?</p>
                <form method="POST" action="/instructor/instructor_function.php">
                    <input type="hidden" id="delete-ID" name="ID">

                    <div class="button-container">
                        <input type="submit" name="delete_instructor" value="Delete Instructor">
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
            var ID = this.getAttribute("data-ID");
            var first_name = this.getAttribute("data-first_name");
            var middle_name = this.getAttribute("data-middle_name");
            var last_name = this.getAttribute("data-last_name");
            var dept_name = this.getAttribute("data-dept_name");
            var tot_cred = this.getAttribute("data-tot_cred");

            document.getElementById("edit-ID").value = ID;
            document.getElementById("edit-new_ID").value = ID;
            document.getElementById("edit-first_name").value = first_name;
            document.getElementById("edit-middle_name").value = middle_name;
            document.getElementById("edit-last_name").value = last_name;
            document.getElementById("edit-dept_name").value = dept_name;
            document.getElementById("edit-tot_cred").value = tot_cred;

            editModal.style.display = "block";
        }
    }

    // Get all delete buttons
    var deleteButtons = document.getElementsByClassName("delete-btn");
    for (let i = 0; i < deleteButtons.length; i++) {
        deleteButtons[i].onclick = function() {
            var ID = this.getAttribute("data-ID");

            document.getElementById("delete-ID").value = ID;

            deleteModal.style.display = "block";
        }
    }
</script>

<?php
// Close the database connection
$conn->close();
?>