(function ($) {
  'use strict';

  const translations = {
    en: {
      heroTitle: 'Explore Surat With AV Traveller Vlogs',
      heroSubtitle: 'Travel More · Explore More · Rent Better',
      navBook: 'Book Now',
      navBookMobile: 'Book Now'
    },
    hi: {
      heroTitle: 'AV Traveller Vlogs के साथ सूरत घूमें',
      heroSubtitle: 'ज्यादा यात्रा · ज्यादा खोज · बेहतर कार किराया',
      navBook: 'बुक करें',
      navBookMobile: 'बुक करें'
    },
    gu: {
      heroTitle: 'AV Traveller Vlogs સાથે સુરત એક્સપ્લોર કરો',
      heroSubtitle: 'વધુ પ્રવાસ · વધુ શોધ · વધુ સારી કાર ભાડે',
      navBook: 'બુક કરો',
      navBookMobile: 'બુક કરો'
    }
  };

  const setTheme = (theme) => {
    document.documentElement.setAttribute('data-theme', theme);
    localStorage.setItem('av-theme', theme);
    $('#themeToggle i').toggleClass('fa-sun', theme === 'light').toggleClass('fa-moon', theme !== 'light');
  };

  const applyLanguage = (lang) => {
    const dict = translations[lang] || translations.en;
    $('[data-i18n]').each(function () {
      const key = $(this).data('i18n');
      if (dict[key]) {
        $(this).text(dict[key]);
      }
    });
    localStorage.setItem('av-language', lang);
  };

  const initHeroSlides = () => {
    const slides = $('.hero-slide');
    if (!slides.length) return;
    let index = 0;
    setInterval(() => {
      slides.eq(index).removeClass('active');
      index = (index + 1) % slides.length;
      slides.eq(index).addClass('active');
    }, 5200);
  };

  const initCounters = () => {
    const counters = document.querySelectorAll('.counter');
    if (!counters.length) return;
    const observer = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (!entry.isIntersecting) return;
        const el = entry.target;
        const target = parseInt(el.dataset.count || '0', 10);
        let current = 0;
        const step = Math.max(1, Math.ceil(target / 40));
        const timer = setInterval(() => {
          current += step;
          if (current >= target) {
            current = target;
            clearInterval(timer);
          }
          el.textContent = current;
        }, 28);
        observer.unobserve(el);
      });
    }, { threshold: 0.45 });
    counters.forEach((counter) => observer.observe(counter));
  };

  const initCarTools = () => {
    const grid = $('.car-grid');
    if (!grid.length) return;

    const filterCars = () => {
      const query = ($('#carSearch').val() || '').toString().toLowerCase();
      const status = $('#carFilter').val() || 'all';
      $('.car-item').each(function () {
        const item = $(this);
        const matchesText = item.data('name').toString().includes(query);
        const matchesStatus = status === 'all' || item.data('status') === status;
        item.toggle(matchesText && matchesStatus);
      });
    };

    const sortCars = () => {
      const mode = $('#carSort').val();
      if (mode === 'default') return;
      const items = $('.car-item').get().sort((a, b) => {
        const priceA = Number($(a).data('price'));
        const priceB = Number($(b).data('price'));
        return mode === 'low' ? priceA - priceB : priceB - priceA;
      });
      items.forEach((item) => grid.append(item));
    };

    $('#carSearch, #carFilter').on('input change', filterCars);
    $('#carSort').on('change', function () {
      sortCars();
      filterCars();
    });
  };

  const initAjaxUtilities = () => {
    $('.ajax-newsletter').on('submit', function (event) {
      event.preventDefault();
      const form = $(this);
      const status = $('.newsletter-status');
      status.text('Subscribing...');
      $.post(form.attr('action'), form.serialize())
        .done((response) => status.text(response.message || 'Subscribed.'))
        .fail((xhr) => status.text(xhr.responseJSON?.message || 'Could not subscribe right now.'));
    });

    $('.ajax-contact').on('submit', function (event) {
      event.preventDefault();
      const form = $(this);
      const status = $('.contact-status');
      status.text('Sending...');
      $.post(form.attr('action'), form.serialize())
        .done((response) => {
          status.text(response.message || 'Message sent.');
          form.trigger('reset');
        })
        .fail((xhr) => status.text(xhr.responseJSON?.message || 'Could not send message.'));
    });

    $('.ajax-track').on('submit', function (event) {
      event.preventDefault();
      const form = $(this);
      const result = $('#trackingResult');
      result.html('<p class="form-note">Checking booking...</p>');
      $.post(form.attr('action'), form.serialize())
        .done((response) => {
          const booking = response.booking;
          result.html(`
            <div class="tracking-card">
              <strong>${booking.booking_code}</strong>
              <span>Car: ${booking.car_name}</span>
              <span>Dates: ${booking.pickup_date} to ${booking.return_date}</span>
              <span>Booking: ${booking.status}</span>
              <span>Payment: ${booking.payment_status}</span>
              <span>Estimate: ₹${Number(booking.estimated_amount || 0).toLocaleString('en-IN')}</span>
            </div>
          `);
        })
        .fail((xhr) => result.html(`<p class="form-note">${xhr.responseJSON?.message || 'Booking not found.'}</p>`));
    });
  };

  const initLibraries = () => {
    if (window.AOS) {
      AOS.init({ duration: 750, once: true, offset: 80 });
    }

    if (window.Swiper) {
      new Swiper('.placeSwiper', {
        slidesPerView: 1,
        spaceBetween: 18,
        pagination: { el: '.placeSwiper .swiper-pagination', clickable: true },
        breakpoints: { 768: { slidesPerView: 2 }, 1200: { slidesPerView: 3 } }
      });

      new Swiper('.reviewSwiper', {
        slidesPerView: 1,
        spaceBetween: 18,
        pagination: { el: '.reviewSwiper .swiper-pagination', clickable: true },
        breakpoints: { 768: { slidesPerView: 2 }, 1200: { slidesPerView: 3 } }
      });
    }

    if (window.GLightbox) {
      GLightbox({ selector: '.glightbox', touchNavigation: true, loop: true });
    }

    if (window.gsap) {
      gsap.from('.hero-copy .eyebrow, .hero-copy h1, .hero-copy p, .hero-actions', {
        y: 24,
        opacity: 0,
        duration: 0.8,
        stagger: 0.12,
        delay: 0.2,
        ease: 'power2.out'
      });
    }
  };

  const initBackTop = () => {
    const button = $('#backTop');
    $(window).on('scroll', function () {
      button.toggleClass('show', window.scrollY > 700);
    });
    button.on('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
  };

  const initPwa = () => {
    if ('serviceWorker' in navigator) {
      window.addEventListener('load', () => {
        navigator.serviceWorker.register('sw.js').catch(() => {});
      });
    }
  };

  $(function () {
    setTimeout(() => $('.page-loader').addClass('hide'), 500);
    setTheme(localStorage.getItem('av-theme') || 'dark');
    const savedLang = localStorage.getItem('av-language') || 'en';
    $('#languageSelect').val(savedLang);
    applyLanguage(savedLang);

    $('#themeToggle').on('click', function () {
      const nextTheme = document.documentElement.getAttribute('data-theme') === 'light' ? 'dark' : 'light';
      setTheme(nextTheme);
    });

    $('#languageSelect').on('change', function () {
      applyLanguage($(this).val());
    });

    initLibraries();
    initHeroSlides();
    initCounters();
    initCarTools();
    initAjaxUtilities();
    initBackTop();
    initPwa();
  });
})(jQuery);
