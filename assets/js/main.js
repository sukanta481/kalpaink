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

    // Mobile Hero Swipe Deck
    initMobileHeroSwipe();

    // Mobile Creator Flip Cards
    initCreatorFlipCards();

    // Mobile Services Neon Spotlight
    initServicesSpotlight();

    // Footer Accordion (Mobile)
    initFooterAccordion();

    // Services Page - Mobile Accordion Cards
    initServiceAccordion();

    // Services Page - Floating Tools Parallax
    initFloatingToolsParallax();

    // Case Studies - Mobile Scroll Spotlight
    initCaseStudySpotlight();

    // Case Studies - Progress Bar
    initCaseStudyProgressBar();

    // Case Studies - Custom VIEW Cursor (Desktop)
    initCustomCursor();

    // Case Studies - Floating Gallery Parallax
    initFloatingGalleryParallax();
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

/**
 * Mobile Hero Swipe Deck
 * Touch-enabled card swiping like Tinder/Instagram Stories
 */
function initMobileHeroSwipe() {
    // Only run on mobile/tablet
    if (window.innerWidth >= 992) return;

    const deck = document.getElementById('swipeDeck');
    if (!deck) return;

    const cards = deck.querySelectorAll('.swipe-card');
    const indicators = document.querySelectorAll('.swipe-indicator');

    if (cards.length === 0) return;

    let currentIndex = 0;
    let startX = 0;
    let startY = 0;
    let currentX = 0;
    let isDragging = false;
    let autoRotateInterval;

    // Update card positions based on current index
    function updateCardPositions(skipAnimation = false) {
        cards.forEach((card, index) => {
            // Calculate the relative position (0, 1, 2) from current index
            const relativeIndex = (index - currentIndex + cards.length) % cards.length;

            if (skipAnimation) {
                card.style.transition = 'none';
            } else {
                card.style.transition = 'transform 0.4s ease-out, opacity 0.4s ease-out';
            }

            // Reset inline styles - CSS will handle positioning via data-index
            card.style.transform = '';
            card.style.opacity = '';

            card.setAttribute('data-index', relativeIndex);
        });

        // Update indicators
        indicators.forEach((indicator, index) => {
            indicator.classList.toggle('active', index === currentIndex);
        });
    }

    // Move to next card with Tinder-style animation
    function nextCard() {
        const oldCard = deck.querySelector('.swipe-card[data-index="0"]');

        // Update index
        currentIndex = (currentIndex + 1) % cards.length;

        // First, update positions without animation for the card coming to front
        cards.forEach((card, index) => {
            const relativeIndex = (index - currentIndex + cards.length) % cards.length;

            if (card === oldCard) {
                // Keep the old card off-screen, it will animate back later
                card.style.transition = 'none';
                card.style.transform = 'translateX(-50%) scale(0.84) translateY(30px)';
                card.style.opacity = '0';
            } else {
                card.style.transition = 'transform 0.4s ease-out, opacity 0.4s ease-out';
                card.style.transform = '';
                card.style.opacity = '';
            }

            card.setAttribute('data-index', relativeIndex);
        });

        // Fade in the old card at back after a delay
        setTimeout(() => {
            if (oldCard) {
                oldCard.style.transition = 'opacity 0.3s ease';
                oldCard.style.opacity = '';
            }
        }, 400);

        // Update indicators
        indicators.forEach((indicator, index) => {
            indicator.classList.toggle('active', index === currentIndex);
        });
    }

    // Move to previous card
    function prevCard() {
        const oldCard = deck.querySelector('.swipe-card[data-index="0"]');

        currentIndex = (currentIndex - 1 + cards.length) % cards.length;

        cards.forEach((card, index) => {
            const relativeIndex = (index - currentIndex + cards.length) % cards.length;

            if (card === oldCard) {
                card.style.transition = 'none';
                card.style.transform = 'translateX(-50%) scale(0.84) translateY(30px)';
                card.style.opacity = '0';
            } else {
                card.style.transition = 'transform 0.4s ease-out, opacity 0.4s ease-out';
                card.style.transform = '';
                card.style.opacity = '';
            }

            card.setAttribute('data-index', relativeIndex);
        });

        setTimeout(() => {
            if (oldCard) {
                oldCard.style.transition = 'opacity 0.3s ease';
                oldCard.style.opacity = '';
            }
        }, 400);

        indicators.forEach((indicator, index) => {
            indicator.classList.toggle('active', index === currentIndex);
        });
    }

    // Handle touch start
    function handleTouchStart(e) {
        if (window.innerWidth >= 992) return;

        const card = e.target.closest('.swipe-card');
        if (!card || card.getAttribute('data-index') !== '0') return;

        isDragging = true;
        startX = e.touches[0].clientX;
        startY = e.touches[0].clientY;
        currentX = startX;

        // Pause auto-rotate while dragging
        clearInterval(autoRotateInterval);

        // Disable transition during drag for responsiveness
        card.style.transition = 'none';
    }

    // Handle touch move
    function handleTouchMove(e) {
        if (!isDragging || window.innerWidth >= 992) return;

        const card = deck.querySelector('.swipe-card[data-index="0"]');
        if (!card) return;

        currentX = e.touches[0].clientX;
        const diffX = currentX - startX;
        const diffY = e.touches[0].clientY - startY;

        // Only handle horizontal swipes
        if (Math.abs(diffX) > Math.abs(diffY)) {
            e.preventDefault();

            // Apply transform during drag with requestAnimationFrame for smoothness
            requestAnimationFrame(() => {
                const rotation = diffX * 0.08;
                const opacity = 1 - Math.abs(diffX) / 400;

                card.style.transform = `translateX(calc(-50% + ${diffX}px)) rotate(${rotation}deg) scale(1)`;
                card.style.opacity = Math.max(0.6, opacity);
            });
        }
    }

    // Handle touch end
    function handleTouchEnd(e) {
        if (!isDragging || window.innerWidth >= 992) return;

        isDragging = false;

        const card = deck.querySelector('.swipe-card[data-index="0"]');
        if (!card) return;

        const diffX = currentX - startX;
        const threshold = 60; // Minimum swipe distance

        // Re-enable transition for fly-off animation
        card.style.transition = 'transform 0.35s ease-out, opacity 0.35s ease-out';

        if (Math.abs(diffX) > threshold) {
            if (diffX > 0) {
                // Swiped right - fly off to right
                card.style.transform = 'translateX(120%) rotate(15deg) scale(0.9)';
                card.style.opacity = '0';
                setTimeout(() => {
                    prevCard();
                }, 300);
            } else {
                // Swiped left - fly off to left
                card.style.transform = 'translateX(-170%) rotate(-15deg) scale(0.9)';
                card.style.opacity = '0';
                setTimeout(() => {
                    nextCard();
                }, 300);
            }
        } else {
            // Reset position if not swiped enough
            card.style.transform = 'translateX(-50%) scale(1) translateY(0)';
            card.style.opacity = '1';
        }

        // Restart auto-rotate
        startAutoRotate();
    }

    // Auto-rotate cards
    function startAutoRotate() {
        clearInterval(autoRotateInterval);
        autoRotateInterval = setInterval(() => {
            if (window.innerWidth < 992) {
                nextCard();
            }
        }, 4000);
    }

    // Click on indicators
    indicators.forEach((indicator, index) => {
        indicator.addEventListener('click', () => {
            currentIndex = index;
            updateCardPositions();
            startAutoRotate();
        });
    });

    // Add event listeners
    deck.addEventListener('touchstart', handleTouchStart, { passive: true });
    deck.addEventListener('touchmove', handleTouchMove, { passive: false });
    deck.addEventListener('touchend', handleTouchEnd, { passive: true });

    // Initial setup
    updateCardPositions();
    startAutoRotate();

    // Handle resize
    window.addEventListener('resize', () => {
        if (window.innerWidth >= 992) {
            clearInterval(autoRotateInterval);
        } else {
            startAutoRotate();
        }
    });
}

/**
 * Mobile Creator Flip Cards
 * Tap to flip between Professional and Creative sides
 */
function initCreatorFlipCards() {
    const flipCards = document.querySelectorAll('.creator-flip-mobile');
    
    if (!flipCards.length) return;
    
    flipCards.forEach(card => {
        // Tap to flip
        card.addEventListener('click', function(e) {
            // Don't flip if clicking on the LinkedIn button
            if (e.target.closest('.flip-linkedin')) return;
            
            this.classList.toggle('flipped');
            
            // Add a subtle haptic feedback feel with a small animation
            this.style.transform = 'scale(0.98)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 100);
        });
        
        // Also support touch events for better mobile response
        card.addEventListener('touchend', function(e) {
            // Prevent double-triggering with click
            if (e.target.closest('.flip-linkedin')) return;
        });
    });
    
    // Optional: Auto-flip hint animation on first card after 3 seconds
    setTimeout(() => {
        if (flipCards[0] && window.innerWidth < 992) {
            flipCards[0].classList.add('flipped');
            setTimeout(() => {
                flipCards[0].classList.remove('flipped');
            }, 1500);
        }
    }, 3000);
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
        trigger.addEventListener('click', function() {
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
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 992) {
            document.querySelectorAll('.footer-accordion-item').forEach(item => {
                item.classList.remove('active');
            });
        }
    });
}

/**
 * Services Page - Mobile Accordion Cards
 * Tap to expand service cards on mobile
 */
function initServiceAccordion() {
    const serviceCards = document.querySelectorAll('.service-hologram-card');
    
    if (!serviceCards.length) return;
    
    serviceCards.forEach(card => {
        card.addEventListener('click', function(e) {
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
    window.addEventListener('resize', function() {
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
    
    container.addEventListener('mousemove', function(e) {
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
    
    container.addEventListener('mouseleave', function() {
        tools.forEach(tool => {
            tool.style.transform = '';
        });
    });
}

/**
 * Case Studies - Mobile Scroll Spotlight Effect
 * Center card turns full color and scales up
 * Works with horizontal swipe deck on mobile
 */
function initCaseStudySpotlight() {
    const cards = document.querySelectorAll('.case-study-card-v2');
    const grid = document.querySelector('.masonry-portfolio-grid');
    
    if (!cards.length || !grid) return;
    
    // Only apply on mobile/tablet
    function isMobile() {
        return window.innerWidth < 992;
    }
    
    function updateSpotlight() {
        if (!isMobile()) {
            // Remove all spotlight states on desktop
            cards.forEach(card => card.classList.remove('spotlight-active'));
            return;
        }
        
        // For horizontal swipe deck, use horizontal center
        const viewportCenterX = window.innerWidth / 2;
        let closestCard = null;
        let closestDistance = Infinity;
        
        cards.forEach(card => {
            const rect = card.getBoundingClientRect();
            const cardCenterX = rect.left + rect.width / 2;
            const distance = Math.abs(cardCenterX - viewportCenterX);
            
            if (distance < closestDistance) {
                closestDistance = distance;
                closestCard = card;
            }
            
            card.classList.remove('spotlight-active');
        });
        
        // Only activate if card is reasonably close to center
        if (closestCard && closestDistance < window.innerWidth * 0.4) {
            closestCard.classList.add('spotlight-active');
        }
    }
    
    // Listen to horizontal scroll on the grid container
    let ticking = false;
    grid.addEventListener('scroll', function() {
        if (!ticking) {
            window.requestAnimationFrame(function() {
                updateSpotlight();
                ticking = false;
            });
            ticking = true;
        }
    });
    
    // Also listen to window scroll (for page scroll)
    window.addEventListener('scroll', function() {
        if (!ticking) {
            window.requestAnimationFrame(function() {
                updateSpotlight();
                ticking = false;
            });
            ticking = true;
        }
    });
    
    // Initial check
    updateSpotlight();
    
    // Update on resize
    window.addEventListener('resize', updateSpotlight);
}

/**
 * Case Studies - Progress Bar Indicator
 * Updates progress bar as user swipes through cards
 */
function initCaseStudyProgressBar() {
    const grid = document.querySelector('.masonry-portfolio-grid');
    const progressFill = document.getElementById('caseStudyProgress');
    
    if (!grid || !progressFill) return;
    
    function updateProgress() {
        const scrollLeft = grid.scrollLeft;
        const scrollWidth = grid.scrollWidth - grid.clientWidth;
        
        if (scrollWidth > 0) {
            const progress = (scrollLeft / scrollWidth) * 100;
            // Ensure minimum of ~11% (first card visible)
            const displayProgress = Math.max(11, Math.min(100, progress + 11));
            progressFill.style.width = displayProgress + '%';
        }
    }
    
    grid.addEventListener('scroll', updateProgress);
    
    // Initial state
    updateProgress();
}

/**
 * Case Studies - Custom VIEW Cursor (Desktop)
 * Yellow circle cursor when hovering over masonry grid
 */
function initCustomCursor() {
    const cursor = document.querySelector('.custom-cursor-view');
    const grid = document.querySelector('.masonry-portfolio-grid');
    
    if (!cursor || !grid) return;
    
    // Only on desktop
    if (window.innerWidth < 992) return;
    
    let mouseX = 0;
    let mouseY = 0;
    let cursorX = 0;
    let cursorY = 0;
    
    // Smooth cursor following
    function animateCursor() {
        const dx = mouseX - cursorX;
        const dy = mouseY - cursorY;
        
        cursorX += dx * 0.15;
        cursorY += dy * 0.15;
        
        cursor.style.left = cursorX - 40 + 'px';
        cursor.style.top = cursorY - 40 + 'px';
        
        requestAnimationFrame(animateCursor);
    }
    
    animateCursor();
    
    // Track mouse movement
    document.addEventListener('mousemove', function(e) {
        mouseX = e.clientX;
        mouseY = e.clientY;
    });
    
    // Show/hide cursor on grid
    grid.addEventListener('mouseenter', function() {
        cursor.classList.add('active');
        document.body.style.cursor = 'none';
    });
    
    grid.addEventListener('mouseleave', function() {
        cursor.classList.remove('active');
        document.body.style.cursor = '';
    });
    
    // Hide cursor when hovering links inside cards
    const links = grid.querySelectorAll('a');
    links.forEach(link => {
        link.addEventListener('mouseenter', function() {
            cursor.classList.remove('active');
            document.body.style.cursor = 'pointer';
        });
        link.addEventListener('mouseleave', function() {
            cursor.classList.add('active');
            document.body.style.cursor = 'none';
        });
    });
}

/**
 * Case Studies - Floating Gallery Parallax
 */
function initFloatingGalleryParallax() {
    const gallery = document.querySelector('.floating-gallery');
    const items = document.querySelectorAll('.gallery-float-item');
    
    if (!gallery || !items.length) return;
    
    gallery.addEventListener('mousemove', function(e) {
        const rect = gallery.getBoundingClientRect();
        const centerX = rect.width / 2;
        const centerY = rect.height / 2;
        const mouseX = e.clientX - rect.left - centerX;
        const mouseY = e.clientY - rect.top - centerY;
        
        items.forEach((item, index) => {
            const speed = 0.02 + (index * 0.01);
            const x = mouseX * speed;
            const y = mouseY * speed;
            const baseRotation = item.classList.contains('item-1') ? -8 :
                                item.classList.contains('item-2') ? 5 :
                                item.classList.contains('item-3') ? 3 : -5;
            
            item.style.transform = `rotate(${baseRotation}deg) translate(${x}px, ${y}px)`;
        });
    });
    
    gallery.addEventListener('mouseleave', function() {
        items.forEach(item => {
            item.style.transform = '';
        });
    });
}
