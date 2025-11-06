const words = ["Spectacular", "Modern", "Designs", "@Nexa"];
const text = document.getElementById("typewriter") ?? document.getElementById("v2-typewriter");

let wordIndex = 0;
let charIndex = 0;
let deleting = false;

function type() {
  const currentWord = words[wordIndex];
  
  if (!deleting) {
    text.textContent = currentWord.substring(0, charIndex + 1);
    charIndex++;

    if (charIndex === currentWord.length) {
      deleting = true;
      setTimeout(type, 1000); // pause before deleting
      return;
    }
  } else {
    text.textContent = currentWord.substring(0, charIndex - 1);
    charIndex--;

    if (charIndex === 0) {
      deleting = false;
      wordIndex = (wordIndex + 1) % words.length;
    }
  }

  const speed = deleting ? 80 : 120;
  setTimeout(type, speed);
}

type();
