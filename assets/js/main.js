/**
 * Kalpoink - Main JavaScript
 * Digital Marketing Agency Website
 */

// Wait for DOM to load
document.addEventListener('DOMContentLoaded', function () {
    // Initialize AOS (Animate on Scroll)
    AOS.init({
        duration: 800,
        easing: 'ease-out',
        once: true,
        offset: 100
    });

    // Navbar scroll effect
    initNavbarScroll();

    // Back to top button
    initBackToTop();

    // Smooth scroll for anchor links
    initSmoothScroll();

    // Portfolio filter
    initPortfolioFilter();

    // Form validation
    initFormValidation();

    // Mobile menu close on link click
    initMobileMenu();

    // Mouse parallax effect for hero masonry
    initParallaxEffect();

    // GSAP Text reveal animation
    initTextReveal();
});

/**
 * Navbar scroll effect
 */
function initNavbarScroll() {
    const navbar = document.querySelector('.navbar');

    window.addEventListener('scroll', function () {
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });
}

/**
 * Back to top button
 */
function initBackToTop() {
    const backToTopBtn = document.getElementById('backToTop');

    if (!backToTopBtn) return;

    window.addEventListener('scroll', function () {
        if (window.scrollY > 300) {
            backToTopBtn.classList.add('visible');
        } else {
            backToTopBtn.classList.remove('visible');
        }
    });

    backToTopBtn.addEventListener('click', function () {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
}

/**
 * Smooth scroll for anchor links
 */
function initSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const targetId = this.getAttribute('href');

            if (targetId === '#') return;

            const targetElement = document.querySelector(targetId);

            if (targetElement) {
                e.preventDefault();

                const navbarHeight = document.querySelector('.navbar').offsetHeight;
                const targetPosition = targetElement.getBoundingClientRect().top + window.pageYOffset - navbarHeight;

                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });
}

/**
 * Portfolio filter functionality
 */
function initPortfolioFilter() {
    const filterButtons = document.querySelectorAll('.filter-btn');
    const portfolioItems = document.querySelectorAll('.portfolio-item');

    if (!filterButtons.length || !portfolioItems.length) return;

    filterButtons.forEach(button => {
        button.addEventListener('click', function () {
            // Update active button
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');

            const filterValue = this.getAttribute('data-filter');

            // Filter items with animation
            portfolioItems.forEach(item => {
                const itemCategories = item.getAttribute('data-category').split(',');

                if (filterValue === 'all' || itemCategories.includes(filterValue)) {
                    item.style.display = 'block';
                    setTimeout(() => {
                        item.style.opacity = '1';
                        item.style.transform = 'scale(1)';
                    }, 50);
                } else {
                    item.style.opacity = '0';
                    item.style.transform = 'scale(0.8)';
                    setTimeout(() => {
                        item.style.display = 'none';
                    }, 300);
                }
            });
        });
    });
}

/**
 * Form validation
 */
function initFormValidation() {
    const contactForm = document.getElementById('contactForm');

    if (!contactForm) return;

    contactForm.addEventListener('submit', function (e) {
        e.preventDefault();

        // Reset previous errors
        this.querySelectorAll('.is-invalid').forEach(el => {
            el.classList.remove('is-invalid');
        });

        let isValid = true;

        // Validate name
        const name = this.querySelector('#name');
        if (name && name.value.trim() === '') {
            name.classList.add('is-invalid');
            isValid = false;
        }

        // Validate email
        const email = this.querySelector('#email');
        if (email && !isValidEmail(email.value)) {
            email.classList.add('is-invalid');
            isValid = false;
        }

        // Validate phone
        const phone = this.querySelector('#phone');
        if (phone && phone.value.trim() === '') {
            phone.classList.add('is-invalid');
            isValid = false;
        }

        // Validate message
        const message = this.querySelector('#message');
        if (message && message.value.trim() === '') {
            message.classList.add('is-invalid');
            isValid = false;
        }

        if (isValid) {
            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
            submitBtn.disabled = true;

            // Simulate form submission (replace with actual AJAX call)
            setTimeout(() => {
                // Show success message
                showAlert('success', 'Thank you! Your message has been sent successfully. We will get back to you soon.');
                contactForm.reset();
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 1500);
        }
    });
}

/**
 * Email validation helper
 */
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

/**
 * Show alert message
 */
function showAlert(type, message) {
    // Remove existing alerts
    const existingAlert = document.querySelector('.custom-alert');
    if (existingAlert) {
        existingAlert.remove();
    }

    // Create alert element
    const alert = document.createElement('div');
    alert.className = `custom-alert alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show`;
    alert.style.cssText = 'position: fixed; top: 100px; left: 50%; transform: translateX(-50%); z-index: 9999; min-width: 300px; max-width: 500px;';
    alert.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;

    document.body.appendChild(alert);

    // Auto remove after 5 seconds
    setTimeout(() => {
        if (alert.parentNode) {
            alert.remove();
        }
    }, 5000);
}

/**
 * Mobile menu - simplified handler for proper navigation
 */
function initMobileMenu() {
    const navbarCollapse = document.querySelector('.navbar-collapse');
    const navbarToggler = document.querySelector('.navbar-toggler');

    if (!navbarCollapse || !navbarToggler) return;

    // Fix active link color on mobile menu open
    function fixActiveLinksColor() {
        if (window.innerWidth < 992) {
            const activeLinks = document.querySelectorAll('.navbar-nav .nav-link.active');
            activeLinks.forEach(link => {
                link.style.color = '#ffffff';
            });
        }
    }

    // Apply fix when menu is shown
    navbarCollapse.addEventListener('shown.bs.collapse', fixActiveLinksColor);

    // Also apply on toggler click
    navbarToggler.addEventListener('click', function () {
        setTimeout(fixActiveLinksColor, 50);
    });

    // Close menu when clicking the X pseudo-element area only
    navbarCollapse.addEventListener('click', function (e) {
        const rect = this.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;

        // Check if click is in the close button area (top right corner)
        if (x > rect.width - 60 && y < 60 && window.innerWidth < 992) {
            e.preventDefault();
            e.stopPropagation();
            const bsCollapse = bootstrap.Collapse.getInstance(navbarCollapse);
            if (bsCollapse) {
                bsCollapse.hide();
            }
        }
    });

    // Close menu when clicking outside (not on menu or toggler)
    document.addEventListener('click', function (e) {
        if (window.innerWidth < 992 &&
            navbarCollapse.classList.contains('show') &&
            !navbarCollapse.contains(e.target) &&
            !navbarToggler.contains(e.target)) {
            const bsCollapse = bootstrap.Collapse.getInstance(navbarCollapse);
            if (bsCollapse) {
                bsCollapse.hide();
            }
        }
    });
}


/**
 * Counter animation for stats
 */
function animateCounters() {
    const counters = document.querySelectorAll('.stat-number');

    counters.forEach(counter => {
        const target = parseInt(counter.getAttribute('data-target'));
        const duration = 2000;
        const step = target / (duration / 16);
        let current = 0;

        const updateCounter = () => {
            current += step;
            if (current < target) {
                counter.textContent = Math.floor(current);
                requestAnimationFrame(updateCounter);
            } else {
                counter.textContent = target;
            }
        };

        updateCounter();
    });
}

/**
 * Intersection Observer for animations
 */
const observerOptions = {
    threshold: 0.2,
    rootMargin: '0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('animated');

            // Trigger counter animation if it's a stats section
            if (entry.target.classList.contains('stats-section')) {
                animateCounters();
            }

            observer.unobserve(entry.target);
        }
    });
}, observerOptions);

// Observe elements for animation
document.querySelectorAll('.animate-on-scroll').forEach(el => {
    observer.observe(el);
});

/**
 * Typing effect for hero title
 */
function initTypingEffect() {
    const typingElement = document.querySelector('.typing-effect');

    if (!typingElement) return;

    const text = typingElement.getAttribute('data-text');
    const speed = 100;
    let i = 0;

    typingElement.textContent = '';

    function typeWriter() {
        if (i < text.length) {
            typingElement.textContent += text.charAt(i);
            i++;
            setTimeout(typeWriter, speed);
        }
    }

    typeWriter();
}

/**
 * Parallax effect for hero section
 */
function initParallax() {
    const heroImage = document.querySelector('.hero-image');

    if (!heroImage) return;

    window.addEventListener('scroll', function () {
        const scrolled = window.pageYOffset;
        const rate = scrolled * 0.3;

        if (scrolled < 600) {
            heroImage.style.transform = `translateY(${rate}px)`;
        }
    });
}

// Initialize parallax
initParallax();

/**
 * Mouse Parallax Effect for Hero Masonry Grid
 * Creates subtle movement opposite to mouse direction
 */
function initParallaxEffect() {
    // Only run on desktop
    if (window.innerWidth < 992) return;

    const containers = document.querySelectorAll('[data-parallax-container]');
    if (containers.length === 0) return;

    document.addEventListener('mousemove', function (e) {
        const mouseX = e.clientX;
        const mouseY = e.clientY;
        const centerX = window.innerWidth / 2;
        const centerY = window.innerHeight / 2;

        containers.forEach(container => {
            const items = container.querySelectorAll('[data-parallax]');
            items.forEach(item => {
                const speed = parseFloat(item.dataset.parallax) || 0.05;
                const x = (centerX - mouseX) * speed;
                const y = (centerY - mouseY) * speed;

                // Apply transform with existing animation
                item.style.transform = `translate(${x}px, ${y}px)`;
            });
        });
    });

    // Reset on mouse leave
    document.addEventListener('mouseleave', function () {
        containers.forEach(container => {
            const items = container.querySelectorAll('[data-parallax]');
            items.forEach(item => {
                item.style.transform = 'translate(0, 0)';
            });
        });
    });
}

/**
 * GSAP Text Reveal Animation
 * Words slide up one by one from behind a mask
 */
function initTextReveal() {
    // Check if GSAP is loaded
    if (typeof gsap === 'undefined') {
        console.warn('GSAP not loaded, skipping text reveal animation');
        return;
    }

    const heroTitles = document.querySelectorAll('.hero-title');

    heroTitles.forEach((title, index) => {
        // Get original text and wrap each word
        const text = title.textContent.trim();
        const words = text.split(' ');

        // Clear and rebuild with wrapped words
        title.innerHTML = words.map(word =>
            `<span class="word"><span class="word-inner">${word}</span></span>`
        ).join(' ');

        // Only animate the first (visible) slide immediately
        if (index === 0) {
            const wordInners = title.querySelectorAll('.word-inner');

            // GSAP animation for each word
            gsap.to(wordInners, {
                y: 0,
                opacity: 1,
                duration: 0.8,
                stagger: 0.15,
                ease: "power3.out",
                delay: 0.3,
                onComplete: () => {
                    title.classList.add('revealed');
                }
            });
        }
    });

    // Re-animate on slide change
    const heroCarousel = document.getElementById('heroCarousel');
    if (heroCarousel) {
        heroCarousel.addEventListener('slid.bs.carousel', function (event) {
            const activeSlide = event.relatedTarget;
            const title = activeSlide.querySelector('.hero-title');

            if (title) {
                const wordInners = title.querySelectorAll('.word-inner');

                // Reset and animate
                gsap.fromTo(wordInners,
                    { y: '100%', opacity: 0 },
                    {
                        y: 0,
                        opacity: 1,
                        duration: 0.8,
                        stagger: 0.15,
                        ease: "power3.out",
                        onComplete: () => {
                            title.classList.add('revealed');
                        }
                    }
                );
            }
        });
    }
}
