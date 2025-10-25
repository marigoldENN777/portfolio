const subscribeForm = document.getElementById('subscribeForm');
const subscribeStatus = document.querySelector('.subscribe-section .status');

subscribeForm.addEventListener('submit', e => {
  e.preventDefault();

  subscribeStatus.textContent = "Subscribing...";
  subscribeStatus.style.color = "#aaa";

  setTimeout(() => {
    subscribeStatus.textContent = "Subscribed successfully ✅";
    subscribeStatus.style.color = "#00ffc8";
    subscribeForm.reset();
  }, 1500);
});
