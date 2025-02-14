<?php
/** HERE DEFINE CONTACT METADATA
 * Plugin Name: Contacts for Everyone
 * Description: Simple contact form plugin for beginners.
 * Version: 1.0
 * Author: Alice Gonzalez
 * Author URI: www.tekcodees.com 
 * Text Domain: contacts_for_everyone
 * For a list of plugin header requirements: https://developer.wordpress.org/plugins/plugin-basics/header-requirements/
 */

 //SECURITY CHECK USED FOR WP PLUGINS
 //This code ensures that the plugin can only be run within WP
 if(!defined('ABSPATH')){
    exit;
 }

 // LINK CCS FILE
 function cfe_enqueue_styles(){
    wp_enqueue_style('cfe-styles', plugin_dir_url(__FILE__) . 'css/contact-form.css');
 }

 add_action('wp_enqueue_scripts', 'cfe_enqueue_styles');

 //FUNCTION TO REGISTER SHORTCODE OF THE PLUGIN
 //Shortcode is how we will display the contact form on WP pages also using shortcode block
 function cfe_contact_form_shortcode(){
    ob_start();
    include plugin_dir_path(__FILE__) . 'contact_form.php';
    return ob_get_clean();
 }
 //This is where we name the shortcode to pull into the pages and make form appear
 add_shortcode('cfe_contact_form', 'cfe_contact_form_shortcode');