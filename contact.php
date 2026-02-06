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

    <!-- Contact Hero -->
    <section class="contact-hero-v3">
        <div class="contact-hero-v3-bg">
            <div class="ch-orb ch-orb--1"></div>
            <div class="ch-orb ch-orb--2"></div>
            <div class="ch-orb ch-orb--3"></div>
        </div>
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-7" data-aos="fade-up">
                    <div class="ch-eyebrow">
                        <span class="ch-eyebrow-dot"></span>
                        <span class="ch-eyebrow-text">Let's Talk</span>
                    </div>
                    <h1 class="ch-title">
                        Got a project?<br>
                        <span class="ch-title-accent">Let's make it real.</span>
                    </h1>
                    <p class="ch-subtitle">Whether it's a bold rebrand, a digital product, or a campaign that breaks the mold — we're ready. Drop us a line and let's start something great.</p>
                    
                    <div class="ch-trust-pills">
                        <div class="ch-pill">
                            <i class="fas fa-shield-alt"></i>
                            <span>100% Confidential</span>
                        </div>
                        <div class="ch-pill">
                            <i class="fas fa-bolt"></i>
                            <span>Reply in 24hrs</span>
                        </div>
                        <div class="ch-pill">
                            <i class="fas fa-handshake"></i>
                            <span>NDA Available</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5" data-aos="fade-up" data-aos-delay="150">
                    <div class="ch-quick-connect">
                        <div class="ch-qc-header">
                            <div class="ch-qc-dot-row"><span></span><span></span><span></span></div>
                            <span class="ch-qc-label">Quick Connect</span>
                        </div>
                        <div class="ch-qc-body">
                            <a href="tel:<?php echo CONTACT_PHONE; ?>" class="ch-qc-item">
                                <div class="ch-qc-icon"><i class="fas fa-phone"></i></div>
                                <div class="ch-qc-info">
                                    <span class="ch-qc-info-label">Call us</span>
                                    <span class="ch-qc-info-value"><?php echo CONTACT_PHONE; ?></span>
                                </div>
                                <i class="fas fa-arrow-right ch-qc-arrow"></i>
                            </a>
                            <a href="mailto:<?php echo CONTACT_EMAIL; ?>" class="ch-qc-item">
                                <div class="ch-qc-icon"><i class="fas fa-envelope"></i></div>
                                <div class="ch-qc-info">
                                    <span class="ch-qc-info-label">Email us</span>
                                    <span class="ch-qc-info-value"><?php echo CONTACT_EMAIL; ?></span>
                                </div>
                                <i class="fas fa-arrow-right ch-qc-arrow"></i>
                            </a>
                            <a href="https://maps.google.com" target="_blank" class="ch-qc-item">
                                <div class="ch-qc-icon"><i class="fas fa-map-marker-alt"></i></div>
                                <div class="ch-qc-info">
                                    <span class="ch-qc-info-label">Visit us</span>
                                    <span class="ch-qc-info-value">Kolkata, India</span>
                                </div>
                                <i class="fas fa-arrow-right ch-qc-arrow"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Form Section -->
    <section class="contact-form-v3">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="cf3-card" data-aos="fade-up">
                        <div class="cf3-card-glow"></div>
                        
                        <!-- Left accent bar -->
                        <div class="cf3-accent-bar"></div>
                        
                        <div class="row g-0">
                            <!-- Form Side -->
                            <div class="col-lg-7">
                                <div class="cf3-form-side">
                                    <div class="cf3-header">
                                        <h2 class="cf3-headline">Tell us about your project</h2>
                                        <p class="cf3-subtext">Fill in the details and we'll get back to you shortly.</p>
                                    </div>
                                    
                                    <?php if ($form_submitted || $form_error): ?>
                                    <div class="alert alert-<?php echo $form_submitted ? 'success' : 'danger'; ?> alert-dismissible fade show" role="alert" style="background: <?php echo $form_submitted ? 'rgba(34, 197, 94, 0.15)' : 'rgba(239, 68, 68, 0.15)'; ?>; border: 1px solid <?php echo $form_submitted ? 'rgba(34, 197, 94, 0.3)' : 'rgba(239, 68, 68, 0.3)'; ?>; color: <?php echo $form_submitted ? '#22c55e' : '#ef4444'; ?>; border-radius: 12px;">
                                        <?php echo $form_message; ?>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="filter: <?php echo $form_submitted ? 'invert(59%) sepia(50%) saturate(600%) hue-rotate(100deg)' : 'invert(36%) sepia(80%) saturate(3000%) hue-rotate(345deg)'; ?>"></button>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <form id="contactForm" method="POST" action="">
                                        <div class="row g-3">
                                            <div class="col-sm-6">
                                                <div class="cf3-field">
                                                    <label class="cf3-label">Your name <span>*</span></label>
                                                    <input type="text" class="cf3-input" name="name" placeholder="John Doe" required>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="cf3-field">
                                                    <label class="cf3-label">Email address <span>*</span></label>
                                                    <input type="email" class="cf3-input" name="email" placeholder="john@company.com" required>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="cf3-field">
                                                    <label class="cf3-label">Phone number</label>
                                                    <input type="tel" class="cf3-input" name="phone" placeholder="+91 98765 43210">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="cf3-field">
                                                    <label class="cf3-label">Location</label>
                                                    <select class="cf3-input cf3-select" name="country">
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
                                            <div class="col-12">
                                                <div class="cf3-field">
                                                    <label class="cf3-label">Project details <span>*</span></label>
                                                    <textarea class="cf3-input cf3-textarea" name="message" rows="4" placeholder="Tell us about your vision, goals, and timeline..." required></textarea>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <button type="submit" class="cf3-submit">
                                                    <span>Send Message</span>
                                                    <i class="fas fa-paper-plane"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            
                            <!-- Info Side -->
                            <div class="col-lg-5">
                                <div class="cf3-info-side">
                                    <div class="cf3-info-content">
                                        <h3 class="cf3-info-title">Let's connect</h3>
                                        <p class="cf3-info-desc">We'd love to hear from you. Reach out through any channel that works best for you.</p>
                                        
                                        <div class="cf3-info-items">
                                            <div class="cf3-info-item">
                                                <div class="cf3-info-icon">
                                                    <i class="fas fa-map-marker-alt"></i>
                                                </div>
                                                <div>
                                                    <span class="cf3-info-label">Address</span>
                                                    <p><?php echo CONTACT_ADDRESS; ?></p>
                                                </div>
                                            </div>
                                            <div class="cf3-info-item">
                                                <div class="cf3-info-icon">
                                                    <i class="fas fa-phone"></i>
                                                </div>
                                                <div>
                                                    <span class="cf3-info-label">Phone</span>
                                                    <p><a href="tel:<?php echo CONTACT_PHONE; ?>"><?php echo CONTACT_PHONE; ?></a></p>
                                                </div>
                                            </div>
                                            <div class="cf3-info-item">
                                                <div class="cf3-info-icon">
                                                    <i class="fas fa-envelope"></i>
                                                </div>
                                                <div>
                                                    <span class="cf3-info-label">Email</span>
                                                    <p><a href="mailto:<?php echo CONTACT_EMAIL; ?>"><?php echo CONTACT_EMAIL; ?></a></p>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="cf3-social-row">
                                            <span class="cf3-social-label">Follow us</span>
                                            <div class="cf3-socials">
                                                <a href="#" class="cf3-social"><i class="fab fa-facebook-f"></i></a>
                                                <a href="#" class="cf3-social"><i class="fab fa-instagram"></i></a>
                                                <a href="#" class="cf3-social"><i class="fab fa-linkedin-in"></i></a>
                                                <a href="#" class="cf3-social"><i class="fab fa-twitter"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section class="contact-map-v3">
        <div class="container">
            <div class="map-card-v3" data-aos="fade-up">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3683.7567075776!2d88.3788!3d22.5726!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMjLCsDM0JzIxLjQiTiA4OMKwMjInNDMuNyJF!5e0!3m2!1sen!2sin!4v1234567890"
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
    </section>

<?php include 'includes/footer.php'; ?>
