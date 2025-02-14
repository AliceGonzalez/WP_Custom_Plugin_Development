<?php
/** Plugin Name: Our Project Vault
 * Description: A plugin for manahging timelines, statises, important details in one place.
 * Version: 1.0
 * Author: Alice Gonzalez
*/

//Exit if accessed directly
//Security measure code
if(!defined('ABSPATH')){
    exit;
}

// Register custom post type for Project Management
function opv_register_project_cpt() {
    // Array: Labels that will be used in the WP admin area for our custom post type*/
    $labels = array(
        'name'                  => _x('Projects', 'Post type general name', 'our-project-vault'),
        'singular_name'         => _x('Project', 'Post type singular name', 'our-project-vault'),
        'menu_name'             => _x('Our Project Vault', 'Admin Menu text', 'our-project-vault'),
        'name_admin_bar'        => _x('Project', 'Add New on Toolbar', 'our-project-vault'),
        'add_new'               => __('Add New Project', 'our-project-vault'),
        'add_new_item'          => __('Add New Project', 'our-project-vault'),
        'new_item'              => __('New Project', 'our-project-vault'),
        'edit_item'             => __('Edit Project', 'our-project-vault'),
        'view_item'             => __('View Project', 'our-project-vault'),
        'all_items'             => __('All Projects', 'our-project-vault'),
        'search_items'          => __('Search Projects', 'our-project-vault'),
        'not_found'             => __('No projects found.', 'our-project-vault'),
        'not_found_in_trash'    => __('No projects found in Trash.', 'our-project-vault'),
    );

    /** Arguments Array: Defines the settings for our custom post types */
    $args = array(
        'labels'             => $labels, /**Link to the labels array above */
        'public'             => true, /** If public is set to true it makes post visible to the public */
        'has_archive'        => true, /** Accesible on the front end, allow archive pages and suport WP block editor is public is set to true*/
        'show_in_rest'       => true, /** Same as above */
        'supports'           => array('title', 'editor', 'custom-fields'), /** List features available for the post type */
        'menu_position'      => 5, /** */
        'menu_icon'          => 'dashicons-portfolio', /** */
        'rewrite'            => array('slug' => 'projects'), /** Defines  URL slug for the post type (ex. www.website.com/projects */
    );

    register_post_type('project', $args); /** This is a WP function used to register (create) a post on the site */
}
add_action('init', 'opv_register_project_cpt'); /** This is a hook and it connects our custom function to a specific point in the WP process using actions and filters */
/** In ths case the add_action hook tells WP to run our custom function during the init event */
/** This mean whenever someone visits the WP dashboard or any part of the site the entire function runs ans registers the CPT */

// Add custom meta boxes for additional project details
function opv_add_project_meta_boxes() { /** This function adds meta box to the CPT */
    add_meta_box( /** Add parameters */
        'opv_project_details', //Unique ID
        __('Project Details', 'our-project-vault'), //Title : will appear at the top of meta box
        'opv_render_project_meta_box', //Callback function not boxes box
        'project', //Post type : indicates meta boxes will only appear in this CPT (not on every post or page)
        'normal', //Context : Will display meta box of main area of edit screen and all context is together
        'default' //Priority : Determines the order of the meta box on the page
    );
}
add_action('add_meta_boxes', 'opv_add_project_meta_boxes');

/** This function outputs an HTML form for the custom meta box */
function opv_render_project_meta_box($post) { //Handles rendering of HTML for the fields
    // Retrieve current meta values
    $project_start_date = get_post_meta($post->ID, '_opv_project_start_date', true);
    $project_end_date = get_post_meta($post->ID, '_opv_project_end_date', true);
    $project_status = get_post_meta($post->ID, '_opv_project_status', true);
    //Note we are closing below to write a combination of HTML and PHP
    ?> 
    <label for="opv_project_start_date"><?php _e('Start Date', 'our-project-vault'); ?>:</label>
    <input type="date" id="opv_project_start_date" name="opv_project_start_date" value="<?php echo esc_attr($project_start_date); ?>" style="width: 100%; margin-bottom: 10px;" />
    
    <label for="opv_project_end_date"><?php _e('End Date', 'our-project-vault'); ?>:</label>
    <input type="date" id="opv_project_end_date" name="opv_project_end_date" value="<?php echo esc_attr($project_end_date); ?>" style="width: 100%; margin-bottom: 10px;" />
    
    <label for="opv_project_status"><?php _e('Project Status', 'our-project-vault'); ?>:</label>
    
    <select id="opv_project_status" name="opv_project_status" style="width: 100%; margin-bottom: 10px;">
        <option value="not_started" <?php selected($project_status, 'not_started'); ?>><?php _e('Not Yet Started', 'our-project-vault'); ?></option>
        <option value="in_progress" <?php selected($project_status, 'in_progress'); ?>><?php _e('In Progress', 'our-project-vault'); ?></option>
        <option value="complete" <?php selected($project_status, 'complete'); ?>><?php _e('Complete', 'our-project-vault'); ?></option>
    </select>

    <!-- Open PHP tag to transition between HTML to PHP again -->
    <?php
}

// Save custom meta box data
//This funtion ensures that data entered into the project start, project end and project status meta boxes are saved corectly
function opv_save_project_meta_box_data($post_id) {
    if (array_key_exists('opv_project_start_date', $_POST)) { //Check is data exists on the fields
        update_post_meta( //If values are found, function saves the meta info or updates the database
            $post_id,
            '_opv_project_start_date',
            sanitize_text_field($_POST['opv_project_start_date']) //Ensures input is sanitazed or does not have any unnecessary characters
        );
    }
    
    //By checking if data already existe we avoid duplicates in the database and ensure only relevant database is processed
    if (array_key_exists('opv_project_end_date', $_POST)) { //If data exist update with new value
        update_post_meta(
            $post_id,
            '_opv_project_end_date',
            sanitize_text_field($_POST['opv_project_end_date'])
        );
    }
    
    if (array_key_exists('opv_project_status', $_POST)) {
        update_post_meta(
            $post_id,
            '_opv_project_status',
            sanitize_text_field($_POST['opv_project_status'])
        );
    }
}
add_action('save_post', 'opv_save_project_meta_box_data');

//Now that we set up CPT and what it will ask, now we need to display this on the front end of our site
// Display project details on the front end
//This function is used to access the actual values that are input into our meta boxes & also displays on fronend of website
function opv_display_project_details($content) {
    if (is_singular('project')) { //Conditional check: checks if in current page is a single post or entry on our project CPT, remember custom plugin info only on CPT set up not every post on the website
        global $post; //Gives access to the current post or CPT
        
        //Retrieve custom field values: start date, end date and status
        $project_start_date = get_post_meta($post->ID, '_opv_project_start_date', true);
        $project_end_date = get_post_meta($post->ID, '_opv_project_end_date', true);
        $project_status = get_post_meta($post->ID, '_opv_project_status', true);

        //Status label array, this will come back with one selected value, not all of them
        $status_labels = array(
            'not_started' => __('Not Yet Started', 'our-project-vault'),
            'in_progress' => __('In Progress', 'our-project-vault'),
            'complete' => __('Complete', 'our-project-vault'),
        );

        //HTML block using custom content variables to display project details
        $custom_content = "<div class='opv-project-details'>";
        $custom_content .= "<p><strong>Start Date:</strong> " . esc_html($project_start_date) . "</p>";
        $custom_content .= "<p><strong>End Date:</strong> " . esc_html($project_end_date) . "</p>";
        $custom_content .= "<p><strong>Status:</strong> " . esc_html($status_labels[$project_status]) . "</p>";
        $custom_content .= "</div>";

        $content .= $custom_content; //Appends custom content to the main content post or what's in default WP editor section
    }
    return $content;
}
add_filter('the_content', 'opv_display_project_details');

function opv_enqueque_styles(){
    wp_enqueques_style('project-styles', plugin_dir_dir(__FILE__) . 'css/project-styles.css');
}

add_action('wp_enqueque_scripts', 'opv_enqueque_styles');
//NOW READY TO ACTIVATE PLUGIN