<?php
// Start the session
session_start();

// Include the database connection
include("../database/db_connect.php");

// Query to fetch department data
$sql = "SELECT * FROM department";
$result = $conn->query($sql);

// Query to fetch classroom data (buildings)
$sql_classrooms = "SELECT DISTINCT building FROM classroom";
$result_classrooms = $conn->query($sql_classrooms);

// Store classroom data in an array
$classrooms = [];
if ($result_classrooms->num_rows > 0) {
    while ($row = $result_classrooms->fetch_assoc()) {
        $classrooms[] = $row['building'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Departments</title>
    <link rel="stylesheet" href="../resources/css/style.css">
</head>
<body>
    <?php include('../components/sidebar.php'); ?>

    <div class="main-content">
        <h1>Departments</h1>
        
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

        <!-- Button to Open the Modal for Adding Department -->
        <button id="openModalBtn">Add New Department</button>

        <!-- The Modal for Adding Department -->
        <div id="myModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <!-- Form to Add Department -->
                <form method="POST" action="/department/department_function.php">
                <br>
                    <label for="dept_name">Department:</label>
                    <input type="text" id="dept_name" name="dept_name" required><br><br>

                    <label for="building">Building:</label>
                    <select id="building" name="building" required>
                        <option value="">Select Building</option>
                        <?php foreach ($classrooms as $building): ?>
                            <option value="<?php echo $building; ?>"><?php echo $building; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <br><br>

                    <label for="budget">Budget:</label>
                    <input type="text" id="budget" name="budget" required><br><br>
                    
                    <div class="button-container">
                        <input type="submit" name="add_department" value="Add Department">
                        <button type="button" id="cancelAddBtn">Cancel</button>
                    </div>
                </form>
            </div>
        </div>

        <br><br>

        <!-- Display Departments -->
        <table style="width:100%">
            <thead>
                <tr>
                    <th>Department</th>
                    <th>Building</th>
                    <th>Budget</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0) : ?>
                    <?php while ($row = $result->fetch_assoc()) : ?>
                        <tr>
                            <td style="text-align: center;"><?php echo $row['dept_name']; ?></td>
                            <td style="text-align: center;"><?php echo $row['building']; ?></td>
                            <td style="text-align: center;"><?php echo $row['budget']; ?></td>
                            <td>
                                <!-- Edit and Delete buttons -->
                                <div class="button-container">
                                    <button class="edit-btn" data-dept_name="<?php echo $row['dept_name']; ?>" data-building="<?php echo $row['building']; ?>" data-budget="<?php echo $row['budget']; ?>">Edit</button>
                                    <button class="delete-btn" data-dept_name="<?php echo $row['dept_name']; ?>">Delete</button>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="4">No Departments found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- The Modal for Editing Department -->
        <div id="editModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <!-- Form to Edit Department -->
                <form method="POST" action="/department/department_function.php">
                <br>
                    <input type="hidden" id="edit-dept_name" name="dept_name" required>

                    <label for="edit-new_dept_name">Department:</label>
                    <input type="text" id="edit-new_dept_name" name="new_dept_name" required><br><br>

                    <label for="edit-building">Building:</label>
                    <select id="edit-building" name="building" required>
                        <option value="">Select Building</option>
                        <?php foreach ($classrooms as $building): ?>
                            <option value="<?php echo htmlspecialchars($building); ?>"><?php echo $building; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <br><br>

                    <label for="edit-budget">Budget:</label>
                    <input type="number" id="edit-budget" name="budget" required><br><br>

                    <div class="button-container">
                        <input type="submit" name="update_department" value="Update Department">
                        <button type="button" id="cancelEditBtn">Cancel</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- The Modal for Deleting Department -->
        <div id="deleteModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <!-- Confirmation for Deleting Department -->
                <p>Are you sure you want to delete this department?</p>
                <form method="POST" action="/department/department_function.php">                    
                    <input type="hidden" id="delete-dept_name" name="dept_name">

                    <div class="button-container">
                        <input type="submit" name="delete_department" value="Delete Department">
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
            var dept_name = this.getAttribute("data-dept_name");
            var building = this.getAttribute("data-building");
            var budget = this.getAttribute("data-budget");

            document.getElementById("edit-dept_name").value = dept_name;
            document.getElementById("edit-new_dept_name").value = dept_name;
            document.getElementById("edit-building").value = building;
            document.getElementById("edit-budget").value = budget;

            editModal.style.display = "block";
        }
    }

    // Get all delete buttons
    var deleteButtons = document.getElementsByClassName("delete-btn");
    for (let i = 0; i < deleteButtons.length; i++) {
        deleteButtons[i].onclick = function() {
            var dept_name = this.getAttribute("data-dept_name");
            document.getElementById("delete-dept_name").value = dept_name;

            deleteModal.style.display = "block";
        }
    }
</script>

<?php
// Close the database connection
$conn->close();
?>