document.getElementById("registrationForm").addEventListener("submit", function(event) {
    event.preventDefault();
    
    let valid = true;

    let firstName = document.getElementById("firstName").value.trim();
    if (firstName === "") {
        document.getElementById("firstNameError").innerText = "First Name is required";
        valid = false;
    } else {
        document.getElementById("firstNameError").innerText = "";
    }

    let lastName = document.getElementById("lastName").value.trim();
    if (lastName === "") {
        document.getElementById("lastNameError").innerText = "Last Name is required";
        valid = false;
    } else {
        document.getElementById("lastNameError").innerText = "";
    }

    let email = document.getElementById("email").value.trim();
    let emailPattern = /\S+@\S+\.\S+/;
    if (email === "") {
        document.getElementById("emailError").innerText = "Email is required";
        valid = false;
    } else if (!emailPattern.test(email)) {
        document.getElementById("emailError").innerText = "Enter a valid email";
        valid = false;
    } else {
        document.getElementById("emailError").innerText = "";
    }

    let password = document.getElementById("password").value;
    if (password.length < 6) {
        document.getElementById("passwordError").innerText = "Password must be at least 6 characters";
        valid = false;
    } else {
        document.getElementById("passwordError").innerText = "";
    }

    let genderSelected = document.querySelector('input[name="gender"]:checked');
    if (!genderSelected) {
        document.getElementById("genderError").innerText = "Please select a gender";
        valid = false;
    } else {
        document.getElementById("genderError").innerText = "";
    }

    let dob = document.getElementById("dob").value;
    if (dob === "") {
        document.getElementById("dobError").innerText = "Date of Birth is required";
        valid = false;
    } else {
        document.getElementById("dobError").innerText = "";
    }

    if (valid) {
        alert("Registration Successful!");
        document.getElementById("registrationForm").reset();
    }
});
