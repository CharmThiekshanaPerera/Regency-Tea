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
