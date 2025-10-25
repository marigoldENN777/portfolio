const form = document.getElementById('contactForm');
const status = document.querySelector('.status');

form.addEventListener('submit', e => {
  e.preventDefault();
  status.textContent = "Sending...";
  status.style.color = "#aaa";

  setTimeout(() => {
    status.textContent = "Message sent successfully ✅";
    status.style.color = "#00ffc8";
    form.reset();
  }, 1500);
});
