//Template Name: Grandoria
 
(function() {
  "use strict";

  /* =============================
     # HEADER SCROLL EFFECT
     ============================= */
  const body = document.body;
  const header = document.querySelector('#header');

  function toggleScrolled() {
    if (!header) return;
    const needSticky = header.classList.contains('scroll-up-sticky') ||
                       header.classList.contains('sticky-top') ||
                       header.classList.contains('fixed-top');
    if (!needSticky) return;
    body.classList.toggle('scrolled', window.scrollY > 100);
  }

  window.addEventListener('load', toggleScrolled);
  document.addEventListener('scroll', toggleScrolled);

  /* =============================
     # MOBILE NAV TOGGLE
     ============================= */
  const mobileNavToggleBtn = document.querySelector('.mobile-nav-toggle');
  function toggleMobileNav() {
    body.classList.toggle('mobile-nav-active');
    mobileNavToggleBtn.classList.toggle('bi-list');
    mobileNavToggleBtn.classList.toggle('bi-x');
  }
  if (mobileNavToggleBtn) mobileNavToggleBtn.addEventListener('click', toggleMobileNav);

  // Hide menu on link click (mobile)
  document.querySelectorAll('#navmenu a').forEach(link => {
    link.addEventListener('click', () => {
      if (body.classList.contains('mobile-nav-active')) toggleMobileNav();
    });
  });

  /* =============================
     # PRELOADER
     ============================= */
  window.addEventListener('load', () => {
    document.querySelector('#preloader')?.remove();
  });

  /* =============================
     # SCROLL TO TOP BUTTON
     ============================= */
  const scrollTopBtn = document.querySelector('.scroll-top');
  function toggleScrollTop() {
    if (scrollTopBtn)
      scrollTopBtn.classList.toggle('active', window.scrollY > 100);
  }
  if (scrollTopBtn) {
    scrollTopBtn.addEventListener('click', e => {
      e.preventDefault();
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }
  window.addEventListener('load', toggleScrollTop);
  document.addEventListener('scroll', toggleScrollTop);

  /* =============================
     # AOS (ANIMATION ON SCROLL)
     ============================= */
  function aosInit() {
    if (typeof AOS !== 'undefined') {
      AOS.init({
        duration: 600,
        easing: 'ease-in-out',
        once: true,
        mirror: false
      });
    }
  }
  window.addEventListener('load', aosInit);

  /* =============================
     # PURE COUNTER (FOR ABOUT STATS)
     ============================= */
  if (typeof PureCounter !== 'undefined') {
    new PureCounter();
  }

  /* =============================
     # SWIPER SLIDER (Optional)
     ============================= */
  function initSwiper() {
    if (typeof Swiper === 'undefined') return;
    document.querySelectorAll('.init-swiper').forEach(swiperEl => {
      const configEl = swiperEl.querySelector('.swiper-config');
      if (!configEl) return;
      const config = JSON.parse(configEl.innerHTML.trim());
      new Swiper(swiperEl, config);
    });
  }
  window.addEventListener('load', initSwiper);

})();
