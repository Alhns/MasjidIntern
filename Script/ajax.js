function updateRole(selectElement, ic) {
    var selectedRole = selectElement.value; // Get selected role

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../backend/update_role.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                alert("Role updated successfully!"); // Success message
            } else {
                alert("Error updating role. Please try again."); // Error message
            }
        }
    };

    // Send data to the server
    xhr.send("ic=" + encodeURIComponent(ic) + "&role=" + encodeURIComponent(selectedRole));
}
