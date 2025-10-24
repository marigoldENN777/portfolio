const signupForm = document.getElementById('signupForm');
const signupStatus = document.querySelector('.status');
const toggleButtons = document.querySelectorAll('.toggle-password');

toggleButtons.forEach(btn => {
  btn.addEventListener('click', () => {
    const target = document.getElementById(btn.dataset.target);
    const isPassword = target.type === 'password';
    target.type = isPassword ? 'text' : 'password';
    btn.textContent = isPassword ? '🙈' : '👁️';
  });
});

signupForm.addEventListener('submit', e => {
  e.preventDefault();

  const password = document.getElementById('password').value;
  const confirm = document.getElementById('confirmPassword').value;

  if (password !== confirm) {
    signupStatus.textContent = "Passwords do not match ❌";
    signupStatus.style.color = "#ff4d4d";
    return;
  }

  signupStatus.textContent = "Creating account...";
  signupStatus.style.color = "#aaa";

  setTimeout(() => {
    signupStatus.textContent = "Account created successfully ✅";
    signupStatus.style.color = "#00ffc8";
    signupForm.reset();
  }, 1500);
});
