<?php
/* Template Name: University Page */

get_header();

// Fetch Page Metadata
$page_id = get_the_ID();

/**
 * Helper to convert standard watch URLs of YouTube/Vimeo into proper embed URLs
 */
function get_university_embed_url($url) {
    if (empty($url)) return '';
    
    // YouTube
    if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match)) {
        return 'https://www.youtube.com/embed/' . $match[1] . '?autoplay=1';
    }
    
    // Vimeo
    if (preg_match('%vimeo\.com/(?:channels/(?:\w+/)?|groups/([^/]*)/videos/|album/(\d+)/video/|video/|)(\d+)(?:$|/|\?)%i', $url, $match)) {
        return 'https://player.vimeo.com/video/' . $match[3] . '?autoplay=1';
    }
    
    return esc_url_raw($url);
}

/**
 * Helper to fetch YouTube video thumbnail if no custom cover image is uploaded
 */
function get_university_video_thumbnail($video_url, $custom_image = '') {
    if (!empty($custom_image)) {
        return $custom_image;
    }
    if (empty($video_url)) {
        return '';
    }
    if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $video_url, $match)) {
        return 'https://img.youtube.com/vi/' . $match[1] . '/hqdefault.jpg';
    }
    return '';
}
$hero_title       = get_post_meta($page_id, 'hero_title', true) ?: get_the_title();
$hero_desc        = get_post_meta($page_id, 'hero_description', true);
$hero_bg_image    = get_post_meta($page_id, 'hero_bg_image', true);
$hero_bg_color    = get_post_meta($page_id, 'hero_bg_color', true) ?: '#8A1529';

// 2. Intro Metadata
$intro_bg_color   = get_post_meta($page_id, 'intro_bg_color', true) ?: '#ffffff';
$intro_title      = get_post_meta($page_id, 'intro_title', true);
$intro_subtitle   = get_post_meta($page_id, 'intro_subtitle', true);
$intro_desc       = get_post_meta($page_id, 'intro_description', true);
$intro_image      = get_post_meta($page_id, 'intro_image', true);
$intro_cta_text   = get_post_meta($page_id, 'intro_cta_text', true);
$intro_cta_link   = get_post_meta($page_id, 'intro_cta_link', true);
$intro_cta_target = get_post_meta($page_id, 'intro_cta_target', true) ?: '_self';

// 3. Stats Metadata
$stats_bg_color   = get_post_meta($page_id, 'stats_bg_color', true) ?: '#0F2042';
$selected_stats   = get_post_meta($page_id, 'univ_selected_stats', true) ?: [];
$stats_limit      = get_post_meta($page_id, 'stats_limit', true) ?: 4;

// 4. Alumni Benefits Metadata
$benefits_bg_color = get_post_meta($page_id, 'benefits_bg_color', true) ?: '#ffffff';
$benefits_title    = get_post_meta($page_id, 'benefits_title', true) ?: 'Alumni benefits and services';
$benefits_desc     = get_post_meta($page_id, 'benefits_desc', true);
$benefits          = get_post_meta($page_id, 'univ_benefits', true) ?: [];

// 5. Band Metadata
$band_bg_color     = get_post_meta($page_id, 'band_bg_color', true) ?: '#FFCC00';
$selected_band_id  = get_post_meta($page_id, 'selected_band_id', true);

// 6. Success Stories Metadata
$snapshot_bg_color    = get_post_meta($page_id, 'snapshot_bg_color', true) ?: '#ffffff';
$snapshot_limit       = get_post_meta($page_id, 'snapshot_limit', true) ?: 3;
$snapshot_type        = get_post_meta($page_id, 'snapshot_type', true) ?: 'image';
$snapshot_video_thumb = get_post_meta($page_id, 'snapshot_video_thumb', true);

$voice_bg_color       = get_post_meta($page_id, 'voice_bg_color', true) ?: '#f9f9f9';
$voice_limit          = get_post_meta($page_id, 'voice_limit', true) ?: 3;
$voice_type           = get_post_meta($page_id, 'voice_type', true) ?: 'video';
$voice_play_icon      = get_post_meta($page_id, 'voice_play_icon', true);
$voice_video_thumb    = get_post_meta($page_id, 'voice_video_thumb', true);

// 7. Mid Section Metadata
$mid_bg_color      = get_post_meta($page_id, 'mid_bg_color', true) ?: '#ffffff';
$mid_image         = get_post_meta($page_id, 'mid_image', true);
$mid_title         = get_post_meta($page_id, 'mid_title', true);
$mid_description   = get_post_meta($page_id, 'mid_description', true);
$mid_cta_text      = get_post_meta($page_id, 'mid_cta_text', true);
$mid_cta_link      = get_post_meta($page_id, 'mid_cta_link', true);
$mid_cta_target    = get_post_meta($page_id, 'mid_cta_target', true) ?: '_self';
$mid_accordion     = get_post_meta($page_id, 'univ_mid_accordion', true) ?: [];

// 8. Latest News Metadata
$news_bg_color     = get_post_meta($page_id, 'news_bg_color', true) ?: '#ffffff';
$show_latest_news  = get_post_meta($page_id, 'show_latest_news', true) ?: 'no';
$news_title        = get_post_meta($page_id, 'news_title', true) ?: 'Latest News';
$news_limit        = get_post_meta($page_id, 'news_limit', true) ?: 4;

// 9. Career Metadata
$career_bg_color   = get_post_meta($page_id, 'career_bg_color', true) ?: '#ffffff';
$career_title      = get_post_meta($page_id, 'career_title', true);
$career_limit      = get_post_meta($page_id, 'career_limit', true) ?: 3;
?>

<div class="univ-body">

    <!-- 1. HERO SECTION -->
    <section class="univ-hero" style="<?php echo $hero_bg_image ? 'background-image: url(' . esc_url($hero_bg_image) . ');' : 'background-color: ' . esc_attr($hero_bg_color) . ';'; ?>">
        <div class="univ-container">
            <div class="univ-hero-content">
                <h1><?php echo esc_html($hero_title); ?></h1>
                <?php if ($hero_desc) { ?>
                    <p><?php echo esc_html($hero_desc); ?></p>
                <?php } ?>
            </div>
        </div>
    </section>

    <!-- 2. INTRO SECTION -->
    <section class="univ-section" style="background-color: <?php echo esc_attr($intro_bg_color); ?>;">
        <div class="univ-container">
            <!-- Breadcrumbs -->
            <div class="univ-breadcrumb">
                <a href="<?php echo esc_url(home_url('/')); ?>">
                    <svg viewBox="0 0 24 24"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
                    Home
                </a>
                <span class="sep">&gt;</span>
                <span class="current"><?php echo esc_html(get_the_title()); ?></span>
            </div>

            <div class="univ-intro-grid">
                <div class="univ-intro-image">
                    <?php if ($intro_image) { ?>
                        <img src="<?php echo esc_url($intro_image); ?>" alt="<?php echo esc_attr($intro_title); ?>">
                    <?php } else { ?>
                        <div style="background: #eee; height: 350px; border-radius: 4px; display:flex; align-items:center; justify-content:center; color:#999;">Intro Image Placeholder</div>
                    <?php } ?>
                </div>
                <div class="univ-intro-text">
                    <?php if ($intro_title) { ?>
                        <h2><?php echo esc_html($intro_title); ?></h2>
                    <?php } ?>
                    <?php if ($intro_subtitle) { ?>
                        <span class="subtitle"><?php echo esc_html($intro_subtitle); ?></span>
                    <?php } ?>
                    <?php if ($intro_desc) { ?>
                        <div class="desc"><?php echo wp_kses_post($intro_desc); ?></div>
                    <?php } ?>
                    <?php if ($intro_cta_text && $intro_cta_link) { ?>
                        <a href="<?php echo esc_url($intro_cta_link); ?>" class="univ-btn univ-btn-primary" target="<?php echo esc_attr($intro_cta_target); ?>">
                            <?php echo esc_html($intro_cta_text); ?>
                        </a>
                    <?php } ?>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. STATS SECTION (Statistics CPT) -->
    <?php
    if (!empty($selected_stats)) {
        $stats_query = new WP_Query([
            'post_type'      => 'statistics',
            'post__in'       => $selected_stats,
            'orderby'        => 'post__in',
            'posts_per_page' => $stats_limit
        ]);
        if ($stats_query->have_posts()) { ?>
            <section class="univ-section" style="background-color: <?php echo esc_attr($stats_bg_color); ?>;">
                <div class="univ-container">
                    <div class="univ-stats-grid">
                        <?php while ($stats_query->have_posts()) {
                            $stats_query->the_post();
                            $stat_value = get_post_meta(get_the_ID(), 'stat_value', true) ?: '0';
                            ?>
                            <div class="univ-stat-card">
                                <h3><?php echo esc_html($stat_value); ?></h3>
                                <p><?php the_title(); ?></p>
                            </div>
                        <?php } wp_reset_postdata(); ?>
                    </div>
                </div>
            </section>
        <?php }
    } ?>

    <!-- 4. ALUMNI BENEFITS AND SERVICES -->
    <?php if (!empty($benefits) || !empty($benefits_title)) { ?>
        <section class="univ-section" style="background-color: <?php echo esc_attr($benefits_bg_color); ?>;">
            <div class="univ-container">
                <div class="univ-section-title">
                    <h2><?php echo esc_html($benefits_title); ?></h2>
                    <?php if ($benefits_desc) { ?>
                        <p><?php echo wp_kses_post($benefits_desc); ?></p>
                    <?php } ?>
                </div>
                <div class="univ-benefits-grid">
                    <?php foreach ($benefits as $b) { ?>
                        <div class="univ-benefit-card">
                            <div class="univ-benefit-icon">
                                <?php if ($b['icon']) { ?>
                                    <img src="<?php echo esc_url($b['icon']); ?>" alt="<?php echo esc_attr($b['title']); ?>">
                                <?php } else { ?>
                                    <svg width="24" height="24" viewBox="0 0 24 24" style="fill: #2c3e50;"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 17h-2v-2h2v2zm2.07-7.75l-.9.92C13.45 12.9 13 13.5 13 15h-2v-.5c0-1.1.45-2.1 1.17-2.83l1.24-1.26c.37-.36.59-.86.59-1.41 0-1.1-.9-2-2-2s-2 .9-2 2H7c0-2.76 2.24-5 5-5s5 2.24 5 5c0 1.04-.42 1.99-1.07 2.75z"/></svg>
                                <?php } ?>
                            </div>
                            <h3><?php echo esc_html($b['title']); ?></h3>
                            <p><?php echo wp_kses_post($b['description']); ?></p>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </section>
    <?php } ?>

    <!-- 5. BAND NAME SECTION (CPT Band - Single selection) -->
    <?php
    if ($selected_band_id) {
        $band_post = get_post($selected_band_id);
        if ($band_post && $band_post->post_type === 'band') {
            $band_icon       = get_post_meta($selected_band_id, 'band_icon', true);
            $band_desc       = get_post_meta($selected_band_id, 'band_description', true);
            $band_cta_text   = get_post_meta($selected_band_id, 'band_cta_text', true);
            $band_cta_link   = get_post_meta($selected_band_id, 'band_cta_link', true);
            $band_cta_target = get_post_meta($selected_band_id, 'band_cta_target', true) ?: '_self';
            ?>
            <section class="univ-band-banner" style="background-color: <?php echo esc_attr($band_bg_color); ?>;">
                <div class="univ-container">
                    <div class="univ-band-flex">
                        <div class="univ-band-left">
                            <div class="univ-band-icon">
                                <?php if ($band_icon) { ?>
                                    <img src="<?php echo esc_url($band_icon); ?>" alt="<?php echo esc_attr($band_post->post_title); ?>">
                                <?php } else { ?>
                                    <svg width="24" height="24" viewBox="0 0 24 24" style="fill: #2c3e50;"><path d="M20 18c1.1 0 1.99-.9 1.99-2L22 6c0-1.1-.9-2-2-2H4c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2H0v2h24v-2h-4zM4 6h16v10H4V6z"/></svg>
                                <?php } ?>
                            </div>
                            <div class="univ-band-info">
                                <h3><?php echo esc_html($band_post->post_title); ?></h3>
                                <?php if ($band_desc) { ?>
                                    <p><?php echo wp_kses_post($band_desc); ?></p>
                                <?php } ?>
                            </div>
                        </div>
                        <?php if ($band_cta_text && $band_cta_link) { ?>
                            <div class="univ-band-cta">
                                <a href="<?php echo esc_url($band_cta_link); ?>" class="univ-btn univ-btn-secondary" target="<?php echo esc_attr($band_cta_target); ?>">
                                    <?php echo esc_html($band_cta_text); ?>
                                    <svg viewBox="0 0 24 24"><path d="M5 4v2h11.59L3 21.59 4.41 23 18 9.41V20h2V4z"/></svg>
                                </a>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </section>
            <?php
        }
    }
    ?>

    <!-- 6. SUCCESS STORIES SECTIONS -->
    
    <!-- A. SUCCESS SNAPSHOTS (Image success stories) -->
    <?php
    // Meta query based on selection
    if ($snapshot_type === 'video') {
        $snapshot_meta_query = [
            [
                'key'     => 'story_video_url',
                'value'   => '',
                'compare' => '!='
            ]
        ];
    } else {
        $snapshot_meta_query = [
            'relation' => 'OR',
            [
                'key'     => 'story_video_url',
                'compare' => 'NOT EXISTS'
            ],
            [
                'key'     => 'story_video_url',
                'value'   => '',
                'compare' => '='
            ]
        ];
    }

    $snapshot_query = new WP_Query([
        'post_type'      => 'success_story',
        'posts_per_page' => $snapshot_limit,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'meta_query'     => $snapshot_meta_query
    ]);
    if ($snapshot_query->have_posts()) { ?>
        <section class="univ-section" style="background-color: <?php echo esc_attr($snapshot_bg_color); ?>;">
            <div class="univ-container">
                <div class="univ-section-title" style="text-align: left;">
                    <h2>Success snapshots</h2>
                </div>
                <div class="univ-stories-grid">
                    <?php while ($snapshot_query->have_posts()) {
                        $snapshot_query->the_post();
                        $subtitle = get_post_meta(get_the_ID(), 'story_subtitle', true);
                        $desc = get_post_meta(get_the_ID(), 'story_description', true);
                        $image = get_post_meta(get_the_ID(), 'story_image', true);
                        $video_url = get_post_meta(get_the_ID(), 'story_video_url', true);
                        $thumbnail = $image ?: $snapshot_video_thumb ?: get_university_video_thumbnail($video_url);
                        ?>
                        <div class="univ-story-card">
                            <?php if ($video_url) { ?>
                                <div class="univ-video-trigger" data-video-url="<?php echo get_university_embed_url($video_url); ?>">
                                    <?php if ($thumbnail) { ?>
                                        <img src="<?php echo esc_url($thumbnail); ?>" alt="<?php the_title(); ?>">
                                    <?php } else { ?>
                                        <div class="univ-story-card-placeholder">Success Story</div>
                                    <?php } ?>
                                    <div class="univ-play-overlay">
                                        <svg class="univ-play-icon" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                    </div>
                                </div>
                            <?php } else { ?>
                                <div class="univ-story-image">
                                    <?php if ($image) { ?>
                                        <img src="<?php echo esc_url($image); ?>" alt="<?php the_title(); ?>">
                                    <?php } else { ?>
                                        <div style="background: #eee; height: 100%; display:flex; align-items:center; justify-content:center; color:#999;">No Image Available</div>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                            <div class="univ-story-info">
                                <h3><?php the_title(); ?></h3>
                                <?php if ($subtitle) { ?>
                                    <span class="subtitle"><?php echo esc_html($subtitle); ?></span>
                                <?php } ?>
                                <?php if ($desc) { ?>
                                    <p><?php echo wp_kses_post($desc); ?></p>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } wp_reset_postdata(); ?>
                </div>
            </div>
        </section>
    <?php } ?>

    <!-- B. VOICES OF OUR ALUMNI (Video success stories) -->
    <?php
    // Meta query based on selection
    if ($voice_type === 'image') {
        $voice_meta_query = [
            'relation' => 'OR',
            [
                'key'     => 'story_video_url',
                'compare' => 'NOT EXISTS'
            ],
            [
                'key'     => 'story_video_url',
                'value'   => '',
                'compare' => '='
            ]
        ];
    } else {
        $voice_meta_query = [
            [
                'key'     => 'story_video_url',
                'value'   => '',
                'compare' => '!='
            ]
        ];
    }

    $voice_query = new WP_Query([
        'post_type'      => 'success_story',
        'posts_per_page' => $voice_limit,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'meta_query'     => $voice_meta_query
    ]);
    if ($voice_query->have_posts()) { ?>
        <section class="univ-section" style="background-color: <?php echo esc_attr($voice_bg_color); ?>;">
            <div class="univ-container">
                <div class="univ-section-title" style="text-align: left;">
                    <h2>Voices of our alumni</h2>
                </div>
                <div class="univ-stories-grid">
                    <?php while ($voice_query->have_posts()) {
                        $voice_query->the_post();
                        $subtitle = get_post_meta(get_the_ID(), 'story_subtitle', true);
                        $desc = get_post_meta(get_the_ID(), 'story_description', true);
                        $image = get_post_meta(get_the_ID(), 'story_image', true);
                        $video_url = get_post_meta(get_the_ID(), 'story_video_url', true);
                        $thumbnail = $image ?: $voice_video_thumb ?: get_university_video_thumbnail($video_url);
                        ?>
                        <div class="univ-story-card">
                            <?php if ($video_url) { ?>
                                <div class="univ-video-trigger" data-video-url="<?php echo get_university_embed_url($video_url); ?>">
                                    <?php if ($thumbnail) { ?>
                                        <img src="<?php echo esc_url($thumbnail); ?>" alt="<?php the_title(); ?>">
                                    <?php } else { ?>
                                        <div class="univ-story-card-placeholder">Success Story</div>
                                    <?php } ?>
                                    <div class="univ-play-overlay">
                                        <?php if (!empty($voice_play_icon)) { ?>
                                            <img src="<?php echo esc_url($voice_play_icon); ?>" class="univ-play-icon-custom" alt="Play">
                                        <?php } else { ?>
                                            <svg class="univ-play-icon" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                        <?php } ?>
                                    </div>
                                </div>
                            <?php } else { ?>
                                <div class="univ-story-image">
                                    <?php if ($image) { ?>
                                        <img src="<?php echo esc_url($image); ?>" alt="<?php the_title(); ?>">
                                    <?php } else { ?>
                                        <div style="background: #eee; height: 100%; display:flex; align-items:center; justify-content:center; color:#999;">No Image Available</div>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                            <div class="univ-story-info">
                                <h3><?php the_title(); ?></h3>
                                <?php if ($subtitle) { ?>
                                    <span class="subtitle"><?php echo esc_html($subtitle); ?></span>
                                <?php } ?>
                                <?php if ($desc) { ?>
                                    <p><?php echo wp_kses_post($desc); ?></p>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } wp_reset_postdata(); ?>
                </div>
            </div>
        </section>
    <?php } ?>

    <!-- 7. MID SECTION (Accordon & CTA) -->
    <section class="univ-section" style="background-color: <?php echo esc_attr($mid_bg_color); ?>;">
        <div class="univ-container">
            <div class="univ-mid-grid">
                <div class="univ-mid-image">
                    <?php if ($mid_image) { ?>
                        <img src="<?php echo esc_url($mid_image); ?>" alt="<?php echo esc_attr($mid_title); ?>">
                    <?php } else { ?>
                        <div style="background: #eee; height: 400px; display:flex; align-items:center; justify-content:center; color:#999;">Mid Image Placeholder</div>
                    <?php } ?>
                </div>
                <div class="univ-mid-content">
                    <?php if ($mid_title) { ?>
                        <h2><?php echo esc_html($mid_title); ?></h2>
                    <?php } ?>
                    <?php if ($mid_description) { ?>
                        <div class="univ-mid-desc">
                            <?php echo wp_kses_post($mid_description); ?>
                        </div>
                    <?php } ?>
 
                    <!-- Static list of items (Fully expanded, no toggle buttons) -->
                    <?php if (!empty($mid_accordion)) { ?>
                        <div class="univ-static-list">
                            <?php foreach ($mid_accordion as $item) { ?>
                                <div class="univ-static-item">
                                    <h4><?php echo esc_html($item['title']); ?></h4>
                                    <p><?php echo wp_kses_post($item['description']); ?></p>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } ?>

                    <?php if ($mid_cta_text && $mid_cta_link) { ?>
                        <a href="<?php echo esc_url($mid_cta_link); ?>" class="univ-btn univ-btn-accent" target="<?php echo esc_attr($mid_cta_target); ?>">
                            <?php echo esc_html($mid_cta_text); ?>
                        </a>
                    <?php } ?>
                </div>
            </div>
        </div>
    </section>

    <!-- 8. LATEST NEWS SECTION -->
    <?php
    if ($show_latest_news === 'yes') {
        $news_query = new WP_Query([
            'post_type'      => 'latest_news',
            'posts_per_page' => $news_limit,
            'orderby'        => 'date',
            'order'          => 'DESC'
        ]);
        if ($news_query->have_posts()) { ?>
            <section class="univ-section" style="background-color: <?php echo esc_attr($news_bg_color); ?>;">
                <div class="univ-container">
                    <div class="univ-section-title" style="text-align: left;">
                        <h2><?php echo esc_html($news_title); ?></h2>
                    </div>
                    <div class="univ-news-grid">
                        <?php 
                        $counter = 0;
                        $other_news = [];
                        
                        while ($news_query->have_posts()) {
                            $news_query->the_post();
                            if ($counter === 0) { 
                                // First post (Large featured format with image)
                                $feat_img = get_the_post_thumbnail_url(get_the_ID(), 'large');
                                ?>
                                <div class="univ-news-featured">
                                    <?php if ($feat_img) { ?>
                                        <div class="univ-news-featured-img">
                                            <a href="<?php the_permalink(); ?>">
                                                <img src="<?php echo esc_url($feat_img); ?>" alt="<?php the_title(); ?>">
                                            </a>
                                        </div>
                                    <?php } ?>
                                    <div class="univ-news-featured-content">
                                        <span class="univ-news-date"><?php echo get_the_date('M d, Y'); ?></span>
                                        <h3><a href="<?php the_permalink(); ?>" style="color: inherit; text-decoration: none;"><?php the_title(); ?></a></h3>
                                        <p><?php echo wp_trim_words(get_the_excerpt(), 25); ?></p>
                                    </div>
                                </div>
                            <?php } else {
                                // Other news items (No image list format)
                                $other_news[] = [
                                    'title' => get_the_title(),
                                    'link'  => get_permalink(),
                                    'date'  => get_the_date('M d, Y'),
                                    'excerpt' => wp_trim_words(get_the_excerpt(), 18)
                                ];
                            }
                            $counter++;
                        } wp_reset_postdata(); 
                        ?>

                        <div class="univ-news-list">
                            <?php foreach ($other_news as $item) { ?>
                                <a href="<?php echo esc_url($item['link']); ?>" class="univ-news-item">
                                    <span class="univ-news-date"><?php echo esc_html($item['date']); ?></span>
                                    <h3><?php echo esc_html($item['title']); ?></h3>
                                    <p><?php echo esc_html($item['excerpt']); ?></p>
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </section>
        <?php }
    }
    ?>

    <!-- 9. CAREER SECTION (Career CPT) -->
    <?php
    $career_query = new WP_Query([
        'post_type'      => 'career',
        'posts_per_page' => $career_limit,
        'orderby'        => 'date',
        'order'          => 'DESC'
    ]);
    if ($career_query->have_posts()) { ?>
        <section class="univ-section" style="background-color: <?php echo esc_attr($career_bg_color); ?>;">
            <div class="univ-container">
                <?php if (!empty($career_title)) { ?>
                    <div class="univ-section-title" style="text-align: left; margin-bottom: 50px;">
                        <h2><?php echo esc_html($career_title); ?></h2>
                    </div>
                <?php } ?>
                <div class="univ-career-grid">
                    <?php while ($career_query->have_posts()) {
                        $career_query->the_post();
                        $apply_link = get_post_meta(get_the_ID(), 'career_apply_link', true) ?: get_permalink();
                        ?>
                        <div class="univ-career-item-col">
                            <div class="univ-career-header-wrapper">
                                <a href="<?php echo esc_url($apply_link); ?>" class="univ-career-link">
                                    <?php the_title(); ?>
                                </a>
                            </div>
                            <div class="univ-career-desc">
                                <?php the_content(); ?>
                            </div>
                        </div>
                    <?php } wp_reset_postdata(); ?>
                </div>
            </div>
        </section>
    <?php } ?>

</div>

<!-- Video Popup Modal -->
<div id="univ-video-modal" class="univ-modal">
    <div class="univ-modal-overlay"></div>
    <div class="univ-modal-container">
        <button class="univ-modal-close" aria-label="Close video modal">&times;</button>
        <div class="univ-modal-content">
            <iframe id="univ-modal-iframe" src="" allowfullscreen allow="autoplay"></iframe>
        </div>
    </div>
</div>



<?php
get_footer();