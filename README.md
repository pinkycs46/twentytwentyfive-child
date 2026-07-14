# Twenty Twenty-Five Child Theme: University Page Template

A custom WordPress child theme built on top of the **Twenty Twenty-Five** parent theme. This child theme includes a fully customizable, metadata-driven **University Page Template** (`page-university.php`) with multiple interactive layout sections, custom post types (CPTs), and externalized styling/interaction scripts.

## Features & Custom Sections

1. **Hero Banner Section**: Customizable background image, deep-burgundy base color, page title, and description.
2. **Intro Section**: Features a breadcrumb trail, title, subtitle, descriptive text, and a custom image layout.
3. **Statistics Section**: Displays dynamic statistics fetched from the `statistics` Custom Post Type. Features select-to-add, sortable drag-and-drop order rearrangement, and customizable display limits.
4. **Alumni Benefits & Services**: Repeatable meta box cards allowing custom icon uploads, title headings, and descriptions for an unlimited list of benefits.
5. **Featured Band Section**: Single selection dropdown to feature a custom post from the `band` CPT with detailed descriptions and CTA links.
6. **Success Stories (Success Snapshots & Voices of Alumni)**:
   - Fetches stories from the `success_story` CPT.
   - **Success Snapshots**: Displays image-only success stories.
   - **Voices of Alumni**: Displays video success stories with custom popup modal playback triggers.
   - Includes customizable settings for default section video cover thumbnails, custom play buttons (image/SVG), and display limits.
7. **Mid Accordion Section**: Static, fully-expanded item lists containing titles and description text blocks.
8. **Latest News Section**: Displays the latest news cards from the `latest_news` CPT with query limit controls.
9. **Careers & Opportunities**: Shows the latest active job listings from the `career` CPT in a responsive multi-column layout.

---

## Technical Architecture

* **Template File**: [page-university.php](file:///c:/xampp/htdocs/wordpress682/wp-content/themes/twentytwentyfive-child/page-university.php)
* **Custom Functions & CPTs**: [functions.php](file:///c:/xampp/htdocs/wordpress682/wp-content/themes/twentytwentyfive-child/functions.php)
* **Stylesheets**: [style.css](file:///c:/xampp/htdocs/wordpress682/wp-content/themes/twentytwentyfive-child/style.css) (All custom styles externalized, left-aligned layout defaults, clean contrast formatting).
* **Interactions Script**: [custom.js](file:///c:/xampp/htdocs/wordpress682/wp-content/themes/twentytwentyfive-child/custom.js) (Handles interactive accordion items, esc-key handlers, and overlay popup video modals).

---

## Custom Post Types (CPTs) Registered

* `statistics` — Statistical highlights (e.g. "TOP 4", "3,491")
* `band` — Custom band highlights
* `success_story` — Alumni feedback stories supporting image & video URLs
* `latest_news` — News updates
* `career` — Job listings



