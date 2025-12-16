// Device toggle functionality
const toggleButtons = document.querySelectorAll('.toggleBtn');

toggleButtons.forEach(btn => {
  btn.addEventListener('click', () => {
    const status = btn.previousElementSibling.querySelector('.status');
    if(status.textContent === "OFF" || status.textContent === "CLOSED") {
      status.textContent = "ON";
    } else {
      status.textContent = "OFF";
    }
  });
});

// Simulated login/logout functionality
const loginBtn = document.getElementById('loginBtn');
const logoutBtn = document.getElementById('logoutBtn');

loginBtn.addEventListener('click', () => {
  loginBtn.style.display = 'none';
  logoutBtn.style.display = 'inline-block';
  alert("Logged in successfully!");
});

logoutBtn.addEventListener('click', () => {
  logoutBtn.style.display = 'none';
  loginBtn.style.display = 'inline-block';
  alert("Logged out successfully!");
});
