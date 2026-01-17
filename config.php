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

// Team Information - The Creators
$team_members = [
    [
        'name' => 'Suman Kundu',
        'position' => 'Co-Founder & Creative Director',
        'image_pro' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400&h=500&fit=crop',
        'image_fun' => 'https://images.unsplash.com/photo-1539571696357-5a69c17a67c6?w=400&h=500&fit=crop',
        'tagline' => 'Turning caffeine into creativity since 2016',
        'linkedin' => '#'
    ],
    [
        'name' => 'Souvik Das',
        'position' => 'Co-Founder & Strategy Lead',
        'image_pro' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=400&h=500&fit=crop',
        'image_fun' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=400&h=500&fit=crop',
        'tagline' => 'Making brands unforgettable, one pixel at a time',
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
        'image' => 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?w=600&h=800&fit=crop',
        'tags' => ['Branding', 'Logo']
    ],
    [
        'title' => 'E-Commerce Website Design',
        'category' => 'Web Design',
        'image' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=600&h=400&fit=crop',
        'tags' => ['UI/UX', 'Web']
    ],
    [
        'title' => 'Social Media Campaign',
        'category' => 'Marketing',
        'image' => 'https://images.unsplash.com/photo-1611162616305-c69b3fa7fbe0?w=800&h=400&fit=crop',
        'tags' => ['SMM', 'Content']
    ],
    [
        'title' => 'Corporate Identity Design',
        'category' => 'Branding',
        'image' => 'https://images.unsplash.com/photo-1600880292203-757bb62b4baf?w=600&h=400&fit=crop',
        'tags' => ['Branding', 'Print']
    ],
    [
        'title' => 'Mobile App UI Design',
        'category' => 'UI/UX',
        'image' => 'https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?w=600&h=800&fit=crop',
        'tags' => ['UI/UX', 'Mobile']
    ],
    [
        'title' => 'YouTube Channel Branding',
        'category' => 'YouTube',
        'image' => 'https://images.unsplash.com/photo-1611162618071-b39a2ec055fb?w=600&h=400&fit=crop',
        'tags' => ['YouTube', 'Branding']
    ],
    [
        'title' => 'Startup Brand Strategy',
        'category' => 'Branding',
        'image' => 'https://images.unsplash.com/photo-1553028826-f4804a6dba3b?w=800&h=400&fit=crop',
        'tags' => ['Strategy', 'Branding']
    ],
    [
        'title' => 'Product Photography',
        'category' => 'Photography',
        'image' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=600&h=400&fit=crop',
        'tags' => ['Photography', 'Product']
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
