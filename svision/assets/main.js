const cards = Array.from(document.querySelectorAll("details[data-card]"));

function setOpenStyle(card, isOpen) {
    if (isOpen) {
        card.classList.add("bg-slate-100");
        card.classList.remove("bg-slate-50");
    } else {
        card.classList.add("bg-slate-50");
        card.classList.remove("bg-slate-100");
    }
}

// Init state (in case one is open by default)
cards.forEach(card => setOpenStyle(card, card.open));

cards.forEach(card => {
    card.addEventListener("toggle", () => {
        if (!card.open) {
            setOpenStyle(card, false);
            return;
        }

        // Close others
        cards.forEach(other => {
            if (other !== card) {
                other.removeAttribute("open");
                setOpenStyle(other, false);
            }
        });

        // Activate this one
        setOpenStyle(card, true);
    });
});


const animatedItems = document.querySelectorAll('[data-animate="up"]');

  const observer = new IntersectionObserver(
    (entries, obs) => {
      entries.forEach((entry) => {
        if (!entry.isIntersecting) return;

        entry.target.classList.remove('opacity-0', 'translate-y-[120px]', 'translate-y-[220px]');
        entry.target.classList.add('opacity-100', 'translate-y-0');

        const title = entry.target.querySelector('[data-title]');
        if (title && !title.dataset.blueApplied) {
          entry.target.addEventListener(
            'transitionend',
            (e) => {
              if (e.propertyName !== 'transform' && e.propertyName !== 'opacity') return;
              title.classList.replace('text-slate-900', 'text-blue-600');
              title.dataset.blueApplied = 'true';
            },
            { once: true }
          );
        }

        obs.unobserve(entry.target);
      });
    },
    {
      threshold: 0.25,
    }
  );

  animatedItems.forEach((el) => observer.observe(el));


const titles = document.querySelectorAll(".section-title");

const observer2 = new IntersectionObserver(
  (entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.replace("text-slate-900", "text-blue-500");
        entry.target.classList.remove("text-slate-400"); // in case some start muted
      } else {
        entry.target.classList.remove("text-blue-500");
        entry.target.classList.add("text-slate-900"); // back to your original color
      }
    });
  },
  { threshold: 0.6, rootMargin: "0px 0px -10% 0px" }
);

titles.forEach((el) => observer2.observe(el));


const sections = document.querySelectorAll("[data-scroll]");
const navLinks = document.querySelectorAll("[data-link]");

const TOP_LOCK_PX = 120;  // nav height + a bit
let currentId = "home";

function setActive(id) {
  currentId = id;
  navLinks.forEach(link => {
    link.dataset.active = (link.getAttribute("href") === `#${id}`) ? "true" : "false";
  });
}

// ✅ initial
setActive("home");

// ✅ click highlights immediately, scrollspy will override after
navLinks.forEach(link => {
  link.addEventListener("click", () => {
    const id = link.getAttribute("href").slice(1);
    setActive(id);
  });
});

// ✅ observer updates currentId based on what becomes visible
const observer3 = new IntersectionObserver((entries) => {
  if (window.scrollY < TOP_LOCK_PX) return;

  const visible = entries
    .filter(e => e.isIntersecting)
    .sort((a, b) => b.intersectionRatio - a.intersectionRatio)[0];

  if (visible) setActive(visible.target.id);
}, {
  threshold: [0.15, 0.3, 0.5],
  // less aggressive so intersections actually happen
  rootMargin: "-10% 0px -55% 0px"
});

sections.forEach(s => observer3.observe(s));

// ✅ Always enforce HOME near top
window.addEventListener("scroll", () => {
  if (window.scrollY < TOP_LOCK_PX) setActive("home");
}, { passive: true });

// ✅ IMPORTANT: handle page-load with hash (#contact etc.)
window.addEventListener("load", () => {
  const id = location.hash?.slice(1);
  if (id) setActive(id);
});



