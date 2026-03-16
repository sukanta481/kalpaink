<?php
$page_title = 'Privacy Policy';
$page_seo = [
    'title' => 'Privacy Policy - ' . (defined('SITE_NAME') ? SITE_NAME : 'Kalpanik'),
    'description' => 'Privacy policy for Kalpanik\'s website. Learn how we collect, use, and protect your personal information.',
];
include 'includes/header.php';
?>

    <section class="section-padding" style="padding-top: 140px; min-height: 60vh;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <h1 class="mb-4">Privacy Policy</h1>
                    <p class="text-muted mb-4">Last updated: <?php echo date('F j, Y'); ?></p>

                    <h2>1. Information We Collect</h2>
                    <p>When you contact us through our website, we collect your name, email address, phone number, and project details. We also collect standard web analytics data including IP addresses and browser information.</p>

                    <h2>2. How We Use Your Information</h2>
                    <p>We use the information you provide to respond to inquiries, deliver our content marketing and creative design services, and improve our website experience. We do not sell or share your personal information with third parties for marketing purposes.</p>

                    <h2>3. Data Storage</h2>
                    <p>Your information is stored securely on our servers. Contact form submissions are processed and stored in our CRM system for the purpose of responding to your inquiry and managing our client relationships.</p>

                    <h2>4. Cookies</h2>
                    <p>Our website uses essential cookies for functionality and analytics cookies to understand how visitors interact with our site. You can control cookie settings through your browser preferences.</p>

                    <h2>5. Third-Party Services</h2>
                    <p>We use Google Fonts, Bootstrap CDN, and analytics services. These third-party services may collect anonymized usage data according to their own privacy policies.</p>

                    <h2>6. Your Rights</h2>
                    <p>You have the right to access, correct, or delete your personal information. To exercise these rights, contact us at <a href="mailto:<?php echo CONTACT_EMAIL; ?>"><?php echo CONTACT_EMAIL; ?></a>.</p>

                    <h2>7. Contact</h2>
                    <p>For privacy-related questions, reach us at <a href="mailto:<?php echo CONTACT_EMAIL; ?>"><?php echo CONTACT_EMAIL; ?></a> or call <a href="tel:<?php echo CONTACT_PHONE; ?>"><?php echo CONTACT_PHONE; ?></a>.</p>
                </div>
            </div>
        </div>
    </section>

<?php include 'includes/footer.php'; ?>
