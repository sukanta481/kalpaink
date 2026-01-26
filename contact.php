<?php 
$page_title = 'Contact Us';
include 'includes/header.php'; 

// Form submission handling
$form_submitted = false;
$form_error = false;
$form_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $name = isset($_POST['name']) ? htmlspecialchars(trim($_POST['name'])) : '';
    $email = isset($_POST['email']) ? htmlspecialchars(trim($_POST['email'])) : '';
    $phone = isset($_POST['phone']) ? htmlspecialchars(trim($_POST['phone'])) : '';
    $country = isset($_POST['country']) ? htmlspecialchars(trim($_POST['country'])) : '';
    $message = isset($_POST['message']) ? htmlspecialchars(trim($_POST['message'])) : '';
    
    // Basic validation
    if (empty($name) || empty($email) || empty($message)) {
        $form_error = true;
        $form_message = 'Please fill in all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $form_error = true;
        $form_message = 'Please enter a valid email address.';
    } else {
        // Try to save to database (CRM)
        $saved_to_db = false;
        
        try {
            // Include database config if CRM is installed
            $db_config = __DIR__ . '/admin/config/database.php';
            if (file_exists($db_config)) {
                require_once $db_config;
                $db = getDB();
                
                $stmt = $db->prepare("INSERT INTO leads (name, email, phone, country, message, source, status, priority) VALUES (?, ?, ?, ?, ?, 'contact_form', 'new', 'medium')");
                $stmt->execute([$name, $email, $phone, $country, $message]);
                $saved_to_db = true;
            }
        } catch (Exception $e) {
            // Database not available, fall back to file
            $saved_to_db = false;
        }
        
        // Also save to file as backup
        $submission = date('Y-m-d H:i:s') . " | Name: $name | Email: $email | Phone: $phone | Country: $country | Message: $message\n";
        $log_file = 'uploads/contact_submissions.txt';
        
        // Create uploads directory if it doesn't exist
        if (!file_exists('uploads')) {
            mkdir('uploads', 0755, true);
        }
        
        // Append to log file
        file_put_contents($log_file, $submission, FILE_APPEND);
        
        $form_submitted = true;
        $form_message = 'Thank you for your message! We will get back to you soon.';
    }
}
?>

    <!-- Contact Hero Section - "The First Step" -->
    <section class="contact-hero-v2">
        <div class="container">
            <div class="row align-items-center g-5">
                <!-- Left: Hero Content -->
                <div class="col-lg-6" data-aos="fade-right">
                    <span class="hero-label">
                        <i class="fas fa-paper-plane me-2"></i>
                        Start Your Journey
                    </span>
                    <h1 class="hero-headline">Let's Sculpt<br>Your Vision.</h1>
                    <p class="hero-subtext">You have the idea. We have the chisel. Fill out the form below to start the transformation.</p>
                    
                    <!-- Trust Indicators -->
                    <div class="trust-row">
                        <div class="trust-item">
                            <i class="fas fa-shield-alt"></i>
                            <span>100% Confidential</span>
                        </div>
                        <div class="trust-item">
                            <i class="fas fa-clock"></i>
                            <span>Response in 24hrs</span>
                        </div>
                    </div>
                </div>
                
                <!-- Right: Hero Image -->
                <div class="col-lg-6" data-aos="fade-left">
                    <div class="hero-visual">
                        <div class="hero-image-wrapper">
                            <img src="assets/images/hero/contact-hero.jpg" alt="Let's Connect" onerror="this.style.display='none'; this.parentElement.classList.add('fallback-active');">
                            <!-- Fallback if no image -->
                            <div class="hero-image-fallback">
                                <div class="floating-icons">
                                    <div class="float-icon icon-1"><i class="fas fa-mobile-alt"></i></div>
                                    <div class="float-icon icon-2"><i class="fas fa-comments"></i></div>
                                    <div class="float-icon icon-3"><i class="fas fa-handshake"></i></div>
                                    <div class="float-icon icon-4"><i class="fas fa-lightbulb"></i></div>
                                </div>
                                <div class="central-icon">
                                    <i class="fas fa-rocket"></i>
                                </div>
                            </div>
                        </div>
                        <!-- Decorative Elements -->
                        <div class="hero-decor decor-1"></div>
                        <div class="hero-decor decor-2"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Form Section - "Dark Mode VIP" -->
    <section class="contact-form-section">
        <div class="container">
            <div class="contact-form-card-v2" data-aos="fade-up">
                <!-- Form Header -->
                <div class="form-header">
                    <div class="form-header-badge">
                        <i class="fas fa-gem"></i>
                        <span>VIP Inquiry</span>
                    </div>
                    <h2 class="form-headline">Tell Us About Your Project</h2>
                    <p class="form-subtext">Every great partnership starts with a conversation.</p>
                </div>
                
                <?php if ($form_submitted || $form_error): ?>
                <div class="alert alert-<?php echo $form_submitted ? 'success' : 'danger'; ?> alert-dismissible fade show" role="alert" style="background: <?php echo $form_submitted ? 'rgba(34, 197, 94, 0.2)' : 'rgba(239, 68, 68, 0.2)'; ?>; border: none; color: var(--white);">
                    <?php echo $form_message; ?>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>
                
                <form id="contactForm" method="POST" action="">
                    <div class="row g-4">
                        <!-- Name Field -->
                        <div class="col-md-6">
                            <div class="form-group-dark">
                                <label for="name" class="form-label-dark">What's your name? <span class="required">*</span></label>
                                <input type="text" class="form-control-dark" id="name" name="name" placeholder="John Doe" required>
                            </div>
                        </div>
                        
                        <!-- Email Field -->
                        <div class="col-md-6">
                            <div class="form-group-dark">
                                <label for="email" class="form-label-dark">Where should we send the proposal? <span class="required">*</span></label>
                                <input type="email" class="form-control-dark" id="email" name="email" placeholder="john@company.com" required>
                            </div>
                        </div>
                        
                        <!-- Phone Field -->
                        <div class="col-md-6">
                            <div class="form-group-dark">
                                <label for="phone" class="form-label-dark">Best number to reach you?</label>
                                <input type="tel" class="form-control-dark" id="phone" name="phone" placeholder="+91 98765 43210">
                            </div>
                        </div>
                        
                        <!-- Country Field -->
                        <div class="col-md-6">
                            <div class="form-group-dark">
                                <label for="country" class="form-label-dark">Where are you based?</label>
                                <select class="form-select-dark" id="country" name="country">
                                    <option value="India" selected>India</option>
                                    <option value="USA">USA</option>
                                    <option value="UK">UK</option>
                                    <option value="Canada">Canada</option>
                                    <option value="Australia">Australia</option>
                                    <option value="UAE">UAE</option>
                                    <option value="Singapore">Singapore</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>
                        
                        <!-- Message Field -->
                        <div class="col-12">
                            <div class="form-group-dark">
                                <label for="message" class="form-label-dark">Tell us about your dream project... <span class="required">*</span></label>
                                <textarea class="form-control-dark" id="message" name="message" rows="5" placeholder="I'm looking to create a brand identity that stands out. My target audience is..." required></textarea>
                            </div>
                        </div>
                        
                        <!-- Submit Button -->
                        <div class="col-12">
                            <button type="submit" class="btn-neon-submit">
                                <span>Let's Make Magic</span>
                                <i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </div>
                </form>
                
                <!-- Privacy Note -->
                <div class="privacy-note-dark">
                    <i class="fas fa-shield-alt"></i>
                    <p>Your information is 100% confidential. We never share your details. Request an NDA anytime.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Info Section -->
    <section class="section-padding bg-light-gray">
        <div class="container">
            <div class="row g-4 align-items-stretch">
                <div class="col-lg-5" data-aos="fade-right">
                    <div class="contact-info-card h-100">
                        <h3 class="mb-4">Kalpoink</h3>
                        
                        <div class="contact-info-item">
                            <div class="contact-info-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div>
                                <p class="mb-0"><?php echo CONTACT_ADDRESS; ?></p>
                            </div>
                        </div>
                        
                        <div class="contact-info-item">
                            <div class="contact-info-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div>
                                <a href="tel:<?php echo CONTACT_PHONE; ?>"><?php echo CONTACT_PHONE; ?></a>
                            </div>
                        </div>
                        
                        <div class="contact-info-item mb-0">
                            <div class="contact-info-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div>
                                <a href="mailto:<?php echo CONTACT_EMAIL; ?>"><?php echo CONTACT_EMAIL; ?></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7" data-aos="fade-left">
                    <div class="map-container h-100" style="min-height: 300px;">
                        <iframe 
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3683.7567075776!2d88.3788!3d22.5726!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMjLCsDM0JzIxLjQiTiA4OMKwMjInNDMuNyJF!5e0!3m2!1sen!2sin!4v1234567890"
                            style="border:0;" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php include 'includes/footer.php'; ?>
