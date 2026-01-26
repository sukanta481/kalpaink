    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <!-- Mobile: Social Icons at TOP (visible only on mobile) -->
            <div class="footer-social-top d-lg-none">
                <a href="<?php echo SOCIAL_FACEBOOK; ?>" target="_blank" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                <a href="<?php echo SOCIAL_INSTAGRAM; ?>" target="_blank" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                <a href="<?php echo SOCIAL_LINKEDIN; ?>" target="_blank" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
            </div>
            
            <div class="row footer-main-row">
                <!-- Company Info -->
                <div class="col-lg-4 col-12 footer-brand-col">
                    <div class="footer-brand">
                        <a href="index.php" class="footer-logo">Kalpoink</a>
                        <p class="footer-description">
                            One of the fastest-growing digital marketing agencies in Kolkata. Kalpoink's expertise coupled with their target-oriented approach is what your business requires to reach digital success.
                        </p>
                        <a href="about.php" class="know-more-link d-none d-lg-inline-block">KNOW MORE</a>
                    </div>
                </div>
                
                <!-- Services (Accordion on Mobile) -->
                <div class="col-lg-2 col-12 footer-accordion-col">
                    <div class="footer-accordion-item">
                        <h5 class="footer-title footer-accordion-trigger" data-target="footer-services">
                            Services
                            <i class="fas fa-plus d-lg-none"></i>
                        </h5>
                        <ul class="footer-links footer-accordion-content" id="footer-services">
                            <li><a href="services.php#graphics">Graphics Design</a></li>
                            <li><a href="services.php#branding">Branding</a></li>
                            <li><a href="services.php#smm">SMM</a></li>
                            <li><a href="services.php#seo">SEO</a></li>
                            <li><a href="services.php#content">Content Marketing</a></li>
                            <li><a href="services.php#web">Web Development</a></li>
                        </ul>
                    </div>
                </div>
                
                <!-- Quick Links (Accordion on Mobile) -->
                <div class="col-lg-2 col-12 footer-accordion-col">
                    <div class="footer-accordion-item">
                        <h5 class="footer-title footer-accordion-trigger" data-target="footer-links">
                            Quick Links
                            <i class="fas fa-plus d-lg-none"></i>
                        </h5>
                        <ul class="footer-links footer-accordion-content" id="footer-links">
                            <li><a href="about.php">About Us</a></li>
                            <li><a href="case-studies.php">Case Studies</a></li>
                            <li><a href="blog.php">Blog</a></li>
                            <li><a href="contact.php">Contact Us</a></li>
                        </ul>
                    </div>
                </div>
                
                <!-- Contact Info (Accordion on Mobile) -->
                <div class="col-lg-4 col-12 footer-accordion-col footer-contact-col">
                    <div class="footer-accordion-item">
                        <h5 class="footer-title footer-accordion-trigger" data-target="footer-contact">
                            Contact Us
                            <i class="fas fa-plus d-lg-none"></i>
                        </h5>
                        <div class="footer-accordion-content" id="footer-contact">
                            <ul class="footer-contact">
                                <li>
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span><?php echo CONTACT_ADDRESS; ?></span>
                                </li>
                                <li>
                                    <i class="fas fa-phone"></i>
                                    <a href="tel:<?php echo CONTACT_PHONE; ?>"><?php echo CONTACT_PHONE; ?></a>
                                </li>
                                <li>
                                    <i class="fas fa-envelope"></i>
                                    <a href="mailto:<?php echo CONTACT_EMAIL; ?>"><?php echo CONTACT_EMAIL; ?></a>
                                </li>
                            </ul>
                            
                            <!-- Desktop: Editorial Social Links -->
                            <div class="social-editorial d-none d-lg-flex">
                                <a href="<?php echo SOCIAL_FACEBOOK; ?>" target="_blank">Facebook <i class="fas fa-arrow-up-right"></i></a>
                                <a href="<?php echo SOCIAL_INSTAGRAM; ?>" target="_blank">Instagram <i class="fas fa-arrow-up-right"></i></a>
                                <a href="<?php echo SOCIAL_LINKEDIN; ?>" target="_blank">LinkedIn <i class="fas fa-arrow-up-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Footer Bottom -->
            <div class="footer-bottom">
                <div class="footer-bottom-content">
                    <span>&copy; <?php echo date('Y'); ?> <strong>Kalpoink</strong></span>
                    <span class="separator">|</span>
                    <span>All Rights Reserved</span>
                    <span class="separator">|</span>
                    <a href="#">Terms & Conditions</a>
                    <span class="separator">|</span>
                    <a href="#">Privacy Policy</a>
                    <span class="separator">|</span>
                    <span>Design by <a href="https://biznexa.tech" target="_blank" class="designer-link">Biznexa</a></span>
                </div>
            </div>
        </div>
        
        <!-- Giant Watermark -->
        <div class="footer-watermark">KALPOINK</div>
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
    <script src="assets/js/main.js?v=<?php echo filemtime('assets/js/main.js'); ?>"></script>
</body>
</html>
