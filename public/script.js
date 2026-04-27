// Mobile menu toggle
const menuToggle = document.querySelector('.menu-toggle');
const navList = document.getElementById('nav-list');
if (menuToggle && navList) {
  menuToggle.addEventListener('click', () => {
    const expanded = menuToggle.getAttribute('aria-expanded') === 'true';
    menuToggle.setAttribute('aria-expanded', String(!expanded));
    navList.classList.toggle('open');
  });
  navList.addEventListener('click', (e) => {
    if (e.target.tagName === 'A') {
      navList.classList.remove('open');
      menuToggle.setAttribute('aria-expanded', 'false');
    }
  });
}

// Mobile side drawer (burger menu)
(() => {
  const toggle = document.querySelector('.menu-toggle');
  const drawer = document.getElementById('mobile-drawer');
  const overlay = document.getElementById('drawer-overlay');
  const closeBtn = drawer ? drawer.querySelector('.drawer-close') : null;
  if (!toggle || !drawer || !overlay) return;

  function openDrawer(){
    drawer.classList.add('open');
    overlay.hidden = false;
    overlay.classList.add('show');
    document.body.style.overflow = 'hidden';
    toggle.setAttribute('aria-expanded','true');
  }
  function closeDrawer(){
    drawer.classList.remove('open');
    overlay.classList.remove('show');
    overlay.hidden = true;
    document.body.style.overflow = '';
    toggle.setAttribute('aria-expanded','false');
  }

  toggle.addEventListener('click', () => {
    if (drawer.classList.contains('open')) closeDrawer(); else openDrawer();
  });
  closeBtn && closeBtn.addEventListener('click', closeDrawer);
  overlay.addEventListener('click', (e)=>{ if(e.target===overlay) closeDrawer(); });
  drawer.addEventListener('click', (e)=>{
    const a = e.target.closest('a');
    if (a) closeDrawer();
  });
})();

// Intersection Observer for fade-in sections
const io = new IntersectionObserver((entries) => {
  for (const entry of entries) {
    if (entry.isIntersecting) {
      entry.target.classList.add('appear');
      io.unobserve(entry.target);
    }
  }
}, { threshold: 0.15 });

for (const el of document.querySelectorAll('.fade-in')) io.observe(el);

// Footer year
const yearEl = document.getElementById('year');
if (yearEl) yearEl.textContent = new Date().getFullYear();

// Contact form validation (basic)
const form = document.getElementById('contact-form');
if (form) {
  // If the form has an action (server submit), don't block default submission
  const hasServerAction = !!form.getAttribute('action');
  form.addEventListener('submit', (e) => {
    // Client-side validation before submit
    const data = new FormData(form);
    const name = (data.get('name') || '').toString().trim();
    const phone = (data.get('phone') || '').toString().trim();
    const type = (data.get('type') || '').toString().trim();

    const errors = [];
    if (name.length < 2) errors.push('يرجى إدخال الاسم');
    if (!/^0?5\d{8}$/.test(phone)) errors.push('يرجى إدخال رقم جوال صحيح');
    if (!type) errors.push('يرجى اختيار نوع المشروع');

    if (errors.length) {
      e.preventDefault();
      alert(errors.join('\n'));
      return;
    }

    if (!hasServerAction) {
      e.preventDefault();
      // Simulated submit when no backend action
      alert('تم استلام طلبك بنجاح! سنعاود الاتصال خلال 24 ساعة.');
      form.reset();
    }
  });
}

// Scroll progress bar and back-to-top button
const progressBar = document.querySelector('.scroll-progress span');
const backToTop = document.querySelector('.back-to-top');
const header = document.querySelector('.header');
const navLinks = document.querySelectorAll('.menu a[href^="#"]');

function updateProgress() {
  const doc = document.documentElement;
  const scrollTop = doc.scrollTop || document.body.scrollTop;
  const height = doc.scrollHeight - doc.clientHeight;
  const progress = height > 0 ? (scrollTop / height) * 100 : 0;
  if (progressBar) progressBar.style.width = progress + '%';

  if (backToTop) {
    if (scrollTop > 400) backToTop.classList.add('show');
    else backToTop.classList.remove('show');
  }

  if (header) {
    if (scrollTop > 10) header.classList.add('scrolled');
    else header.classList.remove('scrolled');
  }
}

window.addEventListener('scroll', updateProgress, { passive: true });
window.addEventListener('resize', updateProgress);
document.addEventListener('DOMContentLoaded', updateProgress);

if (backToTop) {
  backToTop.addEventListener('click', () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });
}

// Active nav link highlighting based on section in view
const sectionIds = Array.from(navLinks).map(a => a.getAttribute('href')).filter(Boolean).map(h => h.replace('#',''));
const sections = sectionIds.map(id => document.getElementById(id)).filter(Boolean);

const activeIO = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    const id = entry.target.id;
    if (entry.isIntersecting) {
      navLinks.forEach(a => {
        const href = a.getAttribute('href');
        const isActive = href && href.replace('#','') === id;
        a.classList.toggle('active', Boolean(isActive));
      });
    }
  });
}, { rootMargin: '-40% 0px -55% 0px', threshold: 0.01 });

sections.forEach(sec => activeIO.observe(sec));

// Lightbox for gallery images
(() => {
  const links = Array.from(document.querySelectorAll('a[data-lightbox="gallery"]'));
  if (!links.length) return;

  const items = links.map((a) => ({ src: a.getAttribute('href'), title: a.dataset.title || '' }));
  let current = 0;

  const overlay = document.createElement('div');
  overlay.className = 'lightbox';
  overlay.innerHTML = `
    <div class="lightbox-content">
      <button class="close" aria-label="إغلاق">✕</button>
      <button class="prev" aria-label="السابق">‹</button>
      <img alt="" />
      <button class="next" aria-label="التالي">›</button>
    </div>
  `;
  document.body.appendChild(overlay);
  const img = overlay.querySelector('img');
  const btnClose = overlay.querySelector('.close');
  const btnPrev = overlay.querySelector('.prev');
  const btnNext = overlay.querySelector('.next');

  function show(index) {
    current = (index + items.length) % items.length;
    img.src = items[current].src;
    img.alt = items[current].title || '';
  }

  function open(index) {
    show(index);
    overlay.classList.add('open');
    document.body.style.overflow = 'hidden';
  }

  function close() {
    overlay.classList.remove('open');
    document.body.style.overflow = '';
  }

  btnClose.addEventListener('click', close);
  btnPrev.addEventListener('click', () => show(current - 1));
  btnNext.addEventListener('click', () => show(current + 1));
  overlay.addEventListener('click', (e) => { if (e.target === overlay) close(); });
  document.addEventListener('keydown', (e) => {
    if (!overlay.classList.contains('open')) return;
    if (e.key === 'Escape') close();
    if (e.key === 'ArrowLeft') show(current - 1);
    if (e.key === 'ArrowRight') show(current + 1);
  });

  links.forEach((a, i) => {
    a.addEventListener('click', (e) => {
      e.preventDefault();
      open(i);
    });
  });
})();
