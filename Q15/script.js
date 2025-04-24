function register() {
  const user = document.getElementById("signupUser").value.trim();
  const pass = document.getElementById("signupPass").value.trim();
  const error = document.getElementById("signupError");

  if (user === "" || pass === "") {
    error.textContent = "Both fields are required!";
    return;
  }

  localStorage.setItem("storedUser", user);
  localStorage.setItem("storedPass", pass);

  window.location.href = "login.html";
}

function login() {
  const user = document.getElementById("loginUser").value.trim();
  const pass = document.getElementById("loginPass").value.trim();
  const error = document.getElementById("loginError");

  const storedUser = localStorage.getItem("storedUser");
  const storedPass = localStorage.getItem("storedPass");

  if (user === "" || pass === "") {
    error.textContent = "Both fields are required!";
    return;
  }

  if (user === storedUser && pass === storedPass) {
    error.textContent = "";
    localStorage.setItem("loggedInUser", user);
    window.location.href = "welcome.html";
  } else {
    error.textContent = "Invalid credentials!";
  }
}

window.addEventListener("DOMContentLoaded", () => {
  const displayUser = document.getElementById("displayUser");
  if (displayUser) {
    const loggedInUser = localStorage.getItem("loggedInUser");
    if (loggedInUser) {
      displayUser.textContent = loggedInUser;
    } else {
      window.location.href = "login.html";
    }
  }
});
