<?php
/**
 * Kalpoink - Configuration File
 * Digital Marketing Agency Website
 */

// Site Configuration
define('SITE_NAME', 'Kalpoink');
define('SITE_TAGLINE', 'Creative Digital Solutions');
define('SITE_URL', 'http://localhost/kalpoink');

// Contact Information
define('CONTACT_ADDRESS', '225 Bagmari Road, Kolkata - 700054');
define('CONTACT_PHONE', '+91 891 082 1105');
define('CONTACT_EMAIL', 'kalpoinc@gmail.com');

// Social Media Links (Update with actual links)
define('SOCIAL_FACEBOOK', '#');
define('SOCIAL_INSTAGRAM', '#');
define('SOCIAL_LINKEDIN', '#');
define('SOCIAL_TWITTER', '#');

// Team Information
$team_members = [
    [
        'name' => 'Suman Kundu',
        'position' => 'Co-Founder & Partner',
        'image' => 'assets/images/team/suman.jpg',
        'experience' => '10+ Years Experience',
        'linkedin' => '#'
    ],
    [
        'name' => 'Souvik Das',
        'position' => 'Co-Founder & Partner',
        'image' => 'assets/images/team/souvik.jpg',
        'experience' => '10+ Years Experience',
        'linkedin' => '#'
    ]
];

// Services
$services = [
    [
        'icon' => 'fa-palette',
        'title' => 'Graphics Design',
        'description' => 'Eye-catching visuals that capture your brand essence. From logos to complete brand identity packages.'
    ],
    [
        'icon' => 'fa-bullhorn',
        'title' => 'Brand Identity',
        'description' => 'Build a memorable brand with consistent visual identity across all touchpoints.'
    ],
    [
        'icon' => 'fa-share-nodes',
        'title' => 'Social Media Marketing',
        'description' => 'Strategic social media campaigns that engage audiences and drive conversions.'
    ],
    [
        'icon' => 'fa-code',
        'title' => 'Web Development',
        'description' => 'Modern, responsive websites that deliver exceptional user experiences.'
    ],
    [
        'icon' => 'fa-magnifying-glass',
        'title' => 'SEO Services',
        'description' => 'Improve your search rankings and drive organic traffic to your website.'
    ],
    [
        'icon' => 'fa-pen-nib',
        'title' => 'Content Marketing',
        'description' => 'Compelling content that tells your story and connects with your audience.'
    ]
];

// Demo Case Studies
$case_studies = [
    [
        'title' => 'Modern Restaurant Branding',
        'category' => 'Branding',
        'image' => 'assets/images/portfolio/project1.jpg',
        'tags' => ['Branding', 'Logo']
    ],
    [
        'title' => 'E-Commerce Website Design',
        'category' => 'Web Design',
        'image' => 'assets/images/portfolio/project2.jpg',
        'tags' => ['UI/UX', 'Web']
    ],
    [
        'title' => 'Social Media Campaign',
        'category' => 'Marketing',
        'image' => 'assets/images/portfolio/project3.jpg',
        'tags' => ['SMM', 'Content']
    ],
    [
        'title' => 'Corporate Identity Design',
        'category' => 'Branding',
        'image' => 'assets/images/portfolio/project4.jpg',
        'tags' => ['Branding', 'Print']
    ],
    [
        'title' => 'Mobile App UI Design',
        'category' => 'UI/UX',
        'image' => 'assets/images/portfolio/project5.jpg',
        'tags' => ['UI/UX', 'Mobile']
    ],
    [
        'title' => 'YouTube Channel Branding',
        'category' => 'YouTube',
        'image' => 'assets/images/portfolio/project6.jpg',
        'tags' => ['YouTube', 'Branding']
    ]
];

// FAQ Items
$faqs = [
    [
        'question' => 'What services does Kalpoink offer?',
        'answer' => 'Kalpoink specializes in graphics design, brand identity, social media marketing, web development, SEO services, and content marketing. Our primary expertise is in all types of graphics work.'
    ],
    [
        'question' => 'Where is Kalpoink located?',
        'answer' => 'We are based in Kolkata, West Bengal, India. Our office is located at 225 Bagmari Road, Kolkata - 700054.'
    ],
    [
        'question' => 'How long does a typical project take?',
        'answer' => 'Project timelines vary based on complexity. A logo design might take 1-2 weeks, while a complete brand identity package could take 4-6 weeks. We\'ll provide a detailed timeline after understanding your requirements.'
    ],
    [
        'question' => 'Do you work with clients outside Kolkata?',
        'answer' => 'Yes! We work with clients across India and internationally. Our digital workflow allows us to collaborate seamlessly regardless of location.'
    ],
    [
        'question' => 'What makes Kalpoink different from other agencies?',
        'answer' => 'Our focus on creative excellence combined with strategic thinking sets us apart. With our partners\' combined experience, we deliver work that not only looks great but also drives real business results.'
    ]
];
?>
