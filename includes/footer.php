    <!-- Footer -->
    <footer class="footer-v2">
        <!-- Dark card with rounded top corners -->
        <div class="footer-v2-card">
            <div class="container">
                <!-- Row 1: Brand + CTA -->
                <div class="footer-v2-top">
                    <div class="footer-v2-left">
                        <a href="<?php echo getSitePath(''); ?>" class="footer-v2-logo">
                            <img src="<?php echo SITE_LOGO; ?>" alt="<?php echo SITE_NAME; ?>">
                        </a>
                        <div class="footer-v2-socials">
                            <?php if (SOCIAL_FACEBOOK && SOCIAL_FACEBOOK !== '#'): ?>
                            <a href="<?php echo SOCIAL_FACEBOOK; ?>" target="_blank" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                            <?php endif; ?>
                            <?php if (SOCIAL_INSTAGRAM && SOCIAL_INSTAGRAM !== '#'): ?>
                            <a href="<?php echo SOCIAL_INSTAGRAM; ?>" target="_blank" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                            <?php endif; ?>
                            <?php if (SOCIAL_LINKEDIN && SOCIAL_LINKEDIN !== '#'): ?>
                            <a href="<?php echo SOCIAL_LINKEDIN; ?>" target="_blank" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                            <?php endif; ?>
                        </div>
                        <!-- Contact info under socials -->
                        <div class="footer-v2-contact">
                            <div class="footer-v2-contact-item">
                                <span class="footer-v2-contact-label">Email</span>
                                <a href="mailto:<?php echo CONTACT_EMAIL; ?>"><?php echo CONTACT_EMAIL; ?></a>
                            </div>
                            <div class="footer-v2-contact-item">
                                <span class="footer-v2-contact-label">Phone Number</span>
                                <a href="tel:<?php echo CONTACT_PHONE; ?>"><?php echo CONTACT_PHONE; ?></a>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <nav class="footer-v2-nav" aria-label="Footer navigation">
                        <div class="footer-v2-nav-col">
                            <h4 class="footer-v2-nav-heading">Quick Links</h4>
                            <ul class="footer-v2-nav-list">
                                <li><a href="<?php echo getSitePath(''); ?>">Home</a></li>
                                <li><a href="<?php echo getSitePath('about'); ?>">About Us</a></li>
                                <li><a href="<?php echo getSitePath('services'); ?>">Services</a></li>
                                <li><a href="<?php echo getSitePath('case-studies'); ?>">Case Studies</a></li>
                                <li><a href="<?php echo getSitePath('blog'); ?>">Blog</a></li>
                                <li><a href="<?php echo getSitePath('contact'); ?>">Contact Us</a></li>
                            </ul>
                        </div>
                        <div class="footer-v2-nav-col">
                            <h4 class="footer-v2-nav-heading">Our Services</h4>
                            <ul class="footer-v2-nav-list">
                                <?php if (!empty($services)): ?>
                                    <?php foreach ($services as $svc_footer): ?>
                                    <li><a href="<?php echo getSitePath('services/' . htmlspecialchars($svc_footer['slug'] ?? strtolower(str_replace(' ', '-', $svc_footer['title'])))); ?>"><?php echo htmlspecialchars($svc_footer['title']); ?></a></li>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <li><a href="<?php echo getSitePath('services/graphics-design'); ?>">Graphics Design</a></li>
                                    <li><a href="<?php echo getSitePath('services/brand-identity'); ?>">Brand Identity</a></li>
                                    <li><a href="<?php echo getSitePath('services/social-media-marketing'); ?>">Social Media Marketing</a></li>
                                    <li><a href="<?php echo getSitePath('services/web-development'); ?>">Web Development</a></li>
                                    <li><a href="<?php echo getSitePath('services/seo-services'); ?>">SEO Services</a></li>
                                    <li><a href="<?php echo getSitePath('services/content-marketing'); ?>">Content Marketing</a></li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </nav>

                    <div class="footer-v2-cta">
                        <h3 class="footer-v2-cta-title">Ready to Transform Your Brand?</h3>
                        <p class="footer-v2-cta-text">Let's create something extraordinary together. Get in touch with us today and start your digital journey.</p>
                        <a href="<?php echo getSitePath('contact'); ?>" class="footer-v2-cta-btn">
                            Get enquiry now <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom bar: outside the dark card -->
        <div class="footer-v2-bottom-bar">
            <div class="container">
                <div class="footer-v2-legal">
                    <span>&copy; <?php echo date('Y'); ?> Kalpanik</span>
                    <span class="footer-v2-dot">&bull;</span>
                    <span>All Rights Reserved</span>
                    <span class="footer-v2-dot">&bull;</span>
                    <a href="#">Terms & Conditions</a>
                    <span class="footer-v2-dot">&bull;</span>
                    <a href="#">Privacy Policy</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <button id="backToTop" class="back-to-top" aria-label="Back to top">
        <i class="fas fa-chevron-up"></i>
    </button>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- AOS Animation Library -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <!-- GSAP Animation Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>

    <!-- Custom JS (with auto cache-busting) -->
    <script src="<?php echo getSitePath('assets/js/main.js'); ?>?v=<?php echo filemtime('assets/js/main.js'); ?>"></script>
</body>
</html>
