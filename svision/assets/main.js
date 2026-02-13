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
                    }, { once: true }
                );
            }

            obs.unobserve(entry.target);
        });
    }, {
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
    }, { threshold: 0.6, rootMargin: "0px 0px -10% 0px" }
);

titles.forEach((el) => observer2.observe(el));


const sections = document.querySelectorAll("[data-scroll]");
const navLinks = document.querySelectorAll("[data-link]");

const TOP_LOCK_PX = 120; // nav height + a bit
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


(() => {
    const form = document.getElementById('contactForm');
    if (!form) return;

    // ostatak tvog koda...
    const alertBox = document.getElementById('form-alert');
    const submitBtn = document.getElementById('submitBtn');

    const fields = [
        { id: 'name', errorId: 'name-error', label: 'Ime' },
        { id: 'subject', errorId: 'subject-error', label: 'Naslov' },
        { id: 'contact', errorId: 'contact-error', label: 'Email / Broj tel.' },
        { id: 'message', errorId: 'message-error', label: 'Poruka' },
    ];

    let hideTimer = null;

    function showAlert(type, text, autoHideMs = 4000) {
        // reset classes
        alertBox.className = "rounded-xl border px-4 py-3 text-sm";
        alertBox.classList.remove('hidden');
        alertBox.textContent = text;

        if (type === 'success') {
            alertBox.classList.add('border-emerald-200', 'bg-emerald-50', 'text-emerald-800');
        } else if (type === 'error') {
            alertBox.classList.add('border-red-200', 'bg-red-50', 'text-red-800');
        } else {
            alertBox.classList.add('border-slate-200', 'bg-slate-50', 'text-slate-800');
        }

        if (hideTimer) clearTimeout(hideTimer);
        hideTimer = setTimeout(() => {
            alertBox.classList.add('hidden');
            alertBox.textContent = '';
        }, autoHideMs);
    }

    function clearFieldError(fieldId, errorId) {
  const input = document.getElementById(fieldId);
  const err = document.getElementById(errorId);
  if (!input || !err) return;

  err.textContent = '';
  err.classList.add('hidden');

  input.classList.remove('border-red-400');
  input.classList.add('border-slate-300');
}

function setFieldError(fieldId, errorId, msg) {
  const input = document.getElementById(fieldId);
  const err = document.getElementById(errorId);
  if (!input || !err) return;

  err.textContent = msg;
  err.classList.remove('hidden');

  input.classList.remove('border-slate-300');
  input.classList.add('border-red-400');
}


    function clearAllFieldErrors() {
        fields.forEach(f => clearFieldError(f.id, f.errorId));
    }

    function autoHideFieldErrors(ms = 4000) {
        setTimeout(() => {
            fields.forEach(f => {
                const err = document.getElementById(f.errorId);
                if (!err.classList.contains('hidden')) {
                    err.classList.add('hidden');
                    err.textContent = '';
                }
            });
        }, ms);
    }

    function validate() {
        clearAllFieldErrors();

        let ok = true;

        for (const f of fields) {
            const el = document.getElementById(f.id);
            const val = String(el?.value ?? '').trim();

            // Preskoči contact ovde, jer ga validiramo ispod posebno
            if (f.id === 'contact') continue;

            if (!val) {
                setFieldError(f.id, f.errorId, `${f.label} je obavezno.`);
                ok = false;
            } else if (f.id === 'message' && val.length < 10) {
                setFieldError(f.id, f.errorId, `Poruka je prekratka (min 10 karaktera).`);
                ok = false;
            }
        }


        // optional: contact must look like email or phone
        const contactEl = document.getElementById('contact');
        const contact = (contactEl?.value || '').trim();

        console.log('contactEl:', contactEl);
        console.log('contact value:', contactEl?.value, 'trim:', contact);


        if (!contact) {
  setFieldError('contact', 'contact-error', 'Email / Broj tel. je obavezno.');
  ok = false;
} else {
  if (contact.includes('@')) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(contact)) {
      setFieldError('contact', 'contact-error', 'Unesi validan email.');
      ok = false;
    }
  } else {
    const digitsOnly = contact.replace(/\D/g, '');
    if (digitsOnly.length < 6) {
      setFieldError('contact', 'contact-error', 'Unesi validan broj telefona.');
      ok = false;
    }
  }
}



        if (!ok) {
            showAlert('error', 'Molimo popunite polja označena crveno.');
            autoHideFieldErrors(4500);
        }

        return ok;
    }

    // Clear error per-field on typing
    fields.forEach(f => {
        const el = document.getElementById(f.id);
        if (!el) return;
        el.addEventListener('input', () => clearFieldError(f.id, f.errorId));
    });


    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        if (!validate()) return;

        // UX
        submitBtn.disabled = true;
        submitBtn.classList.add('opacity-80', 'cursor-not-allowed');
        const oldText = submitBtn.textContent;
        submitBtn.textContent = 'Slanje...';

        try {
            const formData = new FormData(form);

            const res = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            let data = null;
try {
  data = await res.json();
} catch {
  data = { ok: false, message: 'Server nije vratio validan odgovor (JSON).' };
}

// Ako server vrati fieldErrors, prikaži ih
if (data.fieldErrors && typeof data.fieldErrors === 'object') {
  Object.entries(data.fieldErrors).forEach(([fieldId, msg]) => {
    const f = fields.find(x => x.id === fieldId);
    if (f) setFieldError(f.id, f.errorId, String(msg));
  });
  autoHideFieldErrors(5000);
}

if (data.ok) {
  showAlert('success', data.message || 'Hvala! Vaša poruka je poslata. Javićemo se uskoro.', 5000);
  form.reset();
  clearAllFieldErrors();
} else {
  showAlert('error', data.message || 'Došlo je do greške. Pokušajte ponovo.', 5000);
}

        } catch (err) {
            showAlert('error', 'Greška u komunikaciji sa serverom. Pokušajte ponovo.', 5000);
        } finally {
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-80', 'cursor-not-allowed');
            submitBtn.textContent = oldText;
        }
    });

})();