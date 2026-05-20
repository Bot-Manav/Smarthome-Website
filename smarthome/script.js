// Device toggle functionality
const toggleButtons = document.querySelectorAll('.toggleBtn');

toggleButtons.forEach(btn => {
  btn.addEventListener('click', () => {
    const status = btn.previousElementSibling.querySelector('.status');
    if (status) {
      if (status.textContent === "OFF" || status.textContent === "CLOSED") {
        status.textContent = "ON";
      } else {
        status.textContent = "OFF";
      }
    }
  });
});

// Simulated login/logout functionality (Fix null pointer checks)
const loginBtn = document.getElementById('loginBtn');
const logoutBtn = document.getElementById('logoutBtn');

if (loginBtn) {
  loginBtn.addEventListener('click', () => {
    loginBtn.style.display = 'none';
    if (logoutBtn) logoutBtn.style.display = 'inline-block';
    alert("Logged in successfully!");
  });
}

if (logoutBtn) {
  logoutBtn.addEventListener('click', () => {
    logoutBtn.style.display = 'none';
    if (loginBtn) loginBtn.style.display = 'inline-block';
    alert("Logged out successfully!");
  });
}

// =========================================
// PAGE TRANSITIONS & PARALLAX LOGIC
// =========================================

document.addEventListener('DOMContentLoaded', () => {
  // Fade out transition overlay
  const overlay = document.querySelector('.page-transition-overlay');
  if (overlay) {
    overlay.classList.add('fade-out');
  }

  // Intercept normal links for page transition fade-in
  const links = document.querySelectorAll('a:not([target="_blank"]):not([href^="#"]):not([href^="mailto:"]):not([href^="tel:"])');
  links.forEach(link => {
    link.addEventListener('click', (e) => {
      const href = link.getAttribute('href');
      if (href && !href.startsWith('javascript:')) {
        e.preventDefault();
        if (overlay) {
          overlay.classList.remove('fade-out');
          setTimeout(() => {
            window.location.href = href;
          }, 400); // 400ms match styles.css transition
        } else {
          window.location.href = href;
        }
      }
    });
  });

  // Intercept button inline redirects
  const navButtons = document.querySelectorAll('button[onclick]');
  navButtons.forEach(btn => {
    const onclickAttr = btn.getAttribute('onclick');
    if (onclickAttr && onclickAttr.includes('window.location.href')) {
      const match = onclickAttr.match(/window\.location\.href\s*=\s*['"]([^'"]+)['"]/);
      if (match && match[1]) {
        const href = match[1];
        // Remove original inline onclick to prevent double trigger / instant navigation
        btn.removeAttribute('onclick');
        btn.addEventListener('click', (e) => {
          e.preventDefault();
          if (overlay) {
            overlay.classList.remove('fade-out');
            setTimeout(() => {
              window.location.href = href;
            }, 400);
          } else {
            window.location.href = href;
          }
        });
      }
    }
  });
});

// Reset overlay if navigating back/forward via bfcache
window.addEventListener('pageshow', (event) => {
  if (event.persisted) {
    const overlay = document.querySelector('.page-transition-overlay');
    if (overlay) {
      overlay.classList.add('fade-out');
    }
  }
});

// Throttled Parallax scrolling effect
let ticking = false;
window.addEventListener('scroll', () => {
  if (!ticking) {
    window.requestAnimationFrame(() => {
      const scrolled = window.pageYOffset || document.documentElement.scrollTop;
      const parallaxElements = document.querySelectorAll('.parallax-shape');
      parallaxElements.forEach(el => {
        const speed = parseFloat(el.getAttribute('data-speed')) || 0.15;
        const yPos = scrolled * speed;
        el.style.transform = `translate3d(0, ${yPos}px, 0)`;
      });
      ticking = false;
    });
    ticking = true;
  }
});
