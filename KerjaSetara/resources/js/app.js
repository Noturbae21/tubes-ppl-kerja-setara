// ─── Stat Counter Animation ────────────────────────────────────────────────
function animateCounters() {
    const counters = document.querySelectorAll('[data-count]');
    counters.forEach(counter => {
        const target = parseInt(counter.getAttribute('data-count'));
        const suffix = counter.getAttribute('data-suffix') || '';
        const duration = 2000;
        const step = target / (duration / 16);
        let current = 0;

        const update = () => {
            current += step;
            if (current < target) {
                counter.textContent = Math.floor(current).toLocaleString('id-ID') + suffix;
                requestAnimationFrame(update);
            } else {
                counter.textContent = target.toLocaleString('id-ID') + suffix;
            }
        };
        requestAnimationFrame(update);
    });
}

// ─── Intersection Observer for animations ─────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    // Counter animation on scroll into view
    const statsSection = document.querySelector('#stats-section');
    if (statsSection) {
        let animated = false;
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !animated) {
                    animated = true;
                    animateCounters();
                }
            });
        }, { threshold: 0.3 });
        observer.observe(statsSection);
    }

    // Fade-in elements on scroll
    const fadeElements = document.querySelectorAll('.reveal-on-scroll');
    if (fadeElements.length) {
        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach((entry, i) => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.classList.add('animate-fade-in-up');
                        entry.target.style.opacity = '1';
                    }, i * 100);
                    revealObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });

        fadeElements.forEach(el => {
            el.style.opacity = '0';
            revealObserver.observe(el);
        });
    }
});
