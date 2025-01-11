document.getElementById("loginForm").addEventListener("submit", function(event) {
    const idNumber = document.getElementById("idNumber").value;
    const phoneNumber = document.getElementById("phoneNumber").value;
    const password = document.getElementById("password").value;

    const idRegex = /^\d{4}$/;
    const phoneRegex = /^\d{3}[- ]?\d{3}[- ]?\d{4}$/;
    const passwordRegex = /^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*]).{5}$/;

    if (!idRegex.test(idNumber)) {
        alert("Plumber ID must be exactly 4 digits.");
        event.preventDefault();
        return;
    }

    if (!phoneRegex.test(phoneNumber)) {
        alert("Phone number must be valid (e.g., 123-456-7890).");
        event.preventDefault();
        return;
    }

    if (!passwordRegex.test(password)) {
        alert("Password must be 5 characters long with at least one uppercase letter, one number, and one special character.");
        event.preventDefault();
        return;
    }
});

document.getElementById('toggle-password').addEventListener('click', function () {
    const passwordInput = document.getElementById('password');
    const type = passwordInput.getAttribute('type');
    passwordInput.setAttribute('type', type === 'password' ? 'text' : 'password');
});
