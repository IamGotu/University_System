<?php
// Start the session
session_start();

// Include the database connection
include("../database/db_connect.php");

// Query to fetch time_slot data
$sql = "SELECT * FROM time_slot";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Time Slot</title>
    <link rel="stylesheet" href="../resources/css/style.css">
</head>
<body>
    <?php include('../components/sidebar.php'); ?>

    <div class="main-content">
        <h1>Time Slot</h1>
        
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

        <!-- Button to Open the Modal for Adding Time Slot -->
        <button id="openModalBtn">Add New Time Slot</button>

        <!-- The Modal for Adding Time Slot -->
        <div id="myModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <!-- Form to Add Time Slot -->
                <form method="POST" action="/time_slot/time_slot_function.php">
                <br>
                    <label for="time_slot_id">Time Slot ID:</label>
                    <input type="text" id="time_slot_id" name="time_slot_id" required>
                    <br><br>

                    <label for="day">Day:</label>
                    <select id="day" name="day" required>
                        <option value="Monday">Monday</option>
                        <option value="Tuesday">Tuesday</option>
                        <option value="Wednesday">Wednesday</option>
                        <option value="Thursday">Thursday</option>
                        <option value="Friday">Friday</option>
                        <option value="Saturday">Saturday</option>
                        <option value="Sunday">Sunday</option>
                    </select>
                    <br><br>

                    <label for="start_time">Start Time:</label>
                    <input type="time" id="start_time" name="start_time" required>
                    <br><br>

                    <label for="end_time">End Time:</label>
                    <input type="time" id="end_time" name="end_time" required>
                    <br><br>

                    <div class="button-container">
                        <input type="submit" name="add_time_slot" value="Add Time Slot">
                        <button type="button" id="cancelAddBtn">Cancel</button>
                    </div>
                </form>
            </div>
        </div>

        <br><br>

        <!-- Display time_slots -->
        <table style="width:100%">
            <thead>
                <tr>
                    <th>Time Slot ID</th>
                    <th>Day</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0) : ?>
                    <?php while ($row = $result->fetch_assoc()) : ?>
                        <tr>
                            <td style="text-align: center;"><?php echo $row['time_slot_id']; ?></td>    
                            <td style="text-align: center;"><?php echo $row['day']; ?></td>
                            <td style="text-align: center;"><?php echo $row['start_time']; ?></td>
                            <td style="text-align: center;"><?php echo $row['end_time']; ?></td>
                            <td>
                                <!-- Edit and Delete buttons -->
                                <div class="button-container">
                                    <button class="edit-btn" data-time_slot_id="<?php echo $row['time_slot_id']; ?>" data-day="<?php echo $row['day']; ?>" data-start_time="<?php echo $row['start_time']; ?>" data-end_time="<?php echo $row['end_time']; ?>">Edit</button>
                                    <button class="delete-btn" data-time_slot_id="<?php echo $row['time_slot_id']; ?>">Delete</button>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="5">No time slots found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- The Modal for Editing Time Slot -->
        <div id="editModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <!-- Form to Edit Time Slot -->
                <form method="POST" action="/time_slot/time_slot_function.php">
                <br>
                    <input type="hidden" id="edit-time_slot_id" name="time_slot_id">

                    <label for="edit-new_time_slot_id">Time Slot ID:</label>
                    <input type="text" id="edit-new_time_slot_id" name="new_time_slot_id" required>
                    <br><br>

                    <label for="edit-day">Day:</label>
                    <select id="edit-day" name="day" required>
                        <option value="Monday">Monday</option>
                        <option value="Tuesday">Tuesday</option>
                        <option value="Wednesday">Wednesday</option>
                        <option value="Thursday">Thursday</option>
                        <option value="Friday">Friday</option>
                        <option value="Saturday">Saturday</option>
                        <option value="Sunday">Sunday</option>
                    </select>
                    <br><br>

                    <label for="edit-start_time">Start Time:</label>
                    <input type="time" id="edit-start_time" name="start_time" required>
                    <br><br>

                    <label for="edit-end_time">End Time:</label>
                    <input type="time" id="edit-end_time" name="end_time" required>
                    <br><br>

                    <div class="button-container">
                        <input type="submit" name="update_time_slot" value="Update Time Slot">
                        <button type="button" id="cancelEditBtn">Cancel</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- The Modal for Deleting Time Slot -->
        <div id="deleteModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <!-- Confirmation for Deleting time_slot -->
                <p>Are you sure you want to delete this time slot?</p>
                <form method="POST" action="/time_slot/time_slot_function.php">
                    <input type="hidden" id="delete-time_slot_id" name="time_slot_id">
                    <div class="button-container">
                        <input type="submit" name="delete_time_slot" value="Delete Time Slot">
                        <button type="button" id="cancelDeleteBtn">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

<script>
    // Get all edit buttons
    var editButtons = document.getElementsByClassName("edit-btn");
    for (let i = 0; i < editButtons.length; i++) {
        editButtons[i].onclick = function() {
            var time_slot_id = this.getAttribute("data-time_slot_id");
            var day = this.getAttribute("data-day");
            var start_time = this.getAttribute("data-start_time");
            var end_time = this.getAttribute("data-end_time");

            document.getElementById("edit-time_slot_id").value = time_slot_id;
            document.getElementById("edit-new_time_slot_id").value = time_slot_id;
            document.getElementById("edit-day").value = day;
            document.getElementById("edit-start_time").value = start_time;
            document.getElementById("edit-end_time").value = end_time;

            editModal.style.display = "block";
        }
    }

    // Get all delete buttons
    var deleteButtons = document.getElementsByClassName("delete-btn");
    for (let i = 0; i < deleteButtons.length; i++) {
        deleteButtons[i].onclick = function() {
            var time_slot_id = this.getAttribute("data-time_slot_id");

            document.getElementById("delete-time_slot_id").value = time_slot_id;

            deleteModal.style.display = "block";
        }
    }
</script>
<script src="../resources/js/script.js"></script>

<?php
// Close the database connection
$conn->close();
?>