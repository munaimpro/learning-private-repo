// Function to edit user details
function editUser (event) {
    event.preventDefault(); // Prevent default loading

    const editForm = document.getElementById('editUserForm'); // Edit user form
    const editUserButton = document.querySelector('.edit-user');
    const userId = document.getElementById('editUserButton').dataset.id;
    console.log(userId);

    // Sending request and getting response
    const response = axiox.get('');
}