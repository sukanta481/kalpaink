/**
 * Kalpanik - Main JavaScript
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

    // Hero Carousel
    initHeroCarousel();

    // Grid reveal (scroll intersection for v2 sections)
    initGridReveal();

    // Mobile Services Neon Spotlight
    initServicesSpotlight();

    // Footer Accordion (Mobile)
    initFooterAccordion();

    // Testimonials Slider
    initTestimonialsSlider();

    // Services Page - Mobile Accordion Cards
    initServiceAccordion();

    // Services Page - Floating Tools Parallax
    initFloatingToolsParallax();

    // Case Studies - Scattered Hero Parallax
    initScatteredParallax();
    initFloatingGalleryParallax();

    // Blog - Trending Section Progress Bar
    initBlogTrendingProgress();

    // Stats Count-Up Animation
    initStatsCountUp();

    // Magnetic Button Effect
    initMagneticButtons();

    // Image Comparison Hover Effect
    initImageComparison();

    // Creators Mobile Swiper
    initCreatorsSwiper();

    // GSAP Scroll-triggered section reveals
    initScrollRevealEffects();

    // Trusted Brands staggered scroll reveal
    initBrandsScrollReveal();
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
 * Mobile menu - slide-in panel with backdrop
 */
function initMobileMenu() {
    const navbarCollapse = document.querySelector('.navbar-collapse');
    const navbarToggler = document.querySelector('.navbar-toggler');
    const backdrop = document.getElementById('navbarBackdrop');

    if (!navbarCollapse || !navbarToggler) return;

    function openMenu() {
        if (backdrop) backdrop.classList.add('active');
        document.body.classList.add('menu-open');
        if (window.innerWidth < 992) {
            document.querySelectorAll('.navbar-nav .nav-link.active').forEach(link => {
                link.style.color = '#ffffff';
            });
        }
    }

    function closeMenu() {
        const bsCollapse = bootstrap.Collapse.getInstance(navbarCollapse);
        if (bsCollapse) bsCollapse.hide();
        if (backdrop) backdrop.classList.remove('active');
        document.body.classList.remove('menu-open');
    }

    // Bootstrap events
    navbarCollapse.addEventListener('shown.bs.collapse', openMenu);
    navbarCollapse.addEventListener('hidden.bs.collapse', function () {
        if (backdrop) backdrop.classList.remove('active');
        document.body.classList.remove('menu-open');
    });

    // Backdrop click closes menu
    if (backdrop) {
        backdrop.addEventListener('click', closeMenu);
    }

    // Close on nav link click (for same-page anchor links)
    navbarCollapse.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('click', function () {
            if (window.innerWidth < 992) closeMenu();
        });
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
 * Hero Carousel
 * Custom carousel with fade+slide transitions, auto-slide, arrows, and dots
 */
function initHeroCarousel() {
    const carousel = document.getElementById('heroCarousel');
    if (!carousel) return;

    const slides = carousel.querySelectorAll('.hero-slide');
    const dots = carousel.querySelectorAll('.hero-dot');
    const prevBtn = carousel.querySelector('.hero-arrow-prev');
    const nextBtn = carousel.querySelector('.hero-arrow-next');

    if (slides.length === 0) return;

    let currentIndex = 0;
    let autoSlideInterval;
    let isTransitioning = false;

    function goToSlide(index) {
        if (isTransitioning || index === currentIndex) return;
        isTransitioning = true;

        // Remove active from current slide
        slides[currentIndex].classList.remove('active');
        dots[currentIndex].classList.remove('active');

        // Set new active
        currentIndex = index;
        slides[currentIndex].classList.add('active');
        dots[currentIndex].classList.add('active');

        // Allow transitions to complete
        setTimeout(() => {
            isTransitioning = false;
        }, 600);
    }

    function nextSlide() {
        goToSlide((currentIndex + 1) % slides.length);
    }

    function prevSlide() {
        goToSlide((currentIndex - 1 + slides.length) % slides.length);
    }

    function startAutoSlide() {
        clearInterval(autoSlideInterval);
        autoSlideInterval = setInterval(nextSlide, 4000);
    }

    function resetAutoSlide() {
        startAutoSlide();
    }

    // Arrow buttons
    if (nextBtn) {
        nextBtn.addEventListener('click', () => {
            nextSlide();
            resetAutoSlide();
        });
    }

    if (prevBtn) {
        prevBtn.addEventListener('click', () => {
            prevSlide();
            resetAutoSlide();
        });
    }

    // Dot navigation
    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            goToSlide(index);
            resetAutoSlide();
        });
    });

    // Pause on hover (desktop)
    carousel.addEventListener('mouseenter', () => clearInterval(autoSlideInterval));
    carousel.addEventListener('mouseleave', startAutoSlide);

    // Touch swipe support for mobile
    let touchStartX = 0;
    let touchEndX = 0;

    carousel.addEventListener('touchstart', (e) => {
        touchStartX = e.changedTouches[0].screenX;
        clearInterval(autoSlideInterval);
    }, { passive: true });

    carousel.addEventListener('touchend', (e) => {
        touchEndX = e.changedTouches[0].screenX;
        const diff = touchStartX - touchEndX;
        if (Math.abs(diff) > 50) {
            if (diff > 0) {
                nextSlide();
            } else {
                prevSlide();
            }
        }
        resetAutoSlide();
    }, { passive: true });

    // Start auto-slide
    startAutoSlide();
}

/**
 * Mobile Creator Flip Cards
 * Tap to flip between Professional and Creative sides
 */
function initGridReveal() {
    const items = document.querySelectorAll('.grid-reveal-item');
    if (!items.length) return;

    const observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.15 });

    items.forEach(function (item, index) {
        // Stagger delay: items in same row get cascading delays
        var columnsPerRow = 3;
        if (item.closest('.creators-v2-grid')) columnsPerRow = 4;
        var delay = (index % columnsPerRow) * 100;
        item.style.transitionDelay = delay + 'ms';
        observer.observe(item);
    });
}

/**
 * Mobile Services Neon Spotlight
 * Detects which service card is most visible and activates it
 */
function initServicesSpotlight() {
    const track = document.querySelector('.services-track');
    const wrappers = document.querySelectorAll('.service-card-wrapper');

    if (!track || !wrappers.length || window.innerWidth >= 992) return;

    function updateActiveCard() {
        // Only apply on mobile
        if (window.innerWidth >= 992) {
            wrappers.forEach(w => w.classList.remove('is-active'));
            return;
        }

        const trackRect = track.getBoundingClientRect();
        const trackCenter = trackRect.left + trackRect.width * 0.4; // Slightly left of center for better UX

        let closestCard = null;
        let closestDistance = Infinity;

        wrappers.forEach(wrapper => {
            const rect = wrapper.getBoundingClientRect();
            const cardCenter = rect.left + rect.width / 2;
            const distance = Math.abs(cardCenter - trackCenter);

            if (distance < closestDistance) {
                closestDistance = distance;
                closestCard = wrapper;
            }
        });

        // Update active states
        wrappers.forEach(wrapper => {
            if (wrapper === closestCard) {
                wrapper.classList.add('is-active');
            } else {
                wrapper.classList.remove('is-active');
            }
        });
    }

    // Listen for scroll on the track
    track.addEventListener('scroll', updateActiveCard, { passive: true });

    // Initial check
    updateActiveCard();

    // Recheck on resize
    window.addEventListener('resize', updateActiveCard);
}

/**
 * Footer Accordion for Mobile
 * Collapsible sections to reduce footer height
 */
function initFooterAccordion() {
    const triggers = document.querySelectorAll('.footer-accordion-trigger');

    if (!triggers.length) return;

    triggers.forEach(trigger => {
        trigger.addEventListener('click', function () {
            // Only work on mobile/tablet
            if (window.innerWidth >= 992) return;

            const parent = this.closest('.footer-accordion-item');
            const isActive = parent.classList.contains('active');

            // Close all other accordions
            document.querySelectorAll('.footer-accordion-item').forEach(item => {
                item.classList.remove('active');
            });

            // Toggle current one
            if (!isActive) {
                parent.classList.add('active');
            }
        });
    });

    // Reset on resize to desktop
    window.addEventListener('resize', function () {
        if (window.innerWidth >= 992) {
            document.querySelectorAll('.footer-accordion-item').forEach(item => {
                item.classList.remove('active');
            });
        }
    });
}

/**
 * Testimonials Slider
 * Static background with sliding text/image content
 */
function initTestimonialsSlider() {
    const wrapper = document.querySelector('.testimonials-wrapper');
    const dotsContainer = document.querySelector('.testimonials-dots');
    const prevBtn = document.querySelector('.testimonial-nav-btn.prev');
    const nextBtn = document.querySelector('.testimonial-nav-btn.next');

    if (!wrapper) return;

    const slides = wrapper.querySelectorAll('.testimonial-slide');
    if (!slides.length) return;

    let currentIndex = 0;
    let autoplayInterval;

    // Create dots
    slides.forEach((_, index) => {
        const dot = document.createElement('span');
        dot.classList.add('dot');
        if (index === 0) dot.classList.add('active');
        dot.addEventListener('click', () => goToSlide(index));
        dotsContainer.appendChild(dot);
    });

    const dots = dotsContainer.querySelectorAll('.dot');

    // Go to specific slide with animation
    function goToSlide(index, direction = 'next') {
        if (index < 0) index = slides.length - 1;
        if (index >= slides.length) index = 0;

        const prevIndex = currentIndex;
        currentIndex = index;

        // Remove all states
        slides.forEach(slide => {
            slide.classList.remove('active', 'prev');
        });

        // Add prev class to old slide (animates out)
        if (direction === 'next') {
            slides[prevIndex].classList.add('prev');
        }

        // Add active class to new slide (animates in)
        slides[currentIndex].classList.add('active');

        // Update dots
        dots.forEach((dot, i) => {
            dot.classList.toggle('active', i === currentIndex);
        });
    }

    // Navigation buttons
    if (prevBtn) {
        prevBtn.addEventListener('click', () => {
            goToSlide(currentIndex - 1, 'prev');
            resetAutoplay();
        });
    }

    if (nextBtn) {
        nextBtn.addEventListener('click', () => {
            goToSlide(currentIndex + 1, 'next');
            resetAutoplay();
        });
    }

    // Autoplay
    function startAutoplay() {
        autoplayInterval = setInterval(() => {
            goToSlide(currentIndex + 1, 'next');
        }, 5000);
    }

    function resetAutoplay() {
        clearInterval(autoplayInterval);
        startAutoplay();
    }

    // Start autoplay
    startAutoplay();

    // Pause on hover
    wrapper.addEventListener('mouseenter', () => clearInterval(autoplayInterval));
    wrapper.addEventListener('mouseleave', startAutoplay);

    // Touch swipe support
    let touchStartX = 0;
    let touchEndX = 0;

    wrapper.addEventListener('touchstart', (e) => {
        touchStartX = e.changedTouches[0].screenX;
    }, { passive: true });

    wrapper.addEventListener('touchend', (e) => {
        touchEndX = e.changedTouches[0].screenX;
        handleSwipe();
    }, { passive: true });

    function handleSwipe() {
        const swipeThreshold = 50;
        const diff = touchStartX - touchEndX;

        if (Math.abs(diff) > swipeThreshold) {
            if (diff > 0) {
                // Swipe left - next slide
                goToSlide(currentIndex + 1, 'next');
            } else {
                // Swipe right - prev slide
                goToSlide(currentIndex - 1, 'prev');
            }
            resetAutoplay();
        }
    }
}

/**
 * Services Page - Mobile Accordion Cards
 * Tap to expand service cards on mobile
 */
function initServiceAccordion() {
    const serviceCards = document.querySelectorAll('.service-hologram-card');

    if (!serviceCards.length) return;

    serviceCards.forEach(card => {
        card.addEventListener('click', function (e) {
            // Only work on mobile/tablet
            if (window.innerWidth >= 992) return;

            const isExpanded = this.classList.contains('expanded');

            // Close all other cards
            serviceCards.forEach(c => {
                if (c !== this) {
                    c.classList.remove('expanded');
                }
            });

            // Toggle current card
            this.classList.toggle('expanded');
        });
    });

    // Reset on resize to desktop
    window.addEventListener('resize', function () {
        if (window.innerWidth >= 992) {
            serviceCards.forEach(card => {
                card.classList.remove('expanded');
            });
        }
    });
}

/**
 * Services Page - Floating Tools Parallax
 * Subtle mouse movement effect on floating tools
 */
function initFloatingToolsParallax() {
    const container = document.querySelector('.floating-tools-container');
    const tools = document.querySelectorAll('.floating-tool');

    if (!container || !tools.length) return;

    container.addEventListener('mousemove', function (e) {
        const rect = container.getBoundingClientRect();
        const centerX = rect.width / 2;
        const centerY = rect.height / 2;
        const mouseX = e.clientX - rect.left - centerX;
        const mouseY = e.clientY - rect.top - centerY;

        tools.forEach(tool => {
            const speed = parseFloat(tool.dataset.speed) || 1;
            const x = (mouseX * speed * 0.02);
            const y = (mouseY * speed * 0.02);

            tool.style.transform = `translate(${x}px, ${y}px)`;
        });
    });

    container.addEventListener('mouseleave', function () {
        tools.forEach(tool => {
            tool.style.transform = '';
        });
    });
}

/* Old case studies spotlight, progress bar, cursor, floating gallery removed */

/**
 * Case Studies - Scattered Hero Parallax
 * Floating project images shift subtly on mouse move
 */
function initScatteredParallax() {
    const hero = document.querySelector('.case-hero');
    const cards = document.querySelectorAll('.sc-card');

    if (!hero || !cards.length) return;
    if (window.innerWidth < 768) return; // Skip on mobile

    hero.addEventListener('mousemove', function (e) {
        const rect = hero.getBoundingClientRect();
        const centerX = rect.width / 2;
        const centerY = rect.height / 2;
        const mouseX = e.clientX - rect.left - centerX;
        const mouseY = e.clientY - rect.top - centerY;

        cards.forEach(card => {
            const speed = parseFloat(card.dataset.speed) || 0.03;
            const x = mouseX * speed;
            const y = mouseY * speed;

            // Get the card's base rotation from CSS
            const style = window.getComputedStyle(card);
            const transform = style.transform;

            card.style.transform = `translate(${x}px, ${y}px)`;
        });
    });

    hero.addEventListener('mouseleave', function () {
        cards.forEach(card => {
            card.style.transform = '';
        });
    });

    // Smooth scroll for "Explore Work" CTA
    const cta = document.querySelector('.case-hero-cta');
    if (cta) {
        cta.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    }
}

/**
 * Blog - Trending Section Progress Bar
 */
function initBlogTrendingProgress() {
    const wrapper = document.querySelector('.trending-cards-wrapper');
    const progressFill = document.getElementById('blogTrendingProgress');

    if (!wrapper || !progressFill) return;

    function updateProgress() {
        const scrollLeft = wrapper.scrollLeft;
        const scrollWidth = wrapper.scrollWidth - wrapper.clientWidth;

        if (scrollWidth > 0) {
            const progress = (scrollLeft / scrollWidth) * 100;
            // Minimum 25% (1 of 4 cards visible)
            const displayProgress = Math.max(25, Math.min(100, progress + 25));
            progressFill.style.width = displayProgress + '%';
        }
    }

    wrapper.addEventListener('scroll', updateProgress);
    updateProgress();
}

/**
 * Services Page - Mobile Accordion Cards
 * Tap to expand/collapse service details on mobile
 */
function initServiceAccordion() {
    // Only run on mobile
    if (window.innerWidth >= 992) return;

    const serviceCards = document.querySelectorAll('.service-hologram-card');

    if (!serviceCards.length) return;

    serviceCards.forEach(card => {
        const header = card.querySelector('.hologram-header');

        if (!header) return;

        header.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            // Close other expanded cards (optional - accordion behavior)
            const wasExpanded = card.classList.contains('expanded');

            // Collapse all cards first for accordion effect
            serviceCards.forEach(otherCard => {
                if (otherCard !== card) {
                    otherCard.classList.remove('expanded');
                }
            });

            // Toggle current card
            if (wasExpanded) {
                card.classList.remove('expanded');
            } else {
                card.classList.add('expanded');

                // Scroll card into view smoothly
                setTimeout(() => {
                    card.scrollIntoView({
                        behavior: 'smooth',
                        block: 'nearest'
                    });
                }, 100);
            }
        });
    });

    // Handle window resize - remove expanded class on desktop
    window.addEventListener('resize', function () {
        if (window.innerWidth >= 992) {
            serviceCards.forEach(card => {
                card.classList.remove('expanded');
            });
        }
    });
}

/**
 * Services Page - Floating Tools Parallax
 * Mouse movement creates subtle parallax on floating tool icons
 */
function initFloatingToolsParallax() {
    const container = document.querySelector('.floating-tools-container');
    const tools = document.querySelectorAll('.floating-tool');

    if (!container || !tools.length) return;

    // Only on desktop
    if (window.innerWidth < 992) return;

    container.addEventListener('mousemove', function (e) {
        const rect = container.getBoundingClientRect();
        const centerX = rect.width / 2;
        const centerY = rect.height / 2;
        const mouseX = e.clientX - rect.left - centerX;
        const mouseY = e.clientY - rect.top - centerY;

        tools.forEach(tool => {
            const speed = parseFloat(tool.dataset.speed) || 1;
            const x = mouseX * 0.02 * speed;
            const y = mouseY * 0.02 * speed;
            const rotation = mouseX * 0.01 * speed;

            tool.style.transform = `translate(${x}px, ${y}px) rotate(${rotation}deg)`;
        });
    });

    container.addEventListener('mouseleave', function () {
        tools.forEach(tool => {
            tool.style.transform = '';
        });
    });
}


/**
 * Stats Count-Up Animation
 * Numbers animate from 0 when scrolled into view
 */
function initStatsCountUp() {
    const statNumbers = document.querySelectorAll('.stat-number[data-count]');

    if (!statNumbers.length) return;

    const animateCount = (element) => {
        const target = parseInt(element.dataset.count);
        const suffix = element.dataset.suffix || '';
        const duration = 2000; // 2 seconds
        const startTime = performance.now();

        element.classList.add('counting');

        const updateCount = (currentTime) => {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);

            // Easing function (ease-out cubic)
            const easeOut = 1 - Math.pow(1 - progress, 3);
            const current = Math.floor(easeOut * target);

            element.textContent = current + suffix;

            if (progress < 1) {
                requestAnimationFrame(updateCount);
            } else {
                element.textContent = target + suffix;
                element.classList.remove('counting');
                element.classList.add('counted');
            }
        };

        requestAnimationFrame(updateCount);
    };

    // Intersection Observer to trigger animation when in view
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && !entry.target.classList.contains('counted')) {
                animateCount(entry.target);
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.5,
        rootMargin: '0px 0px -50px 0px'
    });

    statNumbers.forEach(stat => observer.observe(stat));
}


/**
 * Magnetic Button Effect
 * Button moves slightly towards cursor on hover
 */
function initMagneticButtons() {
    const buttons = document.querySelectorAll('.btn-magnetic');

    if (!buttons.length) return;

    // Only on desktop
    if (window.innerWidth < 992) return;

    buttons.forEach(button => {
        const strength = 0.3; // How strongly it follows the cursor (0-1)
        const maxMove = 15; // Maximum pixels to move

        button.addEventListener('mousemove', (e) => {
            const rect = button.getBoundingClientRect();
            const centerX = rect.left + rect.width / 2;
            const centerY = rect.top + rect.height / 2;

            const deltaX = (e.clientX - centerX) * strength;
            const deltaY = (e.clientY - centerY) * strength;

            // Clamp the movement
            const moveX = Math.max(-maxMove, Math.min(maxMove, deltaX));
            const moveY = Math.max(-maxMove, Math.min(maxMove, deltaY));

            button.style.transform = `translate(${moveX}px, ${moveY}px)`;
        });

        button.addEventListener('mouseleave', () => {
            button.style.transform = 'translate(0, 0)';
        });
    });
}


/**
 * Image Comparison Hover Effect
 * Simulates before/after reveal on mouse movement
 */
function initImageComparison() {
    const comparisons = document.querySelectorAll('.image-comparison');

    if (!comparisons.length) return;

    comparisons.forEach(container => {
        const overlay = container.querySelector('.comparison-overlay');

        if (!overlay) return;

        container.addEventListener('mousemove', (e) => {
            const rect = container.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const percentage = (x / rect.width) * 100;

            // Move overlay based on mouse position
            // When mouse is on left, show more "raw", when on right, show more "polished"
            overlay.style.left = `${Math.max(0, percentage - 20)}%`;
            overlay.style.opacity = Math.min(1, (percentage / 100) * 1.5);
        });

        container.addEventListener('mouseleave', () => {
            overlay.style.left = '50%';
            overlay.style.opacity = '0';
        });
    });
}

/**
 * Creators Mobile Swiper
 * Initializes Swiper on mobile, destroys on desktop
 */
function initCreatorsSwiper() {
    const creatorsSwiperElement = document.querySelector('.creators-swiper');
    if (!creatorsSwiperElement) return;

    if (typeof Swiper === 'undefined') return;

    let creatorsSwiper = null;

    function initOrDestroySwiper() {
        if (window.innerWidth < 992) {
            if (!creatorsSwiper) {
                creatorsSwiper = new Swiper('.creators-swiper', {
                    slidesPerView: 1.15,
                    spaceBetween: 16,
                    grabCursor: true,
                    speed: 500,
                    cssMode: false,
                    touchRatio: 1,
                    resistance: true,
                    resistanceRatio: 0.85,
                    navigation: {
                        nextEl: '.creators-next',
                        prevEl: '.creators-prev',
                    },
                    pagination: {
                        el: '.creators-pagination',
                        clickable: true,
                        dynamicBullets: true,
                    },
                    breakpoints: {
                        480: {
                            slidesPerView: 1.5,
                            spaceBetween: 18,
                        },
                        576: {
                            slidesPerView: 2,
                            spaceBetween: 20,
                        },
                        768: {
                            slidesPerView: 2.5,
                            spaceBetween: 22,
                        }
                    }
                });
            }
        } else {
            if (creatorsSwiper !== null) {
                creatorsSwiper.destroy(true, true);
                creatorsSwiper = null;
            }
        }
    }

    initOrDestroySwiper();
    window.addEventListener('resize', initOrDestroySwiper);
}


/**
 * Testimonials Mobile Swiper
 */
(function initTestimonialsSwiper() {
    const testimonialsSwiperEl = document.querySelector('.testimonials-swiper');
    if (!testimonialsSwiperEl || typeof Swiper === 'undefined') return;

    new Swiper('.testimonials-swiper', {
        slidesPerView: 1,
        spaceBetween: 0,
        loop: false,
        speed: 400,
        grabCursor: true,
        autoHeight: true,
        navigation: {
            nextEl: '.testimonial-next',
            prevEl: '.testimonial-prev',
        },
    });
})();


/**
 * GSAP Scroll Reveal Effects
 * Smooth entrance for hero only — lightweight
 */
function initScrollRevealEffects() {
    if (typeof gsap === 'undefined') return;
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    // Hero card entrance
    const heroCard = document.querySelector('.hero-section-v2-card');
    if (heroCard) {
        gsap.fromTo(heroCard,
            { scale: 0.96, opacity: 0 },
            { scale: 1, opacity: 1, duration: 0.8, ease: 'power2.out', delay: 0.15 }
        );
    }
}


/**
 * Trusted Brands - Staggered Scroll Reveal
 * Logos fade-in-up sequentially when section enters viewport
 */
function initBrandsScrollReveal() {
    var brandsGrid = document.querySelector('.brands-grid');
    if (!brandsGrid) return;

    var brandItems = brandsGrid.querySelectorAll('.brand-item');
    if (!brandItems.length) return;

    // Apply hidden state immediately
    brandItems.forEach(function (item) {
        item.classList.add('fade-up-hidden');
    });

    // Delay observer start so the page renders first
    // This prevents the animation from firing before the user can see it
    setTimeout(function () {
        var brandsObserver = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    // Stagger each logo with 150ms delay for dramatic wave effect
                    brandItems.forEach(function (item, index) {
                        item.style.transitionDelay = (index * 150) + 'ms';
                    });

                    // Double-rAF to ensure browser paints the hidden state first
                    requestAnimationFrame(function () {
                        requestAnimationFrame(function () {
                            brandItems.forEach(function (item) {
                                item.classList.add('fade-up-visible');
                            });
                        });
                    });

                    // Unobserve (one-shot animation)
                    brandsObserver.unobserve(entry.target);

                    // Clean up transition-delay after all animations finish
                    setTimeout(function () {
                        brandItems.forEach(function (item) {
                            item.style.transitionDelay = '';
                        });
                    }, brandItems.length * 150 + 1000);
                }
            });
        }, {
            threshold: 0.15,
            rootMargin: '0px 0px -100px 0px'
        });

        brandsObserver.observe(brandsGrid);
    }, 600);
}

