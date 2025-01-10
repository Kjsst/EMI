function toggleSidebar() {
    var sidebar = document.getElementById('sidebar');
    var screenWidth = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;

    if (screenWidth <= 750) {
        // For screens with a width of 600px or less, toggle the sidebar as usual
        if (sidebar.style.width === '250px') {
            sidebar.style.width = '0';
        } else {
            sidebar.style.width = '250px';
        }
    }
}

// ======= DELETE MEMBER ====




// setting tab

function openTab(evt, tabName) {
    var i, tabcontent, tablinks;

    // Hide all tab content
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    // Deactivate all tab buttons
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }

    // Show the specific tab content and mark the button as active
    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.className += " active";
}


// Toggle checkbox container visibility
// document.getElementById("searchInput").addEventListener("click", function() {
//     var checkboxContainer = document.getElementById("checkboxContainer");
//     if (checkboxContainer.style.display === "block") {
//         checkboxContainer.style.display = "none";
//     } else {
//         checkboxContainer.style.display = "block";
//     }
// });

// Close checkbox container if clicked outside
// window.addEventListener("click", function(event) {
//     var checkboxContainer = document.getElementById("checkboxContainer");
//     var searchInput = document.getElementById("searchInput");
//     if (event.target !== checkboxContainer && event.target !== searchInput) {
//         checkboxContainer.style.display = "none";
//     }
// });

// Handle checkbox change
var checkboxes = document.querySelectorAll('input[type="checkbox"]');
checkboxes.forEach(function(checkbox) {
    checkbox.addEventListener('change', function() {
        if (this.checked) {
            // Create a new selected item element
            var selectedItem = document.createElement("div");
            selectedItem.classList.add("selected-item");
            selectedItem.textContent = this.value;

            // Create a delete button for the selected item
            var deleteButton = document.createElement("span");
            deleteButton.classList.add("delete-button");
            deleteButton.innerHTML = "&times;";

            // Add click event listener to delete the selected item
            deleteButton.addEventListener("click", function() {
                selectedItem.remove();
            });

            // Append the delete button to the selected item
            selectedItem.appendChild(deleteButton);

            // Append the selected item to the selected items container
            document.getElementById("selectedItems").appendChild(selectedItem);
        }
    });
});

// Filter checkboxes based on search input
// document.getElementById("searchInput").addEventListener("input", function() {
//     var searchInput = this.value.toLowerCase();
//     var checkboxes = document.querySelectorAll('.checkbox-label');
//
//     checkboxes.forEach(function(checkbox) {
//         var labelText = checkbox.textContent.toLowerCase();
//         if (labelText.includes(searchInput)) {
//             checkbox.style.display = "block";
//         } else {
//             checkbox.style.display = "none";
//         }
//     });
// });

//



