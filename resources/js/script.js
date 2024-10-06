// Modal variables
var addModal = document.getElementById("myModal");
var editModal = document.getElementById("editModal");
var deleteModal = document.getElementById("deleteModal");

var btn = document.getElementById("openModalBtn");
var spans = document.getElementsByClassName("close");

// Open the Add Modal
btn.onclick = function() {
    addModal.style.display = "block";
}

// Close modals when 'X' is clicked
for (let i = 0; i < spans.length; i++) {
    spans[i].onclick = function() {
        addModal.style.display = "none";
        editModal.style.display = "none";
        deleteModal.style.display = "none";
    }
}

// Close the modal if clicked outside
window.onclick = function(event) {
    if (event.target == addModal || event.target == editModal || event.target == deleteModal) {
        addModal.style.display = "none";
        editModal.style.display = "none";
        deleteModal.style.display = "none";
    }
}

// Cancel buttons functionality
document.getElementById("cancelAddBtn").onclick = function() {
    addModal.style.display = "none";
}

document.getElementById("cancelEditBtn").onclick = function() {
    editModal.style.display = "none";
}

document.getElementById("cancelDeleteBtn").onclick = function() {
    deleteModal.style.display = "none";
}