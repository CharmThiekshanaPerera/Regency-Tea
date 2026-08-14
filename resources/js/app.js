import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

/* ------------------------------------------------------------------
 * Mobile navigation
 * ---------------------------------------------------------------- */
document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.querySelector('[data-menu-toggle]');
    const menu = document.querySelector('[data-mobile-menu]');

    if (toggle && menu) {
        toggle.addEventListener('click', () => {
            const open = menu.classList.toggle('hidden') === false;
            toggle.setAttribute('aria-expanded', String(open));
        });
    }

    initHero();
    initScrollReveal();
});

/* ------------------------------------------------------------------
 * Homepage hero — replaces Slider Revolution.
 * Scroll-snap carousel: no dependency, keyboard accessible, and it
 * degrades to a horizontal scroller if JS fails.
 * ---------------------------------------------------------------- */
function initHero() {
    const hero = document.querySelector('[data-hero]');
    if (!hero) return;

    const track = hero.querySelector('[data-hero-track]');
    const dots = [...hero.querySelectorAll('[data-hero-dot]')];
    const slides = [...track.children];
    if (slides.length < 2) return;

    let index = 0;
    let timer = null;

    const go = (i) => {
        index = (i + slides.length) % slides.length;
        track.scrollTo({ left: slides[index].offsetLeft, behavior: 'smooth' });
        dots.forEach((d, n) => d.classList.toggle('bg-white', n === index));
    };

    const start = () => { timer = setInterval(() => go(index + 1), 6000); };
    const stop = () => { clearInterval(timer); timer = null; };

    dots.forEach((dot, i) => dot.addEventListener('click', () => { stop(); go(i); start(); }));

    hero.addEventListener('mouseenter', stop);
    hero.addEventListener('mouseleave', start);
    hero.addEventListener('focusin', stop);

    hero.setAttribute('tabindex', '0');
    hero.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowRight') { stop(); go(index + 1); }
        if (e.key === 'ArrowLeft') { stop(); go(index - 1); }
    });

    // Respect users who asked for reduced motion.
    if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches) start();
    go(0);
}

/* ------------------------------------------------------------------
 * Scroll reveal — fades/slides [data-reveal] elements in as they
 * enter the viewport, and counts up any [data-count-to] number
 * inside them. Falls back to fully-visible, no counting, if the
 * browser lacks IntersectionObserver or the user prefers less motion.
 * ---------------------------------------------------------------- */
function initScrollReveal() {
    const els = [...document.querySelectorAll('[data-reveal]')];
    if (!els.length) return;

    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    const reveal = (el) => {
        el.classList.add('revealed');
        el.querySelectorAll('[data-count-to]').forEach(animateCount);
    };

    if (reduceMotion || !('IntersectionObserver' in window)) {
        els.forEach(reveal);
        return;
    }

    const io = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                reveal(entry.target);
                io.unobserve(entry.target);
            }
        });
    }, { threshold: 0.15, rootMargin: '0px 0px -40px 0px' });

    els.forEach((el) => io.observe(el));
}

function animateCount(el) {
    const raw = el.dataset.countTo;
    const target = parseFloat(raw);
    if (Number.isNaN(target) || el.dataset.counted) return;
    el.dataset.counted = '1';

    const suffix = el.dataset.countSuffix || '';
    // Preserve the target's own decimal precision (e.g. "2.75") instead of
    // always rounding to a whole number, and thousand-separate large integers.
    const decimals = (raw.split('.')[1] || '').length;
    const format = (n) => decimals > 0 ? n.toFixed(decimals) : Math.round(n).toLocaleString('en-US');
    const duration = 1200;
    const start = performance.now();

    const tick = (now) => {
        const progress = Math.min((now - start) / duration, 1);
        const eased = 1 - Math.pow(1 - progress, 3);
        el.textContent = format(target * eased) + suffix;
        if (progress < 1) requestAnimationFrame(tick);
        else el.textContent = format(target) + suffix;
    };

    requestAnimationFrame(tick);
}
