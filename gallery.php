<?php
$page_title = 'Gallery';
include 'includes/header.php';

// Get gallery data
$gallery_items = getGalleryFromDB();
$gallery_categories = getGalleryCategoriesFromDB();

// Fallback gallery data when database is empty
$fallback_gallery = [
    ['id' => 1, 'title' => 'Brand Identity Workshop', 'category' => 'events', 'image' => 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=600&h=400&fit=crop', 'description' => 'Team collaboration during brand strategy session'],
    ['id' => 2, 'title' => 'Creative Design Sprint', 'category' => 'events', 'image' => 'https://images.unsplash.com/photo-1475721027785-f74eccf877e2?w=600&h=800&fit=crop', 'description' => 'Design thinking workshop with clients'],
    ['id' => 3, 'title' => 'Social Media Campaign Launch', 'category' => 'portfolio', 'image' => 'https://images.unsplash.com/photo-1611162616305-c69b3fa7fbe0?w=600&h=400&fit=crop', 'description' => 'Campaign launch for premium brand'],
    ['id' => 4, 'title' => 'Office Brainstorm', 'category' => 'office', 'image' => 'https://images.unsplash.com/photo-1497366216548-37526070297c?w=600&h=400&fit=crop', 'description' => 'Our creative workspace'],
    ['id' => 5, 'title' => 'Team Celebration', 'category' => 'team', 'image' => 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=600&h=400&fit=crop', 'description' => 'Celebrating project milestones together'],
    ['id' => 6, 'title' => 'Web Development Showcase', 'category' => 'portfolio', 'image' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=600&h=800&fit=crop', 'description' => 'Modern website design showcase'],
    ['id' => 7, 'title' => 'Client Meeting', 'category' => 'clients', 'image' => 'https://images.unsplash.com/photo-1600880292203-757bb62b4baf?w=600&h=400&fit=crop', 'description' => 'Strategic discussion with valued clients'],
    ['id' => 8, 'title' => 'Photography Session', 'category' => 'portfolio', 'image' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=600&h=400&fit=crop', 'description' => 'Product photography for e-commerce'],
    ['id' => 9, 'title' => 'Annual Meetup 2024', 'category' => 'events', 'image' => 'https://images.unsplash.com/photo-1511578314322-379afb476865?w=600&h=400&fit=crop', 'description' => 'Our annual team get-together'],
    ['id' => 10, 'title' => 'Logo Design Collection', 'category' => 'portfolio', 'image' => 'https://images.unsplash.com/photo-1626785774573-4b799315345d?w=600&h=800&fit=crop', 'description' => 'Curated logo design portfolio'],
    ['id' => 11, 'title' => 'Team Outing', 'category' => 'team', 'image' => 'https://images.unsplash.com/photo-1529156069898-49953e39b3ac?w=600&h=400&fit=crop', 'description' => 'Fun moments outside the office'],
    ['id' => 12, 'title' => 'Studio Setup', 'category' => 'office', 'image' => 'https://images.unsplash.com/photo-1497366754035-f200968a6e72?w=600&h=400&fit=crop', 'description' => 'Where the magic happens'],
];

$items = !empty($gallery_items) ? $gallery_items : $fallback_gallery;

// Build categories from items if DB categories empty
if (empty($gallery_categories)) {
    $cat_counts = [];
    foreach ($items as $item) {
        $cat = $item['category'] ?? 'other';
        $cat_counts[$cat] = ($cat_counts[$cat] ?? 0) + 1;
    }
    $gallery_categories = [];
    foreach ($cat_counts as $cat => $count) {
        $gallery_categories[] = ['category' => $cat, 'count' => $count];
    }
}

// Category icon map
$category_icons = [
    'portfolio' => 'fa-palette',
    'team' => 'fa-users',
    'office' => 'fa-building',
    'events' => 'fa-calendar-star',
    'clients' => 'fa-handshake',
    'other' => 'fa-images',
];

// Group items by category and compute unique sub-categories
$grouped_items = [];
$sub_categories = [];
foreach ($items as $item) {
    $cat = $item['category'] ?? 'other';
    $grouped_items[$cat][] = $item;
    
    // Clean title for sub-categories by removing trailing batch counters (e.g. " - 1", "-1")
    $sub = trim(preg_replace('/\s*-\s*\d+$/', '', $item['title'] ?? ''));
    if (!empty($sub)) {
        if (!isset($sub_categories[$cat])) {
            $sub_categories[$cat] = [];
        }
        if (!in_array($sub, $sub_categories[$cat])) {
            $sub_categories[$cat][] = $sub;
        }
    }
}
?>

    <!-- Gallery Page Hero -->
    <section class="gallery-hero">
        <div class="gallery-hero-bg"></div>
        <div class="container">
            <div class="gallery-hero-content" data-aos="fade-up" data-aos-duration="800">
                <span class="gallery-hero-badge">● Our Gallery</span>
                <h1 class="gallery-hero-title">Moments That<br><span class="gallery-hero-accent">Define Us</span></h1>
                <p class="gallery-hero-subtitle">A visual journey through our events, projects, and the creative energy that drives everything we do.</p>
            </div>
        </div>
    </section>

    <!-- Category Filter Tabs -->
    <section class="gallery-filters-section">
        <div class="container">
            <div class="gallery-filter-bar" data-aos="fade-up" data-aos-delay="100">
                <button class="gallery-filter-btn active" data-filter="all">
                    <i class="fas fa-th-large"></i>
                    <span>All</span>
                    <span class="gallery-filter-count"><?php echo count($items); ?></span>
                </button>
                <?php foreach ($gallery_categories as $gc): ?>
                <button class="gallery-filter-btn" data-filter="<?php echo htmlspecialchars($gc['category']); ?>">
                    <i class="fas <?php echo $category_icons[$gc['category']] ?? 'fa-folder'; ?>"></i>
                    <span><?php echo ucfirst(htmlspecialchars($gc['category'])); ?></span>
                    <span class="gallery-filter-count"><?php echo $gc['count']; ?></span>
                </button>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Sub-Filter Tabs for Events and Clients -->
    <section class="gallery-sub-filters-section" id="gallerySubFilters" style="display: none; padding: 10px 0 25px; margin-top: -15px; margin-bottom: 25px; background: #000;">
        <div class="container">
            <div class="gallery-sub-filter-wrapper" style="display: flex; flex-direction: column; align-items: center; gap: 12px;">
                <span class="gallery-sub-filter-label" style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.12em; color: rgba(255,255,255,0.4); font-weight: 600;">Filter By Event / Client Name</span>
                <?php foreach ($sub_categories as $cat => $subs): ?>
                    <?php if (count($subs) >= 1): ?>
                    <div class="gallery-sub-filter-group" data-category="<?php echo htmlspecialchars($cat); ?>" style="display: none; width: 100%;">
                        <div class="gallery-sub-filter-bar" style="display: flex; flex-wrap: wrap; justify-content: center; gap: 8px;">
                            <button class="gallery-sub-filter-btn active" data-sub-filter="all">
                                <span>All <?php echo ucfirst(htmlspecialchars($cat)); ?></span>
                            </button>
                            <?php foreach ($subs as $sub): ?>
                            <button class="gallery-sub-filter-btn" data-sub-filter="<?php echo htmlspecialchars($sub); ?>">
                                <span><?php echo htmlspecialchars($sub); ?></span>
                            </button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Dynamic Sub-Category Info Banner -->
    <section class="gallery-sub-info-section" id="gallerySubInfo" style="display: none; padding: 0 0 30px; background: #000;">
        <div class="container">
            <?php foreach ($sub_categories as $cat => $subs): ?>
                <?php foreach ($subs as $sub): ?>
                    <?php 
                    // Find description for this sub-category
                    $desc = '';
                    foreach ($items as $item) {
                        $item_sub = trim(preg_replace('/\s*-\s*\d+$/', '', $item['title'] ?? ''));
                        if ($item_sub === $sub && !empty($item['description'])) {
                            $desc = $item['description'];
                            break;
                        }
                    }
                    ?>
                    <div class="gallery-sub-info-block text-center p-4 rounded-4" 
                         data-sub-filter="<?php echo htmlspecialchars($sub); ?>" 
                         data-category="<?php echo htmlspecialchars($cat); ?>"
                         style="display: none; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 16px;">
                        <h2 class="h4 text-white fw-bold mb-2" style="font-family: 'Outfit', 'Inter', sans-serif; letter-spacing: -0.01em;"><?php echo htmlspecialchars($sub); ?></h2>
                        <?php if (!empty($desc)): ?>
                            <p class="text-muted mb-0 mx-auto" style="max-width: 700px; font-size: 0.9rem; line-height: 1.6; font-family: 'Inter', sans-serif;"><?php echo htmlspecialchars($desc); ?></p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Gallery Grid -->
    <section class="gallery-grid-section">
        <div class="container">
            <!-- "All" View: Grouped by Category -->
            <div class="gallery-all-view" id="galleryAllView">
                <?php foreach ($grouped_items as $cat => $cat_items): ?>
                <div class="gallery-category-group" data-category="<?php echo htmlspecialchars($cat); ?>">
                    <div class="gallery-category-header" data-aos="fade-up">
                        <div class="gallery-category-icon">
                            <i class="fas <?php echo $category_icons[$cat] ?? 'fa-folder'; ?>"></i>
                        </div>
                        <div>
                            <h2 class="gallery-category-title"><?php echo ucfirst(htmlspecialchars($cat)); ?></h2>
                            <p class="gallery-category-count"><?php echo count($cat_items); ?> photo<?php echo count($cat_items) !== 1 ? 's' : ''; ?></p>
                        </div>
                    </div>
                    <div class="gallery-masonry">
                        <?php foreach ($cat_items as $gi => $gitem): ?>
                        <div class="gallery-item" data-category="<?php echo htmlspecialchars($cat); ?>" data-sub-category="<?php echo htmlspecialchars(trim(preg_replace('/\s*-\s*\d+$/', '', $gitem['title'] ?? ''))); ?>" data-aos="fade-up" data-aos-delay="<?php echo min($gi * 50, 300); ?>">
                            <div class="gallery-card" data-lightbox="<?php echo htmlspecialchars(!empty($gitem['image']) ? (strpos($gitem['image'], 'http') === 0 ? $gitem['image'] : SITE_URL . '/' . $gitem['image']) : ''); ?>" data-title="<?php echo htmlspecialchars($gitem['title'] ?? ''); ?>" data-category="<?php echo ucfirst(htmlspecialchars($cat)); ?>">
                                <img src="<?php echo htmlspecialchars(!empty($gitem['thumbnail']) ? (strpos($gitem['thumbnail'], 'http') === 0 ? $gitem['thumbnail'] : SITE_URL . '/' . $gitem['thumbnail']) : (!empty($gitem['image']) ? (strpos($gitem['image'], 'http') === 0 ? $gitem['image'] : SITE_URL . '/' . $gitem['image']) : '')); ?>" alt="<?php echo htmlspecialchars(!empty($gitem['image_alt_text']) ? $gitem['image_alt_text'] : ($gitem['title'] ?? 'Gallery image')); ?>" loading="lazy" class="gallery-card-img">
                                <div class="gallery-card-overlay">
                                    <span class="gallery-card-category"><?php echo ucfirst(htmlspecialchars($cat)); ?></span>
                                    <h3 class="gallery-card-title"><?php echo htmlspecialchars($gitem['title'] ?? ''); ?></h3>
                                    <span class="gallery-card-zoom"><i class="fas fa-expand"></i></span>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Filtered View (hidden by default, shown when a category tab is clicked) -->
            <div class="gallery-filtered-view" id="galleryFilteredView" style="display: none;">
                <div class="gallery-masonry">
                    <?php foreach ($items as $gi => $gitem): ?>
                    <div class="gallery-item" data-category="<?php echo htmlspecialchars($gitem['category'] ?? 'other'); ?>" data-sub-category="<?php echo htmlspecialchars(trim(preg_replace('/\s*-\s*\d+$/', '', $gitem['title'] ?? ''))); ?>">
                        <div class="gallery-card" data-lightbox="<?php echo htmlspecialchars(!empty($gitem['image']) ? (strpos($gitem['image'], 'http') === 0 ? $gitem['image'] : SITE_URL . '/' . $gitem['image']) : ''); ?>" data-title="<?php echo htmlspecialchars($gitem['title'] ?? ''); ?>" data-category="<?php echo ucfirst(htmlspecialchars($gitem['category'] ?? 'other')); ?>">
                            <img src="<?php echo htmlspecialchars(!empty($gitem['thumbnail']) ? (strpos($gitem['thumbnail'], 'http') === 0 ? $gitem['thumbnail'] : SITE_URL . '/' . $gitem['thumbnail']) : (!empty($gitem['image']) ? (strpos($gitem['image'], 'http') === 0 ? $gitem['image'] : SITE_URL . '/' . $gitem['image']) : '')); ?>" alt="<?php echo htmlspecialchars(!empty($gitem['image_alt_text']) ? $gitem['image_alt_text'] : ($gitem['title'] ?? 'Gallery image')); ?>" loading="lazy" class="gallery-card-img">
                            <div class="gallery-card-overlay">
                                <span class="gallery-card-category"><?php echo ucfirst(htmlspecialchars($gitem['category'] ?? 'other')); ?></span>
                                <h3 class="gallery-card-title"><?php echo htmlspecialchars($gitem['title'] ?? ''); ?></h3>
                                <span class="gallery-card-zoom"><i class="fas fa-expand"></i></span>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Lightbox Modal -->
    <div class="gallery-lightbox" id="galleryLightbox">
        <div class="gallery-lightbox-backdrop"></div>
        <button class="gallery-lightbox-close" aria-label="Close lightbox"><i class="fas fa-times"></i></button>
        <button class="gallery-lightbox-prev" aria-label="Previous image"><i class="fas fa-chevron-left"></i></button>
        <button class="gallery-lightbox-next" aria-label="Next image"><i class="fas fa-chevron-right"></i></button>
        <div class="gallery-lightbox-content">
            <img src="" alt="" class="gallery-lightbox-img" id="lightboxImg">
            <div class="gallery-lightbox-info">
                <span class="gallery-lightbox-category" id="lightboxCategory"></span>
                <h3 class="gallery-lightbox-title" id="lightboxTitle"></h3>
            </div>
        </div>
        <div class="gallery-lightbox-counter">
            <span id="lightboxCurrent">1</span> / <span id="lightboxTotal">1</span>
        </div>
    </div>

<?php include 'includes/footer.php'; ?>
