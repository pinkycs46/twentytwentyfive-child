<?php
/**
 * Twenty Twenty-Five Child Theme Functions
 */

// Enqueue Parent Style & Custom JS
function tt5_child_enqueue_styles() {
    wp_enqueue_style(
        'parent-style',
        get_template_directory_uri() . '/style.css'
    );
    wp_enqueue_style(
        'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array('parent-style')
    );
    wp_enqueue_script(
        'univ-custom-js',
        get_stylesheet_directory_uri() . '/custom.js',
        array(),
        '1.0.0',
        true
    );
}
add_action('wp_enqueue_scripts', 'tt5_child_enqueue_styles');

function create_university_cpts() {
    // Statistics CPT
    register_post_type('statistics', [
        'label' => 'Statistics',
        'public' => true,
        'show_in_menu' => true,
        'supports' => ['title'],
        'menu_icon' => 'dashicons-chart-bar'
    ]);

    // Band CPT
    register_post_type('band', [
        'label' => 'Bands',
        'public' => true,
        'show_in_menu' => true,
        'supports' => ['title'],
        'menu_icon' => 'dashicons-flag'
    ]);

    // Success Story CPT
    register_post_type('success_story', [
        'label' => 'Success Stories',
        'public' => true,
        'show_in_menu' => true,
        'supports' => ['title'],
        'menu_icon' => 'dashicons-testimonial'
    ]);

    // Latest News CPT
    register_post_type('latest_news', [
        'label' => 'Latest News',
        'public' => true,
        'show_in_menu' => true,
        'supports' => ['title', 'editor', 'thumbnail'],
        'menu_icon' => 'dashicons-format-aside'
    ]);

    // Career CPT
    register_post_type('career', [
        'label' => 'Careers',
        'public' => true,
        'show_in_menu' => true,
        'supports' => ['title', 'editor'],
        'menu_icon' => 'dashicons-businessman'
    ]);
}
add_action('init', 'create_university_cpts');

/**
 * 2. Enqueue Media & Color Picker for Admin Panel
 */
function university_admin_assets($hook) {
    global $post;
    if ($hook == 'post.php' || $hook == 'post-new.php') {
        wp_enqueue_media();
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');
        wp_enqueue_script('jquery-ui-sortable');
    }
}
add_action('admin_enqueue_scripts', 'university_admin_assets');

/**
 * 3. Register Page Template Metaboxes
 */
function register_university_page_metaboxes() {
    add_meta_box('univ_hero', 'University Page: 1. Hero Section', 'univ_hero_callback', 'page', 'normal', 'high');
    add_meta_box('univ_intro', 'University Page: 2. Intro Section', 'univ_intro_callback', 'page', 'normal', 'high');
    add_meta_box('univ_stats', 'University Page: 3. Stats Section Config', 'univ_stats_callback', 'page', 'normal', 'high');
    add_meta_box('univ_benefits', 'University Page: 4. Alumni Benefits (Repeatable)', 'univ_benefits_callback', 'page', 'normal', 'high');
    add_meta_box('univ_band', 'University Page: 5. Band Section Config', 'univ_band_callback', 'page', 'normal', 'high');
    add_meta_box('univ_success', 'University Page: 6. Success Stories Config', 'univ_success_callback', 'page', 'normal', 'high');
    add_meta_box('univ_mid', 'University Page: 7. Mid Section (with Accordion)', 'univ_mid_callback', 'page', 'normal', 'high');
    add_meta_box('univ_news', 'University Page: 8. Latest News Config', 'univ_news_callback', 'page', 'normal', 'high');
    add_meta_box('univ_career', 'University Page: 9. Career Section Config', 'univ_career_callback', 'page', 'normal', 'high');

    // CPT Metaboxes
    add_meta_box('stat_value_box', 'Statistic Value', 'stat_value_callback', 'statistics', 'normal', 'high');
    add_meta_box('band_meta_box', 'Band Details', 'band_meta_callback', 'band', 'normal', 'high');
    add_meta_box('story_meta_box', 'Success Story Details', 'story_meta_callback', 'success_story', 'normal', 'high');
    add_meta_box('career_meta_box', 'Career Details', 'career_meta_callback', 'career', 'normal', 'high');
}
add_action('add_meta_boxes', 'register_university_page_metaboxes');

/**
 * 4. Metabox CALLBACKS
 */

// Helper to render background color field
function render_bg_color_field($meta_key, $val) {
    ?>
    <p>
        <label class="univ-meta-label">Section Background Color</label>
        <input type="text" name="<?php echo esc_attr($meta_key); ?>" value="<?php echo esc_attr($val ?: '#ffffff'); ?>" class="wp-color-picker-field">
    </p>
    <?php
}

// 1. Hero Callback
function univ_hero_callback($post) {
    wp_nonce_field('univ_nonce_action', 'univ_nonce');
    $title = get_post_meta($post->ID, 'hero_title', true);
    $bg_image = get_post_meta($post->ID, 'hero_bg_image', true);
    $desc = get_post_meta($post->ID, 'hero_description', true);
    $bg_color = get_post_meta($post->ID, 'hero_bg_color', true);

    render_bg_color_field('hero_bg_color', $bg_color);
    ?>
    <div class="univ-meta-row">
        <label class="univ-meta-label">Hero Title</label>
        <input type="text" name="hero_title" value="<?php echo esc_attr($title); ?>" style="width:100%;">
    </div>
    <div class="univ-meta-row">
        <label class="univ-meta-label">Hero Description</label>
        <textarea name="hero_description" style="width:100%; height:80px;"><?php echo esc_textarea($desc); ?></textarea>
    </div>
    <div class="univ-meta-row">
        <label class="univ-meta-label">Background Image</label>
        <input type="text" name="hero_bg_image" value="<?php echo esc_url($bg_image); ?>" class="media-url-input" style="width:70%;" readonly>
        <button type="button" class="button select-media-btn">Select Image</button>
        <button type="button" class="button clear-media-btn">Clear</button>
        <div class="media-preview-wrapper" style="margin-top:10px;">
            <img src="<?php echo esc_url($bg_image); ?>" style="max-width:300px; display:<?php echo $bg_image ? 'block' : 'none'; ?>;">
        </div>
    </div>
    <?php
}

// 2. Intro Callback
function univ_intro_callback($post) {
    $bg_color = get_post_meta($post->ID, 'intro_bg_color', true);
    $title = get_post_meta($post->ID, 'intro_title', true);
    $subtitle = get_post_meta($post->ID, 'intro_subtitle', true);
    $desc = get_post_meta($post->ID, 'intro_description', true);
    $image = get_post_meta($post->ID, 'intro_image', true);
    $cta_text = get_post_meta($post->ID, 'intro_cta_text', true);
    $cta_link = get_post_meta($post->ID, 'intro_cta_link', true);
    $cta_target = get_post_meta($post->ID, 'intro_cta_target', true);

    render_bg_color_field('intro_bg_color', $bg_color);
    ?>
    <div class="univ-meta-row">
        <label class="univ-meta-label">Intro Title</label>
        <input type="text" name="intro_title" value="<?php echo esc_attr($title); ?>" style="width:100%;">
    </div>
    <div class="univ-meta-row">
        <label class="univ-meta-label">Subtitle</label>
        <input type="text" name="intro_subtitle" value="<?php echo esc_attr($subtitle); ?>" style="width:100%;">
    </div>
    <div class="univ-meta-row">
        <label class="univ-meta-label">Description</label>
        <textarea name="intro_description" style="width:100%; height:100px;"><?php echo esc_textarea($desc); ?></textarea>
    </div>
    <div class="univ-meta-row">
        <label class="univ-meta-label">Intro Image</label>
        <input type="text" name="intro_image" value="<?php echo esc_url($image); ?>" class="media-url-input" style="width:70%;" readonly>
        <button type="button" class="button select-media-btn">Select Image</button>
        <button type="button" class="button clear-media-btn">Clear</button>
        <div class="media-preview-wrapper" style="margin-top:10px;">
            <img src="<?php echo esc_url($image); ?>" style="max-width:200px; display:<?php echo $image ? 'block' : 'none'; ?>;">
        </div>
    </div>
    <div class="univ-meta-row">
        <label class="univ-meta-label">CTA Button Text</label>
        <input type="text" name="intro_cta_text" value="<?php echo esc_attr($cta_text); ?>" style="width:100%;">
    </div>
    <div class="univ-meta-row">
        <label class="univ-meta-label">CTA Link URL</label>
        <input type="text" name="intro_cta_link" value="<?php echo esc_attr($cta_link); ?>" style="width:100%;">
    </div>
    <div class="univ-meta-row">
        <label class="univ-meta-label">CTA Link Target</label>
        <select name="intro_cta_target">
            <option value="_self" <?php selected($cta_target, '_self'); ?>>Open in Same Tab</option>
            <option value="_blank" <?php selected($cta_target, '_blank'); ?>>Open in New Tab</option>
        </select>
    </div>
    <?php
}

// 3. Stats Callback
function univ_stats_callback($post) {
    $bg_color = get_post_meta($post->ID, 'stats_bg_color', true);
    $selected_stats = get_post_meta($post->ID, 'univ_selected_stats', true);
    if (!is_array($selected_stats)) $selected_stats = [];
    $limit = get_post_meta($post->ID, 'stats_limit', true) ?: '4';

    render_bg_color_field('stats_bg_color', $bg_color);

    // Fetch all statistics posts
    $all_stats = get_posts([
        'post_type' => 'statistics',
        'numberposts' => -1,
        'orderby' => 'title',
        'order' => 'ASC'
    ]);
    ?>
    <div class="univ-stats-metabox-wrapper" style="display: flex; gap: 30px; margin-top: 15px;">
        <div class="univ-stats-selector-panel" style="flex: 1;">
            <label class="univ-meta-label">Select Statistic to Add</label>
            <select id="univ-stats-select" style="width: 100%;">
                <option value="">-- Choose a Statistic --</option>
                <?php foreach ($all_stats as $stat) { ?>
                    <option value="<?php echo $stat->ID; ?>"><?php echo esc_html($stat->post_title); ?></option>
                <?php } ?>
            </select>
            <p class="description" style="margin-top: 10px;">Select a statistic from the dropdown to add it. Reorder them by dragging them up and down on the right panel.</p>
            <div style="margin-top: 20px;">
                <label class="univ-meta-label">Limit Statistics to Display</label>
                <input type="number" name="stats_limit" value="<?php echo esc_attr($limit); ?>" min="1" max="20" style="width:100px;">
            </div>
        </div>
        
        <div class="univ-stats-ordered-panel" style="flex: 1.2; background: #fafafa; border: 1px solid #ddd; padding: 15px; border-radius: 4px; min-height: 120px;">
            <h4 style="margin-top: 0; border-bottom: 1px solid #eee; padding-bottom: 8px; color: #23282d; font-size: 13px;">Selected Statistics (Drag to Reorder)</h4>
            <ul id="univ-stats-selected-list" style="margin: 0; padding: 0; list-style: none; min-height: 40px;">
                <?php foreach ($selected_stats as $stat_id) {
                    $stat_post = get_post($stat_id);
                    if ($stat_post && $stat_post->post_type === 'statistics') {
                        ?>
                        <li class="univ-stat-sortable-item" style="border: 1px solid #ccc; background: #white; padding: 8px 12px; margin-bottom: 8px; display: flex; align-items: center; justify-content: space-between; border-radius: 3px; cursor: move; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                            <span style="display: flex; align-items: center; gap: 8px;">
                                <span class="dashicons dashicons-menu drag-handle" style="color: #999; cursor: move;"></span>
                                <strong><?php echo esc_html($stat_post->post_title); ?></strong>
                            </span>
                            <button type="button" class="remove-stat-item" style="background: none; border: none; color: #d63638; cursor: pointer; font-weight: bold; font-size: 14px;">X</button>
                            <input type="hidden" name="univ_selected_stats[]" value="<?php echo $stat_id; ?>">
                        </li>
                        <?php
                    }
                } ?>
            </ul>
        </div>
    </div>
    <?php
}

// 4. Alumni Benefits Callback
function univ_benefits_callback($post) {
    $bg_color = get_post_meta($post->ID, 'benefits_bg_color', true) ?: '#ffffff';
    $title    = get_post_meta($post->ID, 'benefits_title', true) ?: 'Alumni benefits and services';
    $desc     = get_post_meta($post->ID, 'benefits_desc', true) ?: '';
    $benefits = get_post_meta($post->ID, 'univ_benefits', true);
    if (!is_array($benefits)) $benefits = [];

    render_bg_color_field('benefits_bg_color', $bg_color);
    ?>
    <div class="univ-meta-row">
        <label class="univ-meta-label">Section Title</label>
        <input type="text" name="benefits_title" value="<?php echo esc_attr($title); ?>" style="width:100%;">
    </div>
    <div class="univ-meta-row">
        <label class="univ-meta-label">Section Description</label>
        <textarea name="benefits_desc" style="width:100%; height:80px;"><?php echo esc_textarea($desc); ?></textarea>
    </div>

    <h4>Repeatable Benefits List (No Limits)</h4>
    <div id="benefits-container">
        <?php foreach ($benefits as $index => $benefit) { 
            $icon = $benefit['icon'] ?? '';
            $heading = $benefit['title'] ?? '';
            $item_desc = $benefit['description'] ?? '';
            ?>
            <div class="benefit-row" style="border: 1px solid #ccc; padding: 15px; margin-bottom: 10px; background: #fff; position: relative;">
                <span class="remove-row" style="position: absolute; right: 10px; top: 10px; color: red; cursor: pointer; font-weight: bold;">X</span>
                <p>
                    <label><b>Icon Image</b></label><br>
                    <input type="text" name="univ_benefits[<?php echo $index; ?>][icon]" value="<?php echo esc_url($icon); ?>" class="media-url-input" style="width: 70%;" readonly>
                    <button type="button" class="button select-media-btn">Select Icon</button>
                    <button type="button" class="button clear-media-btn">Clear</button>
                    <div class="media-preview-wrapper" style="margin-top: 5px;">
                        <img src="<?php echo esc_url($icon); ?>" style="max-height: 50px; display: <?php echo $icon ? 'block' : 'none'; ?>;">
                    </div>
                </p>
                <p>
                    <label><b>Heading</b></label><br>
                    <input type="text" name="univ_benefits[<?php echo $index; ?>][title]" value="<?php echo esc_attr($heading); ?>" style="width: 100%;">
                </p>
                <p>
                    <label><b>Description</b></label><br>
                    <textarea name="univ_benefits[<?php echo $index; ?>][description]" style="width: 100%; height: 60px;"><?php echo esc_textarea($item_desc); ?></textarea>
                </p>
            </div>
        <?php } ?>
    </div>
    <p><button type="button" class="button button-secondary" id="add-benefit-row">+ Add Benefit Row</button></p>
    <?php
}

// 5. Band Callback
function univ_band_callback($post) {
    $bg_color = get_post_meta($post->ID, 'band_bg_color', true);
    $selected_band = get_post_meta($post->ID, 'selected_band_id', true);

    render_bg_color_field('band_bg_color', $bg_color);

    // Fetch CPT bands
    $bands = get_posts([
        'post_type' => 'band',
        'numberposts' => -1,
        'orderby' => 'title',
        'order' => 'ASC'
    ]);
    ?>
    <div class="univ-meta-row">
        <label class="univ-meta-label">Select Band to Show</label>
        <select name="selected_band_id" style="width:100%;">
            <option value="">-- Select a Band --</option>
            <?php foreach ($bands as $b) { ?>
                <option value="<?php echo $b->ID; ?>" <?php selected($selected_band, $b->ID); ?>>
                    <?php echo esc_html($b->post_title); ?>
                </option>
            <?php } ?>
        </select>
    </div>
    <?php
}

// 6. Success Stories Callback
function univ_success_callback($post) {
    $snapshot_limit = get_post_meta($post->ID, 'snapshot_limit', true) ?: '3';
    $snapshot_type  = get_post_meta($post->ID, 'snapshot_type', true) ?: 'image';
    $snapshot_bg    = get_post_meta($post->ID, 'snapshot_bg_color', true) ?: '#ffffff';
    $snapshot_video_thumb = get_post_meta($post->ID, 'snapshot_video_thumb', true) ?: '';

    $voice_limit     = get_post_meta($post->ID, 'voice_limit', true) ?: '3';
    $voice_type      = get_post_meta($post->ID, 'voice_type', true) ?: 'video';
    $voice_bg        = get_post_meta($post->ID, 'voice_bg_color', true) ?: '#f9f9f9';
    $voice_play_icon = get_post_meta($post->ID, 'voice_play_icon', true) ?: '';
    $voice_video_thumb = get_post_meta($post->ID, 'voice_video_thumb', true) ?: '';
    ?>
    <div style="border-bottom: 1px solid #ccc; padding-bottom: 20px; margin-bottom: 20px;">
        <h4 style="margin-top:0; font-size:14px; border-bottom: 1px solid #eee; padding-bottom: 8px;">1. Success Snapshot Section</h4>
        <div class="univ-meta-row">
            <label class="univ-meta-label">Background Color</label>
            <input type="text" name="snapshot_bg_color" value="<?php echo esc_attr($snapshot_bg); ?>" class="wp-color-picker-field">
        </div>
        <div class="univ-meta-row" style="margin-top: 15px;">
            <label class="univ-meta-label">Limit Stories to Display</label>
            <input type="number" name="snapshot_limit" value="<?php echo esc_attr($snapshot_limit); ?>" min="1" max="20" style="width:100px;">
        </div>
        <div class="univ-meta-row">
            <label class="univ-meta-label">Story Type to Show</label>
            <select name="snapshot_type" style="width:100%;">
                <option value="image" <?php selected($snapshot_type, 'image'); ?>>Image Stories (No Video URL)</option>
                <option value="video" <?php selected($snapshot_type, 'video'); ?>>Video Stories (Has Video URL)</option>
            </select>
        </div>
        <div class="univ-meta-row" style="margin-top: 15px;">
            <label class="univ-meta-label">Default Video Thumbnail Image</label>
            <input type="text" name="snapshot_video_thumb" value="<?php echo esc_url($snapshot_video_thumb); ?>" class="media-url-input" style="width: 70%;" readonly>
            <button type="button" class="button select-media-btn">Select Image</button>
            <button type="button" class="button clear-media-btn">Clear</button>
            <div class="media-preview-wrapper" style="margin-top: 5px;">
                <img src="<?php echo esc_url($snapshot_video_thumb); ?>" style="max-height: 50px; display: <?php echo $snapshot_video_thumb ? 'block' : 'none'; ?>;">
            </div>
        </div>
    </div>

    <div>
        <h4 style="margin-top:0; font-size:14px; border-bottom: 1px solid #eee; padding-bottom: 8px;">2. Voice of Alumni Section</h4>
        <div class="univ-meta-row">
            <label class="univ-meta-label">Background Color</label>
            <input type="text" name="voice_bg_color" value="<?php echo esc_attr($voice_bg); ?>" class="wp-color-picker-field">
        </div>
        <div class="univ-meta-row" style="margin-top: 15px;">
            <label class="univ-meta-label">Limit Stories to Display</label>
            <input type="number" name="voice_limit" value="<?php echo esc_attr($voice_limit); ?>" min="1" max="20" style="width:100px;">
        </div>
        <div class="univ-meta-row">
            <label class="univ-meta-label">Story Type to Show</label>
            <select name="voice_type" style="width:100%;">
                <option value="image" <?php selected($voice_type, 'image'); ?>>Image Stories (No Video URL)</option>
                <option value="video" <?php selected($voice_type, 'video'); ?>>Video Stories (Has Video URL)</option>
            </select>
        </div>
        <div class="univ-meta-row" style="margin-top: 15px;">
            <label class="univ-meta-label">Custom Video Play Icon (Image/SVG)</label>
            <input type="text" name="voice_play_icon" value="<?php echo esc_url($voice_play_icon); ?>" class="media-url-input" style="width: 70%;" readonly>
            <button type="button" class="button select-media-btn">Select Icon</button>
            <button type="button" class="button clear-media-btn">Clear</button>
            <div class="media-preview-wrapper" style="margin-top: 5px;">
                <img src="<?php echo esc_url($voice_play_icon); ?>" style="max-height: 50px; display: <?php echo $voice_play_icon ? 'block' : 'none'; ?>;">
            </div>
        </div>
        <div class="univ-meta-row" style="margin-top: 15px;">
            <label class="univ-meta-label">Default Video Thumbnail Image</label>
            <input type="text" name="voice_video_thumb" value="<?php echo esc_url($voice_video_thumb); ?>" class="media-url-input" style="width: 70%;" readonly>
            <button type="button" class="button select-media-btn">Select Image</button>
            <button type="button" class="button clear-media-btn">Clear</button>
            <div class="media-preview-wrapper" style="margin-top: 5px;">
                <img src="<?php echo esc_url($voice_video_thumb); ?>" style="max-height: 50px; display: <?php echo $voice_video_thumb ? 'block' : 'none'; ?>;">
            </div>
        </div>
    </div>
    <?php
}

// 7. Mid Section Callback
function univ_mid_callback($post) {
    $bg_color = get_post_meta($post->ID, 'mid_bg_color', true);
    $image = get_post_meta($post->ID, 'mid_image', true);
    $title = get_post_meta($post->ID, 'mid_title', true);
    $description = get_post_meta($post->ID, 'mid_description', true);
    $cta_text = get_post_meta($post->ID, 'mid_cta_text', true);
    $cta_link = get_post_meta($post->ID, 'mid_cta_link', true);
    $cta_target = get_post_meta($post->ID, 'mid_cta_target', true);

    $accordion = get_post_meta($post->ID, 'univ_mid_accordion', true);
    if (!is_array($accordion)) $accordion = [];

    render_bg_color_field('mid_bg_color', $bg_color);
    ?>
    <div class="univ-meta-row">
        <label class="univ-meta-label">Title</label>
        <input type="text" name="mid_title" value="<?php echo esc_attr($title); ?>" style="width:100%;">
    </div>
    <div class="univ-meta-row">
        <label class="univ-meta-label">Description</label>
        <textarea name="mid_description" style="width:100%; height:80px;"><?php echo esc_textarea($description); ?></textarea>
    </div>
    <div class="univ-meta-row">
        <label class="univ-meta-label">Left Side Image</label>
        <input type="text" name="mid_image" value="<?php echo esc_url($image); ?>" class="media-url-input" style="width:70%;" readonly>
        <button type="button" class="button select-media-btn">Select Image</button>
        <button type="button" class="button clear-media-btn">Clear</button>
        <div class="media-preview-wrapper" style="margin-top:10px;">
            <img src="<?php echo esc_url($image); ?>" style="max-width:200px; display:<?php echo $image ? 'block' : 'none'; ?>;">
        </div>
    </div>
    <div class="univ-meta-row">
        <label class="univ-meta-label">CTA Text</label>
        <input type="text" name="mid_cta_text" value="<?php echo esc_attr($cta_text); ?>" style="width:100%;">
    </div>
    <div class="univ-meta-row">
        <label class="univ-meta-label">CTA Link URL</label>
        <input type="text" name="mid_cta_link" value="<?php echo esc_attr($cta_link); ?>" style="width:100%;">
    </div>
    <div class="univ-meta-row">
        <label class="univ-meta-label">CTA Target</label>
        <select name="mid_cta_target">
            <option value="_self" <?php selected($cta_target, '_self'); ?>>Open in Same Tab</option>
            <option value="_blank" <?php selected($cta_target, '_blank'); ?>>Open in New Tab</option>
        </select>
    </div>

    <h4>Repeatable Accordion Items</h4>
    <div id="accordion-container">
        <?php foreach ($accordion as $index => $item) { 
            $t = $item['title'] ?? '';
            $d = $item['description'] ?? '';
            ?>
            <div class="accordion-row" style="border: 1px solid #ccc; padding: 15px; margin-bottom: 10px; background: #fff; position: relative;">
                <span class="remove-row" style="position: absolute; right: 10px; top: 10px; color: red; cursor: pointer; font-weight: bold;">X</span>
                <p>
                    <label><b>Accordion Title</b></label><br>
                    <input type="text" name="univ_mid_accordion[<?php echo $index; ?>][title]" value="<?php echo esc_attr($t); ?>" style="width: 100%;">
                </p>
                <p>
                    <label><b>Accordion Description</b></label><br>
                    <textarea name="univ_mid_accordion[<?php echo $index; ?>][description]" style="width: 100%; height: 60px;"><?php echo esc_textarea($d); ?></textarea>
                </p>
            </div>
        <?php } ?>
    </div>
    <p><button type="button" class="button button-secondary" id="add-accordion-row">+ Add Accordion Row</button></p>
    <?php
}

// 8. Latest News Callback
function univ_news_callback($post) {
    $bg_color = get_post_meta($post->ID, 'news_bg_color', true);
    $show = get_post_meta($post->ID, 'show_latest_news', true);
    $title = get_post_meta($post->ID, 'news_title', true) ?: 'Latest News';
    $limit = get_post_meta($post->ID, 'news_limit', true) ?: '4';

    render_bg_color_field('news_bg_color', $bg_color);
    ?>
    <div class="univ-meta-row">
        <label>
            <input type="checkbox" name="show_latest_news" value="yes" <?php checked($show, 'yes'); ?>>
            <b>Show Latest News Section</b>
        </label>
    </div>
    <div class="univ-meta-row" style="margin-top:10px;">
        <label class="univ-meta-label">Section Title</label>
        <input type="text" name="news_title" value="<?php echo esc_attr($title); ?>" style="width:100%;">
    </div>
    <div class="univ-meta-row" style="margin-top:10px;">
        <label class="univ-meta-label">Limit News Items to Display</label>
        <input type="number" name="news_limit" value="<?php echo esc_attr($limit); ?>" min="1" max="20" style="width:100px;">
    </div>
    <?php
}

// 9. Career Callback
function univ_career_callback($post) {
    $bg_color = get_post_meta($post->ID, 'career_bg_color', true);
    $title = get_post_meta($post->ID, 'career_title', true);
    $limit = get_post_meta($post->ID, 'career_limit', true) ?: '3';

    render_bg_color_field('career_bg_color', $bg_color);
    ?>
    <div class="univ-meta-row">
        <label class="univ-meta-label">Section Title</label>
        <input type="text" name="career_title" value="<?php echo esc_attr($title); ?>" style="width:100%;">
    </div>
    <div class="univ-meta-row">
        <label class="univ-meta-label">Limit Careers to Display</label>
        <input type="number" name="career_limit" value="<?php echo esc_attr($limit); ?>" min="1" max="20" style="width:100px;">
    </div>
    <?php
}


/**
 * 5. CPT Metabox CALLBACKS
 */

// Stats CPT Meta Callback
function stat_value_callback($post) {
    wp_nonce_field('univ_nonce_action', 'univ_nonce');
    $value = get_post_meta($post->ID, 'stat_value', true);
    ?>
    <p>
        <label class="univ-meta-label">Statistic Value (e.g. 3,491 or TOP 4)</label>
        <input type="text" name="stat_value" value="<?php echo esc_attr($value); ?>" style="width:100%;">
    </p>
    <?php
}

// Band CPT Meta Callback
function band_meta_callback($post) {
    wp_nonce_field('univ_nonce_action', 'univ_nonce');
    $icon = get_post_meta($post->ID, 'band_icon', true);
    $desc = get_post_meta($post->ID, 'band_description', true);
    $cta_text = get_post_meta($post->ID, 'band_cta_text', true);
    $cta_link = get_post_meta($post->ID, 'band_cta_link', true);
    $cta_target = get_post_meta($post->ID, 'band_cta_target', true);
    ?>
    <div class="univ-meta-row">
        <label class="univ-meta-label">Band Icon Image</label>
        <input type="text" name="band_icon" value="<?php echo esc_url($icon); ?>" class="media-url-input" style="width:70%;" readonly>
        <button type="button" class="button select-media-btn">Select Icon</button>
        <button type="button" class="button clear-media-btn">Clear</button>
        <div class="media-preview-wrapper" style="margin-top:5px;">
            <img src="<?php echo esc_url($icon); ?>" style="max-height:50px; display:<?php echo $icon ? 'block' : 'none'; ?>;">
        </div>
    </div>
    <div class="univ-meta-row">
        <label class="univ-meta-label">Band Description</label>
        <textarea name="band_description" style="width:100%; height:60px;"><?php echo esc_textarea($desc); ?></textarea>
    </div>
    <div class="univ-meta-row">
        <label class="univ-meta-label">CTA Button Text</label>
        <input type="text" name="band_cta_text" value="<?php echo esc_attr($cta_text); ?>" style="width:100%;">
    </div>
    <div class="univ-meta-row">
        <label class="univ-meta-label">CTA Link URL</label>
        <input type="text" name="band_cta_link" value="<?php echo esc_attr($cta_link); ?>" style="width:100%;">
    </div>
    <div class="univ-meta-row">
        <label class="univ-meta-label">CTA Link Target</label>
        <select name="band_cta_target">
            <option value="_self" <?php selected($cta_target, '_self'); ?>>Open in Same Tab</option>
            <option value="_blank" <?php selected($cta_target, '_blank'); ?>>Open in New Tab</option>
        </select>
    </div>
    <?php
}

// Success Story CPT Meta Callback
function story_meta_callback($post) {
    wp_nonce_field('univ_nonce_action', 'univ_nonce');
    $subtitle = get_post_meta($post->ID, 'story_subtitle', true);
    $desc = get_post_meta($post->ID, 'story_description', true);
    $image = get_post_meta($post->ID, 'story_image', true);
    $video_url = get_post_meta($post->ID, 'story_video_url', true);
    ?>
    <div class="univ-meta-row">
        <label class="univ-meta-label">Subtitle (e.g. Programme Name)</label>
        <input type="text" name="story_subtitle" value="<?php echo esc_attr($subtitle); ?>" style="width:100%;">
    </div>
    <div class="univ-meta-row">
        <label class="univ-meta-label">Story Description</label>
        <textarea name="story_description" style="width:100%; height:80px;"><?php echo esc_textarea($desc); ?></textarea>
    </div>
    <div class="univ-meta-row">
        <label class="univ-meta-label">Story Thumbnail Image</label>
        <input type="text" name="story_image" value="<?php echo esc_url($image); ?>" class="media-url-input" style="width:70%;" readonly>
        <button type="button" class="button select-media-btn">Select Image</button>
        <button type="button" class="button clear-media-btn">Clear</button>
        <div class="media-preview-wrapper" style="margin-top:5px;">
            <img src="<?php echo esc_url($image); ?>" style="max-width:200px; display:<?php echo $image ? 'block' : 'none'; ?>;">
        </div>
    </div>
    <div class="univ-meta-row">
        <label class="univ-meta-label">Video URL (If set, this will display under "Voice of Alumni" video section. If empty, it displays under "Success Snapshot" image section)</label>
        <input type="text" name="story_video_url" value="<?php echo esc_attr($video_url); ?>" style="width:100%;" placeholder="e.g. https://www.youtube.com/embed/XXXXXX or self-hosted video url">
    </div>
    <?php
}

// Career CPT Meta Callback
function career_meta_callback($post) {
    wp_nonce_field('univ_nonce_action', 'univ_nonce');
    $location = get_post_meta($post->ID, 'career_location', true);
    $job_type = get_post_meta($post->ID, 'career_type', true);
    $apply_link = get_post_meta($post->ID, 'career_apply_link', true);
    ?>
    <div class="univ-meta-row">
        <label class="univ-meta-label">Location (e.g. London / Remote)</label>
        <input type="text" name="career_location" value="<?php echo esc_attr($location); ?>" style="width:100%;">
    </div>
    <div class="univ-meta-row">
        <label class="univ-meta-label">Job Type (e.g. Full-time / Part-time)</label>
        <input type="text" name="career_type" value="<?php echo esc_attr($job_type); ?>" style="width:100%;">
    </div>
    <div class="univ-meta-row">
        <label class="univ-meta-label">Apply Link</label>
        <input type="text" name="career_apply_link" value="<?php echo esc_attr($apply_link); ?>" style="width:100%;">
    </div>
    <?php
}


/**
 * 6. SAVE Metabox values
 */
function save_university_meta_data($post_id) {
    // Nonce verification
    if (!isset($_POST['univ_nonce']) || !wp_verify_nonce($_POST['univ_nonce'], 'univ_nonce_action')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    // SAVE PAGE METABOXES
    if (isset($_POST['hero_title'])) {
        update_post_meta($post_id, 'hero_title', sanitize_text_field($_POST['hero_title']));
        update_post_meta($post_id, 'hero_description', wp_kses_post($_POST['hero_description'] ?? ''));
        update_post_meta($post_id, 'hero_bg_image', esc_url_raw($_POST['hero_bg_image'] ?? ''));
        update_post_meta($post_id, 'hero_bg_color', sanitize_hex_color($_POST['hero_bg_color'] ?? '#ffffff'));

        // Intro Section
        update_post_meta($post_id, 'intro_bg_color', sanitize_hex_color($_POST['intro_bg_color'] ?? '#ffffff'));
        update_post_meta($post_id, 'intro_title', sanitize_text_field($_POST['intro_title'] ?? ''));
        update_post_meta($post_id, 'intro_subtitle', sanitize_text_field($_POST['intro_subtitle'] ?? ''));
        update_post_meta($post_id, 'intro_description', wp_kses_post($_POST['intro_description'] ?? ''));
        update_post_meta($post_id, 'intro_image', esc_url_raw($_POST['intro_image'] ?? ''));
        update_post_meta($post_id, 'intro_cta_text', sanitize_text_field($_POST['intro_cta_text'] ?? ''));
        update_post_meta($post_id, 'intro_cta_link', sanitize_text_field($_POST['intro_cta_link'] ?? ''));
        update_post_meta($post_id, 'intro_cta_target', sanitize_text_field($_POST['intro_cta_target'] ?? '_self'));

        // Stats Section
        update_post_meta($post_id, 'stats_bg_color', sanitize_hex_color($_POST['stats_bg_color'] ?? '#ffffff'));
        update_post_meta($post_id, 'stats_limit', intval($_POST['stats_limit'] ?? 4));
        $selected_stats = [];
        if (isset($_POST['univ_selected_stats']) && is_array($_POST['univ_selected_stats'])) {
            foreach ($_POST['univ_selected_stats'] as $stat_id) {
                $selected_stats[] = intval($stat_id);
            }
        }
        update_post_meta($post_id, 'univ_selected_stats', $selected_stats);

        // Alumni Benefits (Repeatable Array)
        update_post_meta($post_id, 'benefits_bg_color', sanitize_hex_color($_POST['benefits_bg_color'] ?? '#ffffff'));
        update_post_meta($post_id, 'benefits_title', sanitize_text_field($_POST['benefits_title'] ?? 'Alumni benefits and services'));
        update_post_meta($post_id, 'benefits_desc', wp_kses_post($_POST['benefits_desc'] ?? ''));
        $benefits = [];
        if (isset($_POST['univ_benefits']) && is_array($_POST['univ_benefits'])) {
            foreach ($_POST['univ_benefits'] as $b) {
                if (!empty($b['title']) || !empty($b['description']) || !empty($b['icon'])) {
                    $benefits[] = [
                        'icon' => esc_url_raw($b['icon'] ?? ''),
                        'title' => sanitize_text_field($b['title'] ?? ''),
                        'description' => wp_kses_post($b['description'] ?? '')
                    ];
                }
            }
        }
        update_post_meta($post_id, 'univ_benefits', $benefits);

        // Band Section
        update_post_meta($post_id, 'band_bg_color', sanitize_hex_color($_POST['band_bg_color'] ?? '#ffffff'));
        update_post_meta($post_id, 'selected_band_id', sanitize_text_field($_POST['selected_band_id'] ?? ''));

        // Success Story Config
        update_post_meta($post_id, 'snapshot_bg_color', sanitize_hex_color($_POST['snapshot_bg_color'] ?? '#ffffff'));
        update_post_meta($post_id, 'snapshot_limit', intval($_POST['snapshot_limit'] ?? 3));
        update_post_meta($post_id, 'snapshot_type', sanitize_text_field($_POST['snapshot_type'] ?? 'image'));
        update_post_meta($post_id, 'snapshot_video_thumb', esc_url_raw($_POST['snapshot_video_thumb'] ?? ''));

        update_post_meta($post_id, 'voice_bg_color', sanitize_hex_color($_POST['voice_bg_color'] ?? '#ffffff'));
        update_post_meta($post_id, 'voice_limit', intval($_POST['voice_limit'] ?? 3));
        update_post_meta($post_id, 'voice_type', sanitize_text_field($_POST['voice_type'] ?? 'video'));
        update_post_meta($post_id, 'voice_play_icon', esc_url_raw($_POST['voice_play_icon'] ?? ''));
        update_post_meta($post_id, 'voice_video_thumb', esc_url_raw($_POST['voice_video_thumb'] ?? ''));

        // Mid Section
        update_post_meta($post_id, 'mid_bg_color', sanitize_hex_color($_POST['mid_bg_color'] ?? '#ffffff'));
        update_post_meta($post_id, 'mid_image', esc_url_raw($_POST['mid_image'] ?? ''));
        update_post_meta($post_id, 'mid_title', sanitize_text_field($_POST['mid_title'] ?? ''));
        update_post_meta($post_id, 'mid_description', wp_kses_post($_POST['mid_description'] ?? ''));
        update_post_meta($post_id, 'mid_cta_text', sanitize_text_field($_POST['mid_cta_text'] ?? ''));
        update_post_meta($post_id, 'mid_cta_link', sanitize_text_field($_POST['mid_cta_link'] ?? ''));
        update_post_meta($post_id, 'mid_cta_target', sanitize_text_field($_POST['mid_cta_target'] ?? '_self'));

        // Mid Accordion Repeatable
        $accordion = [];
        if (isset($_POST['univ_mid_accordion']) && is_array($_POST['univ_mid_accordion'])) {
            foreach ($_POST['univ_mid_accordion'] as $item) {
                if (!empty($item['title']) || !empty($item['description'])) {
                    $accordion[] = [
                        'title' => sanitize_text_field($item['title'] ?? ''),
                        'description' => wp_kses_post($item['description'] ?? '')
                    ];
                }
            }
        }
        update_post_meta($post_id, 'univ_mid_accordion', $accordion);

        // Latest News Config
        update_post_meta($post_id, 'news_bg_color', sanitize_hex_color($_POST['news_bg_color'] ?? '#ffffff'));
        update_post_meta($post_id, 'show_latest_news', sanitize_text_field($_POST['show_latest_news'] ?? 'no'));
        update_post_meta($post_id, 'news_title', sanitize_text_field($_POST['news_title'] ?? 'Latest News'));
        update_post_meta($post_id, 'news_limit', intval($_POST['news_limit'] ?? 4));

        // Career Config
        update_post_meta($post_id, 'career_bg_color', sanitize_hex_color($_POST['career_bg_color'] ?? '#ffffff'));
        update_post_meta($post_id, 'career_title', sanitize_text_field($_POST['career_title'] ?? ''));
        update_post_meta($post_id, 'career_limit', intval($_POST['career_limit'] ?? 3));
    }

    // SAVE CPT METABOXES
    if (isset($_POST['stat_value'])) {
        update_post_meta($post_id, 'stat_value', sanitize_text_field($_POST['stat_value']));
    }

    if (isset($_POST['band_icon'])) {
        update_post_meta($post_id, 'band_icon', esc_url_raw($_POST['band_icon']));
        update_post_meta($post_id, 'band_description', wp_kses_post($_POST['band_description'] ?? ''));
        update_post_meta($post_id, 'band_cta_text', sanitize_text_field($_POST['band_cta_text'] ?? ''));
        update_post_meta($post_id, 'band_cta_link', sanitize_text_field($_POST['band_cta_link'] ?? ''));
        update_post_meta($post_id, 'band_cta_target', sanitize_text_field($_POST['band_cta_target'] ?? '_self'));
    }

    if (isset($_POST['story_subtitle'])) {
        update_post_meta($post_id, 'story_subtitle', sanitize_text_field($_POST['story_subtitle']));
        update_post_meta($post_id, 'story_description', wp_kses_post($_POST['story_description'] ?? ''));
        update_post_meta($post_id, 'story_image', esc_url_raw($_POST['story_image'] ?? ''));
        update_post_meta($post_id, 'story_video_url', sanitize_text_field($_POST['story_video_url'] ?? ''));
    }

    if (isset($_POST['career_location'])) {
        update_post_meta($post_id, 'career_location', sanitize_text_field($_POST['career_location']));
        update_post_meta($post_id, 'career_type', sanitize_text_field($_POST['career_type'] ?? ''));
        update_post_meta($post_id, 'career_apply_link', sanitize_text_field($_POST['career_apply_link'] ?? ''));
    }
}
add_action('save_post', 'save_university_meta_data');


/**
 * 7. Admin Javascript & Styling in Footer
 */
function university_admin_footer_scripts() {
    global $post;
    if (!$post) return;
    
    // Only output scripts when editing a page or CPTs
    $post_type = get_post_type($post);
    $allowed_types = ['page', 'statistics', 'band', 'success_story', 'career'];
    if (!in_array($post_type, $allowed_types)) return;
    ?>
    <style>
        /* Modern styling to make WordPress backend metaboxes clean */
        .univ-meta-row {
            margin-bottom: 15px;
        }
        .univ-meta-label {
            font-weight: bold;
            display: block;
            margin-bottom: 6px;
            color: #23282d;
            font-size: 13px;
        }
        .media-preview-wrapper img {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 4px;
            background: #fff;
        }
        .benefit-row, .accordion-row {
            border: 1px solid #ccc !important;
            border-radius: 4px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            background: #fafafa !important;
            transition: border-color 0.2s ease;
        }
        .benefit-row:hover, .accordion-row:hover {
            border-color: #999 !important;
        }
        .remove-row {
            border: 1px solid #d63638;
            border-radius: 3px;
            background: #fff;
            padding: 2px 6px;
            font-size: 11px;
            transition: all 0.1s ease;
        }
        .remove-row:hover {
            background: #d63638;
            color: #fff !important;
        }
        .univ-sortable-placeholder {
            border: 1px dashed #999;
            background: #f0f0f0;
            height: 40px;
            margin-bottom: 8px;
            border-radius: 3px;
        }
    </style>
    <script>
    jQuery(document).ready(function($) {
        // Media Library Uploader
        $(document).on('click', '.select-media-btn', function(e) {
            e.preventDefault();
            var button = $(this);
            var input = button.siblings('.media-url-input');
            var preview = button.siblings('.media-preview-wrapper').find('img');
            
            var mediaUploader = wp.media({
                title: 'Select Image',
                button: { text: 'Use this image' },
                multiple: false
            }).on('select', function() {
                var attachment = mediaUploader.state().get('selection').first().toJSON();
                input.val(attachment.url);
                preview.attr('src', attachment.url).show();
            }).open();
        });

        $(document).on('click', '.clear-media-btn', function(e) {
            e.preventDefault();
            var button = $(this);
            var input = button.siblings('.media-url-input');
            var preview = button.siblings('.media-preview-wrapper').find('img');
            input.val('');
            preview.attr('src', '').hide();
        });

        // Initialize Color Pickers
        $('.wp-color-picker-field').wpColorPicker();

        // Sortable Statistics
        $("#univ-stats-selected-list").sortable({
            handle: '.drag-handle',
            placeholder: 'univ-sortable-placeholder'
        });

        // Dropdown Add Handler
        $("#univ-stats-select").on('change', function() {
            var val = $(this).val();
            var text = $(this).find('option:selected').text();
            if (!val) return;
            
            // Check if already in the list
            if ($("#univ-stats-selected-list input[value='" + val + "']").length > 0) {
                alert("This statistic is already selected!");
                $(this).val('');
                return;
            }
            
            var li = `
            <li class="univ-stat-sortable-item" style="border: 1px solid #ccc; background: #fff; padding: 8px 12px; margin-bottom: 8px; display: flex; align-items: center; justify-content: space-between; border-radius: 3px; cursor: move; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                <span style="display: flex; align-items: center; gap: 8px;">
                    <span class="dashicons dashicons-menu drag-handle" style="color: #999; cursor: move;"></span>
                    <strong>${text}</strong>
                </span>
                <button type="button" class="remove-stat-item" style="background: none; border: none; color: #d63638; cursor: pointer; font-weight: bold; font-size: 14px;">X</button>
                <input type="hidden" name="univ_selected_stats[]" value="${val}">
            </li>
            `;
            $("#univ-stats-selected-list").append(li);
            $(this).val('');
        });

        // Remove Statistics Handler
        $(document).on('click', '.remove-stat-item', function() {
            $(this).closest('li').remove();
        });

        // Repeatable Alumni Benefits
        var benefitCount = $('#benefits-container .benefit-row').length;
        $('#add-benefit-row').on('click', function(e) {
            e.preventDefault();
            var row = `
            <div class="benefit-row" style="border: 1px solid #ccc; padding: 15px; margin-bottom: 10px; background: #fff; position: relative;">
                <span class="remove-row" style="position: absolute; right: 10px; top: 10px; color: red; cursor: pointer; font-weight: bold;">X</span>
                <p>
                    <label><b>Icon Image</b></label><br>
                    <input type="text" name="univ_benefits[${benefitCount}][icon]" value="" class="media-url-input" style="width: 70%;" readonly>
                    <button type="button" class="button select-media-btn">Select Icon</button>
                    <button type="button" class="button clear-media-btn">Clear</button>
                    <div class="media-preview-wrapper" style="margin-top: 5px;">
                        <img src="" style="max-height: 50px; display: none;">
                    </div>
                </p>
                <p>
                    <label><b>Heading</b></label><br>
                    <input type="text" name="univ_benefits[${benefitCount}][title]" value="" style="width: 100%;">
                </p>
                <p>
                    <label><b>Description</b></label><br>
                    <textarea name="univ_benefits[${benefitCount}][description]" style="width: 100%; height: 60px;"></textarea>
                </p>
            </div>
            `;
            $('#benefits-container').append(row);
            benefitCount++;
        });

        // Repeatable Mid Accordion
        var accordionCount = $('#accordion-container .accordion-row').length;
        $('#add-accordion-row').on('click', function(e) {
            e.preventDefault();
            var row = `
            <div class="accordion-row" style="border: 1px solid #ccc; padding: 15px; margin-bottom: 10px; background: #fff; position: relative;">
                <span class="remove-row" style="position: absolute; right: 10px; top: 10px; color: red; cursor: pointer; font-weight: bold;">X</span>
                <p>
                    <label><b>Accordion Title</b></label><br>
                    <input type="text" name="univ_mid_accordion[${accordionCount}][title]" value="" style="width: 100%;">
                </p>
                <p>
                    <label><b>Accordion Description</b></label><br>
                    <textarea name="univ_mid_accordion[${accordionCount}][description]" style="width: 100%; height: 60px;"></textarea>
                </p>
            </div>
            `;
            $('#accordion-container').append(row);
            accordionCount++;
        });

        // Remove repeatable rows
        $(document).on('click', '.remove-row', function(e) {
            e.preventDefault();
            $(this).closest('div').remove();
        });

        // Dynamic show/hide metaboxes based on Template selection
        function toggleUniversityMetaboxes() {
            var template = $('#page_template').val() || $('select[name="page_template"]').val();
            // In Gutenberg Block Editor, template is in the side panel data structure
            var metaboxes = $('[id^="univ_"]');
            
            // Check if template is University Page Template
            if (template === 'page-university.php') {
                metaboxes.show();
            } else {
                // If not Gutenberg but normal editor, or fallback check
                var gutenbergTemplate = $('.editor-page-attributes__template select').val();
                if (gutenbergTemplate === 'page-university.php') {
                    metaboxes.show();
                } else {
                    metaboxes.hide();
                }
            }
        }

        // Run on load and listen to template dropdown changes
        toggleUniversityMetaboxes();
        $(document).on('change', '#page_template, select[name="page_template"], .editor-page-attributes__template select', function() {
            toggleUniversityMetaboxes();
        });
        
        // Wait a second for Gutenberg initialization
        setTimeout(function() {
            toggleUniversityMetaboxes();
            $('.wp-color-picker-field').wpColorPicker();
        }, 1500);
    });
    </script>
    <?php
}
add_action('admin_footer', 'university_admin_footer_scripts');