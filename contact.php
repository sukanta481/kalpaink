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
        // In a real application, you would send an email or save to database here
        // For now, we'll just show a success message
        
        // Example: Save to a file (for demo purposes)
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

    <!-- Contact Hero Section -->
    <section class="contact-hero">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 text-center">
                    <div class="placeholder-image" style="height: 200px; border-radius: 15px; margin-bottom: -80px;">
                        <i class="fas fa-envelope-open-text" style="font-size: 4rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Form Section -->
    <section class="section-padding" style="padding-top: 0;">
        <div class="container">
            <div class="contact-form-card" data-aos="fade-up">
                <h2 class="mb-2">Congratulations!!</h2>
                <p class="text-muted mb-4">You're One Step Away from Digital Success. Get in Touch!</p>
                
                <?php if ($form_submitted || $form_error): ?>
                <div class="alert alert-<?php echo $form_submitted ? 'success' : 'danger'; ?> alert-dismissible fade show" role="alert">
                    <?php echo $form_message; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>
                
                <form id="contactForm" method="POST" action="">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Your name *</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Your name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Your email address *</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Your email address" required>
                        </div>
                        <div class="col-md-6">
                            <label for="phone" class="form-label">Contact number</label>
                            <input type="tel" class="form-control" id="phone" name="phone" placeholder="Contact number">
                        </div>
                        <div class="col-md-6">
                            <label for="country" class="form-label">Country</label>
                            <select class="form-select" id="country" name="country">
                                <option value="India" selected>India</option>
                                <option value="USA">USA</option>
                                <option value="UK">UK</option>
                                <option value="Canada">Canada</option>
                                <option value="Australia">Australia</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="message" class="form-label">Give us a Brief *</label>
                            <textarea class="form-control" id="message" name="message" rows="5" placeholder="Message" required></textarea>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary submit-btn">Submit</button>
                        </div>
                    </div>
                </form>
                
                <p class="privacy-note">
                    <i class="fas fa-lock me-1"></i> 
                    We ensure complete privacy when it comes to your information. All information, particulars and details provided are kept 100% confidential and only shared with our specific department heads only if necessary. Please feel free to request a Non-Disclosure Agreement if required.
                </p>
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
