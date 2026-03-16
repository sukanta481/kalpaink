<?php
$page_title = 'Terms & Conditions';
$page_seo = [
    'title' => 'Terms & Conditions - ' . (defined('SITE_NAME') ? SITE_NAME : 'Kalpanik'),
    'description' => 'Terms and conditions for using Kalpanik\'s content marketing, brand identity, and creative design services.',
];
include 'includes/header.php';
?>

    <section class="section-padding" style="padding-top: 140px; min-height: 60vh;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <h1 class="mb-4">Terms & Conditions</h1>
                    <p class="text-muted mb-4">Last updated: <?php echo date('F j, Y'); ?></p>

                    <h2>1. Services</h2>
                    <p>Kalpanik provides content marketing, brand identity, creative design, social media management, web development, and related digital marketing services. All project scopes, timelines, and deliverables are agreed upon before work begins.</p>

                    <h2>2. Intellectual Property</h2>
                    <p>Upon full payment, all final deliverables and creative assets become the property of the client. Kalpanik retains the right to showcase completed work in our portfolio and case studies unless otherwise agreed in writing.</p>

                    <h2>3. Payment Terms</h2>
                    <p>Payment terms are outlined in individual project proposals. Typically, a 50% advance is required before project commencement, with the remaining balance due upon completion.</p>

                    <h2>4. Revisions</h2>
                    <p>Each project includes a defined number of revision rounds as specified in the project proposal. Additional revisions beyond the agreed scope may incur extra charges.</p>

                    <h2>5. Confidentiality</h2>
                    <p>We treat all client information as confidential. Non-disclosure agreements (NDAs) are available upon request for sensitive projects.</p>

                    <h2>6. Limitation of Liability</h2>
                    <p>Kalpanik's liability is limited to the total amount paid for the specific service in question. We are not liable for indirect, incidental, or consequential damages.</p>

                    <h2>7. Contact</h2>
                    <p>For questions about these terms, contact us at <a href="mailto:<?php echo CONTACT_EMAIL; ?>"><?php echo CONTACT_EMAIL; ?></a>.</p>
                </div>
            </div>
        </div>
    </section>

<?php include 'includes/footer.php'; ?>
