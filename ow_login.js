function togglePassword() {
    const passwordField = document.getElementById('password');
    const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordField.setAttribute('type', type);
}

function validateForm() {
    const fields = document.querySelectorAll('.textbox input, .textbox select');
    for (const field of fields) {
        if (!field.value.trim()) {
            alert('Please fill out all fields.');
            return false;
        }
    }
    return true;
}

// Show/hide 'Other' text area when "Others" option is selected
document.getElementById('machineType').addEventListener('change', function () {
    const otherMachineText = document.getElementById('otherMachineText');
    if (this.value === 'others') {
        otherMachineText.style.display = 'block';
    } else {
        otherMachineText.style.display = 'none';
    }
});
