<?php
// Start the session
session_start();

// Include the database connection
include("../database/db_connect.php");

// Query to fetch classroom data
$sql = "SELECT * FROM classroom";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classroom</title>
    <link rel="stylesheet" href="../resources/css/style.css">
</head>
<body>
    <?php include('../components/sidebar.php'); ?>

    <div class="main-content">
        <h1>Classroom</h1>
        
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

        <!-- Button to Open the Modal for Adding Classroom -->
        <button id="openModalBtn">Add New Classroom</button>

        <!-- The Modal for Adding Classroom -->
        <div id="myModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <!-- Form to Add Classroom -->
                <form method="POST" action="/classroom/classroom_function.php">
                <br>
                    <label for="building">Building:</label>
                    <input type="text" id="building" name="building" required><br><br>

                    <label for="room_number">Room Number:</label>
                    <input type="number" id="room_number" name="room_number"><br><br>

                    <label for="capacity">Capacity:</label>
                    <input type="text" id="capacity" name="capacity"><br><br>

                    <div class="button-container">
                        <input type="submit" name="add_classroom" value="Add Classroom">
                        <button type="button" id="cancelAddBtn">Cancel</button>
                    </div>
                </form>
            </div>
        </div>

        <br><br>

        <!-- Display Classrooms -->
        <table style="width:100%">
            <thead>
                <tr>
                    <th>Building</th>
                    <th>Room Number</th>
                    <th>Capacity</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0) : ?>
                    <?php while ($row = $result->fetch_assoc()) : ?>
                        <tr>
                            <td style="text-align: center;"><?php echo $row['building']; ?></td>
                            <td style="text-align: center;"><?php echo $row['room_number'] ? $row['room_number'] : 'N/A'; ?></td>
                            <td style="text-align: center;"><?php echo $row['capacity']; ?></td>
                            <td>
                                <!-- Edit and Delete buttons -->
                                <div class="button-container">
                                    <button class="edit-btn" data-building="<?php echo $row['building']; ?>" data-room_number="<?php echo $row['room_number']; ?>" data-capacity="<?php echo $row['capacity']; ?>">Edit</button>
                                    <button class="delete-btn" data-building="<?php echo $row['building']; ?>" data-room_number="<?php echo $row['room_number']; ?>">Delete</button>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="4">No classrooms found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- The Modal for Editing Classroom -->
        <div id="editModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <!-- Form to Edit Classroom -->
                <form method="POST" action="/classroom/classroom_function.php">
                <br>
                    <input type="hidden" id="edit-building" name="building">
                    
                    <input type="hidden" id="edit-room_number" name="room_number">

                    <label for="edit-new_building">Building:</label>
                    <input type="text" id="edit-new_building" name="new_building" required><br><br>

                    <label for="edit-new_room_number">Room Number:</label>
                    <input type="number" id="edit-new_room_number" name="new_room_number"><br><br>

                    <label for="edit-capacity">Capacity:</label>
                    <input type="text" id="edit-capacity" name="capacity" required><br><br>

                    <div class="button-container">
                        <input type="submit" name="update_classroom" value="Update Classroom">
                        <button type="button" id="cancelEditBtn">Cancel</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- The Modal for Deleting Classroom -->
        <div id="deleteModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <!-- Confirmation for Deleting Classroom -->
                <p>Are you sure you want to delete this classroom?</p>
                <form method="POST" action="/classroom/classroom_function.php">
                    <input type="hidden" id="delete-building" name="building">

                    <input type="hidden" id="delete-room_number" name="room_number">

                    <div class="button-container">
                        <input type="submit" name="delete_classroom" value="Delete Classroom">
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
            var building = this.getAttribute("data-building");
            var room_number = this.getAttribute("data-room_number");
            var capacity = this.getAttribute("data-capacity");

            document.getElementById("edit-building").value = building;
            document.getElementById("edit-new_building").value = building;
            document.getElementById("edit-room_number").value = room_number;
            document.getElementById("edit-new_room_number").value = room_number;
            document.getElementById("edit-capacity").value = capacity;

            editModal.style.display = "block";
        }
    }

    // Get all delete buttons
    var deleteButtons = document.getElementsByClassName("delete-btn");
    for (let i = 0; i < deleteButtons.length; i++) {
        deleteButtons[i].onclick = function() {
            var building = this.getAttribute("data-building");
            var room_number = this.getAttribute("data-room_number");

            document.getElementById("delete-building").value = building;
            document.getElementById("delete-room_number").value = room_number;

            deleteModal.style.display = "block";
        }
    }
</script>

<?php
// Close the database connection
$conn->close();
?>