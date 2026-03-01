    <!-- Footer -->
    <footer class="footer-v2">
        <!-- Dark card with rounded top corners -->
        <div class="footer-v2-card">
            <div class="container">
                <!-- Row 1: Brand + CTA -->
                <div class="footer-v2-top">
                    <div class="footer-v2-left">
                        <a href="index.php" class="footer-v2-logo">
                            <img src="<?php echo SITE_LOGO; ?>" alt="<?php echo SITE_NAME; ?>">
                        </a>
                        <div class="footer-v2-socials">
                            <a href="<?php echo SOCIAL_FACEBOOK; ?>" target="_blank" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                            <a href="<?php echo SOCIAL_INSTAGRAM; ?>" target="_blank" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                            <a href="<?php echo SOCIAL_LINKEDIN; ?>" target="_blank" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
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
                    <div class="footer-v2-cta">
                        <h3 class="footer-v2-cta-title">Ready to Transform Your Brand?</h3>
                        <p class="footer-v2-cta-text">Let's create something extraordinary together. Get in touch with us today and start your digital journey.</p>
                        <a href="contact.php" class="footer-v2-cta-btn">
                            Gate enquiry now <i class="fas fa-arrow-right"></i>
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
    <script src="assets/js/main.js?v=<?php echo filemtime('assets/js/main.js'); ?>"></script>
</body>
</html>
