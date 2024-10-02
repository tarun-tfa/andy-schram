<?php
/**
 * Andy Schram functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Andy Schram
 * @since Andy Schram 1.0
 */

/**
 * Register block styles.
 */

if (!function_exists('twentytwentyfour_block_styles')):
    /**
     * Register custom block styles
     *
     * @since Andy Schram 1.0
     * @return void
     */
    function twentytwentyfour_block_styles()
    {

        register_block_style(
            'core/details',
            array(
                'name' => 'arrow-icon-details',
                'label' => __('Arrow icon', 'twentytwentyfour'),
                /*
                 * Styles for the custom Arrow icon style of the Details block
                 */
                'inline_style' => '
				.is-style-arrow-icon-details {
					padding-top: var(--wp--preset--spacing--10);
					padding-bottom: var(--wp--preset--spacing--10);
				}

				.is-style-arrow-icon-details summary {
					list-style-type: "\2193\00a0\00a0\00a0";
				}

				.is-style-arrow-icon-details[open]>summary {
					list-style-type: "\2192\00a0\00a0\00a0";
				}',
            )
        );
        register_block_style(
            'core/post-terms',
            array(
                'name' => 'pill',
                'label' => __('Pill', 'twentytwentyfour'),
                /*
                 * Styles variation for post terms
                 * https://github.com/WordPress/gutenberg/issues/24956
                 */
                'inline_style' => '
				.is-style-pill a,
				.is-style-pill span:not([class], [data-rich-text-placeholder]) {
					display: inline-block;
					background-color: var(--wp--preset--color--base-2);
					padding: 0.375rem 0.875rem;
					border-radius: var(--wp--preset--spacing--20);
				}

				.is-style-pill a:hover {
					background-color: var(--wp--preset--color--contrast-3);
				}',
            )
        );
        register_block_style(
            'core/list',
            array(
                'name' => 'checkmark-list',
                'label' => __('Checkmark', 'twentytwentyfour'),
                /*
                 * Styles for the custom checkmark list block style
                 * https://github.com/WordPress/gutenberg/issues/51480
                 */
                'inline_style' => '
				ul.is-style-checkmark-list {
					list-style-type: "\2713";
				}

				ul.is-style-checkmark-list li {
					padding-inline-start: 1ch;
				}',
            )
        );
        register_block_style(
            'core/navigation-link',
            array(
                'name' => 'arrow-link',
                'label' => __('With arrow', 'twentytwentyfour'),
                /*
                 * Styles for the custom arrow nav link block style
                 */
                'inline_style' => '
				.is-style-arrow-link .wp-block-navigation-item__label:after {
					content: "\2197";
					padding-inline-start: 0.25rem;
					vertical-align: middle;
					text-decoration: none;
					display: inline-block;
				}',
            )
        );
        register_block_style(
            'core/heading',
            array(
                'name' => 'asterisk',
                'label' => __('With asterisk', 'twentytwentyfour'),
                'inline_style' => "
				.is-style-asterisk:before {
					content: '';
					width: 1.5rem;
					height: 3rem;
					background: var(--wp--preset--color--contrast-2, currentColor);
					clip-path: path('M11.93.684v8.039l5.633-5.633 1.216 1.23-5.66 5.66h8.04v1.737H13.2l5.701 5.701-1.23 1.23-5.742-5.742V21h-1.737v-8.094l-5.77 5.77-1.23-1.217 5.743-5.742H.842V9.98h8.162l-5.701-5.7 1.23-1.231 5.66 5.66V.684h1.737Z');
					display: block;
				}

				/* Hide the asterisk if the heading has no content, to avoid using empty headings to display the asterisk only, which is an A11Y issue */
				.is-style-asterisk:empty:before {
					content: none;
				}

				.is-style-asterisk:-moz-only-whitespace:before {
					content: none;
				}

				.is-style-asterisk.has-text-align-center:before {
					margin: 0 auto;
				}

				.is-style-asterisk.has-text-align-right:before {
					margin-left: auto;
				}

				.rtl .is-style-asterisk.has-text-align-left:before {
					margin-right: auto;
				}",
            )
        );
    }
endif;

add_action('init', 'twentytwentyfour_block_styles');

/**
 * Enqueue block stylesheets.
 */

if (!function_exists('twentytwentyfour_block_stylesheets')):
    /**
     * Enqueue custom block stylesheets
     *
     * @since Twenty Twenty-Four 1.0
     * @return void
     */
    function twentytwentyfour_block_stylesheets()
    {
        /**
         * The wp_enqueue_block_style() function allows us to enqueue a stylesheet
         * for a specific block. These will only get loaded when the block is rendered
         * (both in the editor and on the front end), improving performance
         * and reducing the amount of data requested by visitors.
         *
         * See https://make.wordpress.org/core/2021/12/15/using-multiple-stylesheets-per-block/ for more info.
         */
        wp_enqueue_block_style(
            'core/button',
            array(
                'handle' => 'twentytwentyfour-button-style-outline',
                'src' => get_parent_theme_file_uri('assets/css/button-outline.css'),
                'ver' => wp_get_theme(get_template())->get('Version'),
                'path' => get_parent_theme_file_path('assets/css/button-outline.css'),
            )
        );
    }
endif;

add_action('init', 'twentytwentyfour_block_stylesheets');

/**
 * Register pattern categories.
 */

if (!function_exists('twentytwentyfour_pattern_categories')):
    /**
     * Register pattern categories
     *
     * @since Twenty Twenty-Four 1.0
     * @return void
     */
    function twentytwentyfour_pattern_categories()
    {

        register_block_pattern_category(
            'twentytwentyfour_page',
            array(
                'label' => _x('Pages', 'Block pattern category', 'twentytwentyfour'),
                'description' => __('A collection of full page layouts.', 'twentytwentyfour'),
            )
        );
    }
endif;

add_action('init', 'twentytwentyfour_pattern_categories');

add_action('wp_enqueue_scripts', 'theme_slug_enqueue_styles');

function theme_slug_enqueue_styles()
{
    wp_enqueue_style(
        'theme-slug-style',
        get_stylesheet_uri()
    );
    wp_enqueue_style('owl-carousel-css', get_template_directory_uri() . '/assets/css/owl.carousel.min.css');
    wp_enqueue_style('owl-carousel-theme-css', get_template_directory_uri() . '/assets/css/owl.theme.default.css');
    wp_enqueue_script('owl.carousel', get_template_directory_uri() . '/assets/js/owl.carousel.min.js', array(), '1.0.0', true);
    wp_enqueue_script('owl.carousel-slider', get_template_directory_uri() . '/assets/js/owl_slider.js', array(), '1.0.0', true);
    wp_enqueue_script('global', get_template_directory_uri() . '/assets/js/global.js', array(), '1.0.0', true);
    wp_enqueue_style('bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css');

    wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js', array('jquery'), null, true);
    wp_localize_script('global', 'frontendajax', array('ajaxurl' => admin_url('admin-ajax.php')));

}

/**
 * Register Theme Options.

function andy_schram_theme_settings()
{

    echo "<h1>Additional Theme CSS</h1>";
    $file_path = get_template_directory() . '/assets/css/custom.css';


    if (isset($_POST['custom_css'])) {
        $custom_css = wp_unslash($_POST['custom_css']);
        update_option('custom_css', $custom_css);

        $upload_dir = wp_upload_dir();
        // $file_path = $upload_dir['basedir'] . '/custom.css';

        $result = file_put_contents($file_path, $custom_css);
        if ($result === false) {
            echo '<div class="error"><p>Failed to save CSS to file.</p></div>';
        } else {
            echo '<div class="updated"><p>CSS saved to file successfully.</p></div>';
        }
    }

    $custom_css = get_option('custom_css', '');

    ?>
    <div class="wrap">
        <form method="post" action="">
            <?php wp_nonce_field('update_custom_css', 'custom_css_nonce'); ?>
            <textarea name="custom_css" rows="35" cols="80"
                class="large-text"><?php echo esc_textarea($custom_css); ?></textarea>
            <?php submit_button('Save CSS'); ?>
        </form>
    </div>
    <?php
}
function register_my_theme_settings_menu()
{
    add_menu_page("Theme Settings",'Theme settings','manage_options','theme-settings-menu','andy_schram_theme_settings');
}

add_action('admin_menu', 'register_my_theme_settings_menu');
 */
/**
 * Register Post-type gallery.
 */
function create_gallery_post_type()
{
    $labels = array(
        'name' => __('Gallery'),
        'singular_name' => __('Gallery'),
        'add_new' => __('Add New'),
        'add_new_item' => __('Add New Gallery'),
        'edit_item' => __('Edit Gallery'),
        'new_item' => __('New Gallery'),
        'view_item' => __('View Gallery'),
        'search_items' => __('Search Gallery'),
        'not_found' => __('No Gallery found'),
        'not_found_in_trash' => __('No Gallery found in Trash'),
        'parent_item_colon' => __('Parent Gallery:'),
        'all_items' => __('All Gallery'),
        'archives' => __('Gallery Archives'),
        'insert_into_item' => __('Insert into Gallery'),
        'uploaded_to_this_item' => __('Uploaded to this Gallery'),
        'featured_image' => __('Featured image'),
        'set_featured_image' => __('Set featured image'),
        'remove_featured_image' => __('Remove featured image'),
        'use_featured_image' => __('Use as featured image'),
        'menu_name' => __('Gallery'),
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'rewrite' => array('slug' => 'gallery'),
        'supports' => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments'),
        'show_in_rest' => true,
        'taxonomies' => array('post_tag', 'category'),
        'menu_icon' => 'dashicons-format-gallery'
    );

    register_post_type('gallery', $args);
}
add_action('init', 'create_gallery_post_type');

/**
 * Register Post-type Testimonial.
 */
function create_testimonial_post_type()
{
    $labels = array(
        'name' => __('Testimonials'),
        'singular_name' => __('Testimonial'),
        'add_new' => __('Add New'),
        'add_new_item' => __('Add New Testimonial'),
        'edit_item' => __('Edit Testimonial'),
        'new_item' => __('New Testimonial'),
        'view_item' => __('View Testimonial'),
        'search_items' => __('Search Testimonials'),
        'not_found' => __('No testimonials found'),
        'not_found_in_trash' => __('No testimonials found in Trash'),
        'parent_item_colon' => __('Parent Testimonial:'),
        'all_items' => __('All Testimonials'),
        'archives' => __('Testimonial Archives'),
        'insert_into_item' => __('Insert into testimonial'),
        'uploaded_to_this_item' => __('Uploaded to this testimonial'),
        'featured_image' => __('Featured image'),
        'set_featured_image' => __('Set featured image'),
        'remove_featured_image' => __('Remove featured image'),
        'use_featured_image' => __('Use as featured image'),
        'menu_name' => __('Testimonials'),
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'rewrite' => array('slug' => 'testimonials'),
        'supports' => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments'),
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-format-quote',
        'taxonomies' => array('post_tag', 'category')
    );

    register_post_type('testimonial', $args);
}
add_action('init', 'create_testimonial_post_type');

/**
 * Register Post-type Founders.
 */
function create_founders_post_type()
{
    $labels = array(
        'name' => __('Founders'),
        'singular_name' => __('Founder'),
        'add_new' => __('Add New'),
        'add_new_item' => __('Add New Founder'),
        'edit_item' => __('Edit Founder'),
        'new_item' => __('New Founder'),
        'view_item' => __('View Founder'),
        'search_items' => __('Search Founders'),
        'not_found' => __('No founders found'),
        'not_found_in_trash' => __('No Founders found in Trash'),
        'parent_item_colon' => __('Parent Founder:'),
        'all_items' => __('All Founders'),
        'archives' => __('Founder Archives'),
        'insert_into_item' => __('Insert into Founder'),
        'uploaded_to_this_item' => __('Uploaded to this Founder'),
        'featured_image' => __('Featured image'),
        'set_featured_image' => __('Set featured image'),
        'remove_featured_image' => __('Remove featured image'),
        'use_featured_image' => __('Use as featured image'),
        'menu_name' => __('Founders'),
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'rewrite' => array('slug' => 'founders'),
        'supports' => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments'),
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-format-quote',
        'taxonomies' => array('post_tag', 'category'),
    );

    register_post_type('founder', $args);
}
add_action('init', 'create_founders_post_type');
/**
 * Register Post-type Founders Image MetaBox.
 */
function add_image_metabox() {
    add_meta_box('founder_image','Full Founder Image','render_image_metabox','founder','side', 'default');
}
add_action('add_meta_boxes', 'add_image_metabox');

function render_image_metabox($post) {
    wp_nonce_field(basename(__FILE__), 'founder_image_nonce');
    $founder_image = get_post_meta($post->ID, '_founder_image', true);
    
    ?>
    <div class="founder-image-metabox">
        <input type="button" id="upload-image-button" class="button" value="Upload Image" />
        <input type="hidden" id="founder-image" name="founder_image" value="<?php echo esc_attr($founder_image); ?>" />
        <div id="image-preview" style="margin-top: 10px;">
            <?php if ($founder_image): ?>
                <img src="<?php echo esc_url($founder_image); ?>" style="max-width: 100%;" />
            <?php endif; ?>
        </div>
    </div>

    <script>
        jQuery(document).ready(function($){
            $('#upload-image-button').on('click', function(e) {
                e.preventDefault();
                
                var image_frame;
                if (image_frame) {
                    image_frame.open();
                    return;
                }
                
                image_frame = wp.media({
                    title: 'Select Image',
                    button: { text: 'Use Image' },
                    multiple: false
                });
                
                image_frame.on('select', function(){
                    var attachment = image_frame.state().get('selection').first().toJSON();
                    $('#founder-image').val(attachment.url);
                    $('#image-preview').html('<img src="'+attachment.url+'" style="max-width:100%;" />');
                });
                
                image_frame.open();
            });
        });
    </script>
    <?php
}
function save_founder_image($post_id) {
    if (!isset($_POST['founder_image_nonce']) || !wp_verify_nonce($_POST['founder_image_nonce'], basename(__FILE__))) {
        return $post_id;
    }
    
    $new_image = (isset($_POST['founder_image'])) ? sanitize_text_field($_POST['founder_image']) : '';
    update_post_meta($post_id, '_founder_image', $new_image);
}
add_action('save_post', 'save_founder_image');


// function create_type_taxonomy() {
//     $labels = array(
//         'name'              => _x( 'Types', 'taxonomy general name', 'textdomain' ),
//         'singular_name'     => _x( 'Type', 'taxonomy singular name', 'textdomain' ),
//         'search_items'      => __( 'Search Types', 'textdomain' ),
//         'all_items'         => __( 'All Types', 'textdomain' ),
//         'parent_item'       => __( 'Parent Type', 'textdomain' ),
//         'parent_item_colon' => __( 'Parent Type:', 'textdomain' ),
//         'edit_item'         => __( 'Edit Type', 'textdomain' ),
//         'update_item'       => __( 'Update Type', 'textdomain' ),
//         'add_new_item'      => __( 'Add New Type', 'textdomain' ),
//         'new_item_name'     => __( 'New Type Name', 'textdomain' ),
//         'menu_name'         => __( 'Type', 'textdomain' ),
//     );
//     $args = array(
//         'hierarchical'      => true,
//         'labels'            => $labels,
//         'show_ui'           => true,
//         'show_admin_column' => true,
//         'query_var'         => true,
//         'rewrite'           => array( 'slug' => 'type' ),
//     );

//     register_taxonomy( 'type', array( 'founder' ), $args );
// }
// add_action( 'init', 'create_type_taxonomy', 0 );

function add_testimonial_meta_boxes()
{
    add_meta_box(
        'testimonial_video',
        __('Testimonial Video', 'textdomain'),
        'render_testimonial_video_meta_box',
        'testimonial',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'add_testimonial_meta_boxes');

function render_testimonial_video_meta_box($post)
{
    // Add a nonce field so we can check for it later.
    wp_nonce_field('testimonial_video_meta_box', 'testimonial_video_meta_box_nonce');

    $value = get_post_meta($post->ID, '_testimonial_video', true);

    echo '<label for="testimonial_video_field">';
    _e('Upload Video', 'textdomain');
    echo '</label><br>';
    echo '<input type="file" id="testimonial_video_field" name="testimonial_video_field" accept="video/*" /><br>';

    if ($value) {
        echo '<p>' . __('Current Video:', 'textdomain') . '</p>';
        echo '<video width="320" height="240" controls><source src="' . esc_url($value) . '" type="video/mp4"></video>';
    }
}

function save_testimonial_video_meta_box_data($post_id)
{
    if (!isset($_POST['testimonial_video_meta_box_nonce'])) {
        return;
    }

    if (!wp_verify_nonce($_POST['testimonial_video_meta_box_nonce'], 'testimonial_video_meta_box')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (!empty($_FILES['testimonial_video_field']['name'])) {
        $supported_types = array('video/mp4', 'video/avi', 'video/mpeg', 'video/quicktime');

        $arr_file_type = wp_check_filetype(basename($_FILES['testimonial_video_field']['name']));
        $uploaded_type = $arr_file_type['type'];

        if (in_array($uploaded_type, $supported_types)) {
            $upload = wp_upload_bits($_FILES['testimonial_video_field']['name'], null, file_get_contents($_FILES['testimonial_video_field']['tmp_name']));

            if (isset($upload['error']) && $upload['error'] != 0) {
                wp_die('There was an error uploading your video. The error is: ' . $upload['error']);
            } else {
                update_post_meta($post_id, '_testimonial_video', $upload['url']);
            }
        } else {
            wp_die("The file type that you've uploaded is not a video.");
        }
    }
}
add_action('save_post', 'save_testimonial_video_meta_box_data');

// function add_video_url_to_rest_response($response, $post, $request) {
//     $video_url = get_post_meta($post->ID, '_testimonial_video', true);
//     $response->data['testimonial_video'] = $video_url;
//     return $response;
// }
// add_filter('rest_prepare_testimonial', 'add_video_url_to_rest_response', 10, 3);

// function my_custom_video_block() {
//     wp_register_script(
// 		        'custom-video-block-editor-script',
// 		        get_template_directory_uri() . '/assets/js/edit.js',
// 		        array( 'wp-blocks', 'wp-element', 'wp-editor', 'wp-data', 'wp-components', 'wp-i18n' )
// 		    );
//     // wp_register_style(
//     //     'my-custom-video-block-css',
//     //     get_template_directory_uri() . '/assets/css/my-custom-video-block.css',
//     //     array(),
//     //     filemtime(get_template_directory() . '/assets/css/my-custom-video-block.css')
//     // );

//     register_block_type('andy-schram/custom-video-block', array(
//         'editor_script' => 'blocks-js',
//         'editor_style'  => 'blocks-css',
//         'style'         => 'blocks-css',
//         'render_callback' => 'render_testimonial_video_block',
//     ));
// }
// add_action('init', 'my_custom_video_block');
// function render_testimonial_video_block($attributes, $content, $block) {
//     $post_id = $block->context['postId'];
//     $video_url = get_post_meta($post_id, '_testimonial_video', true);

//     if ($video_url) {
//         return '<video src="' . esc_url($video_url) . '" controls></video>';
//     }

//     return 'No video found.';
// }
// function my_enqueue_block_assets() {
//     wp_enqueue_script(
//         'custom-video-block-editor-script',
// 		        get_template_directory_uri() . '/assets/js/blocks.js',
// 		        array( 'wp-blocks', 'wp-element', 'wp-editor', 'wp-data', 'wp-components', 'wp-i18n' )
//     );

// }
// add_action('enqueue_block_assets', 'my_enqueue_block_assets');

// Founders popover
function add_founder_popup_to_footer()
{ ?>
    <div class="modal fade row" id="founder-popup" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <!-- <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div> -->
                <div class="modal-body" id="founder-content">
                    <!-- Content goes here -->
                </div>
            </div>
        </div>
    </div>

    <?php
}
add_action('wp_footer', 'add_founder_popup_to_footer');

// founder ajax call
function fetch_founder_data()
{
    $post_slug = isset($_POST['post_slug']) ? $_POST['post_slug'] : '';
    $html = "";

    if ($post_slug) {
        $args = array(
            'post_type' => 'founder',
            'post_status' => 'publish',
            'orderby'     => 'date',  // Order by date (or any other field you need)
            'order'       => 'ASC',
        );
        $posts = get_posts($args);

        $html .= '<div class="founder-wrapper">';
        $html .= '<div class="founder-container  founder-slider owl-carousel row gx-10">';
        $position = 0;
        foreach ($posts as $k => $post) {
            $html .= '<div class="founder-item row '.$post->post_name.' g-96"><div class="founder-gallery col-md-4">';
            $post_id = $post->ID;
            if($post->post_name === $post_slug){
                $position = $k ;
            }
            if (!$post) {
                wp_send_json_error('Post not found');
            }

            $gallery_title = get_post_meta($post_id, '_gallery_title', true);
            $gallery_src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'single-post-thumbnail')[0];
            $founder_image = get_post_meta($post_id, '_founder_image', true);

            if ($founder_image) {
                $alt = esc_html(get_the_title()) . ' ' . esc_html($gallery_title);
                $html .= '<div class="pf-slide-item d-none d-md-block"><img src="' . esc_url($founder_image) . '" alt="' . esc_attr($alt) . '"  /></div>';
				$html .= '<div class="pf-slide-item d-block d-md-none"><img src="' . esc_url($gallery_src) . '" alt="' . esc_attr($alt) . '"  /></div>';
            }
            $html .= '</div>';

            $html .= '<div class="founder-content col-md-6">';
            $categories = get_the_category($post_id);
            if (!empty($categories)) {
                $html .= '<div class="founder-desig">';
                foreach ($categories as $category) {
                    $html .= '<span class="founderpost">'.$category->name . '</span>';
                }
                $html .= '</div>';
            }
            $html .= '<div><h3 class="gallery-title">' . esc_html($post->post_title) . '</h3>' . apply_filters('the_content', $post->post_content) . '</div>';

            $html .= '</div>';
            $html .= '</div>';
        }
        $html .= '</div>';
        $html .= '<div class="founder-slider-navigation col-md-10">';
        $html .= '<div class="founder-slider-arrows row">';
        $html .= '<button class="slider-prev col-5 col-sm-5 col-md-4" onClick="PrevFounder();"><img src="' . esc_url(get_site_url()) . '/wp-content/uploads/2024/09/Union.png"> Back </button>';
        $html .= '<div class="slider-count col-1 col-sm-1 col-md-1"></div>';
        $html .= '<button class="slider-next col-5 col-sm-5 col-md-4" onClick="NextFounder();">Next <img src="' . esc_url(get_site_url()) . '/wp-content/uploads/2024/08/Union.png"></button>';
        $html .= '</div>';
        $html .= '</div>';
		$html .= '<div class="modal-footer d-block d-md-none"><button type="button" class="btn btn-primary d-block d-md-none"  data-bs-dismiss="modal">Close Bio</button></div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<input type="hidden" id="sliderPosition" name="sliderPosition" value="'.$position.'" />';

        wp_send_json_success($html,null,$position);
    } else {
        wp_send_json_error('Invalid post ID');
    }

    wp_die();
}

add_action('wp_ajax_fetch_founder_data', 'fetch_founder_data');
add_action('wp_ajax_nopriv_fetch_founder_data', 'fetch_founder_data');
