document.addEventListener('DOMContentLoaded', () => {
    // Navbar scroll effect
    const navbar = document.getElementById('navbar');
    if (navbar) {
        window.addEventListener('scroll', () => {
            navbar.classList.toggle('scrolled', window.scrollY > 50);
        }, { passive: true });
    }

    // Mobile menu
    const menuBtn = document.getElementById('mobile-menu-btn');
    const closeBtn = document.getElementById('close-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');

    const openMenu = () => {
        mobileMenu?.classList.remove('hidden');
        menuBtn?.setAttribute('aria-expanded', 'true');
        mobileMenu?.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
    };
    const closeMenu = () => {
        mobileMenu?.classList.add('hidden');
        menuBtn?.setAttribute('aria-expanded', 'false');
        mobileMenu?.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
    };

    menuBtn?.addEventListener('click', openMenu);
    closeBtn?.addEventListener('click', closeMenu);
    mobileMenu?.querySelectorAll('a').forEach(link => link.addEventListener('click', closeMenu));

    // Back to top
    const backToTop = document.getElementById('backToTop');
    if (backToTop) {
        window.addEventListener('scroll', () => {
            const show = window.scrollY > 400;
            backToTop.classList.toggle('opacity-0', !show);
            backToTop.classList.toggle('invisible', !show);
        }, { passive: true });
        backToTop.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
    }

    // Scroll reveal
    const revealEls = document.querySelectorAll('.scroll-reveal');
    if (revealEls.length && 'IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('revealed');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });
        revealEls.forEach(el => observer.observe(el));
    } else {
        revealEls.forEach(el => el.classList.add('revealed'));
    }

    // FAQ accordion
    document.querySelectorAll('.faq-item').forEach(item => {
        const btn = item.querySelector('.faq-question');
        btn?.addEventListener('click', () => {
            const wasActive = item.classList.contains('active');
            document.querySelectorAll('.faq-item.active').forEach(i => i.classList.remove('active'));
            if (!wasActive) item.classList.add('active');
        });
    });

    // Count-up animation
    const counters = document.querySelectorAll('[data-count]');
    if (counters.length && 'IntersectionObserver' in window) {
        const countObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (!entry.isIntersecting) return;
                const el = entry.target;
                const target = parseInt(el.dataset.count, 10);
                const suffix = el.dataset.suffix || '';
                let current = 0;
                const step = Math.ceil(target / 40);
                const timer = setInterval(() => {
                    current += step;
                    if (current >= target) { current = target; clearInterval(timer); }
                    el.textContent = current + suffix;
                }, 30);
                countObserver.unobserve(el);
            });
        }, { threshold: 0.5 });
        counters.forEach(el => countObserver.observe(el));
    }
});
