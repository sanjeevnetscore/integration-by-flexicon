<?php
/**
 * Plugin Name: Integration by Flexicon
 * Description: Integration by Flexicon is a lightweight contact form plugin that redirects to a custom plugin page after installation and sends the submitted form data (Name, Company Email, Mobile Number, Message) to the site admin and the submitter via email.
 * Version: 1.0.0
 * Author: netscoretechnologies
 * License: GPLv3 or later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Tags: e-commerce, integration, flexicon, inventory, product sync, online store
 * Requires at least: 5.0
 * Tested up to: 6.8
 * Requires PHP: 7.4
 */

if (!defined('ABSPATH')) exit;

define('IBF_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('IBF_PLUGIN_URL', plugin_dir_url(__FILE__));

function ibf_enqueue_assets() {
    wp_enqueue_style('ibf-style', IBF_PLUGIN_URL . 'assets/style.css');
}
add_action('admin_enqueue_scripts', 'ibf_enqueue_assets');
add_action('wp_enqueue_scripts', 'ibf_enqueue_assets');

require_once IBF_PLUGIN_DIR . 'includes/form-handler.php';

function ibf_add_admin_menu() {
    add_menu_page(
        'Integration by Flexicon',
        'Integration by Flexicon',
        'manage_options',
        'ibf-contact-form',
        'ibf_render_form_page',
        'dashicons-email',
        26
    );
}
add_action('admin_menu', 'ibf_add_admin_menu');

function ibf_activation_redirect() {
    add_option('ibf_do_activation_redirect', true);
}
register_activation_hook(__FILE__, 'ibf_activation_redirect');

function ibf_redirect_to_plugin_page() {
    if (get_option('ibf_do_activation_redirect', false)) {
        delete_option('ibf_do_activation_redirect');
        if (!isset($_GET['activate-multi'])) {
            wp_safe_redirect(admin_url('admin.php?page=ibf-contact-form'));
            exit;
        }
    }
}
add_action('admin_init', 'ibf_redirect_to_plugin_page');

function ibf_render_form_page() {
    ?>
    <div class="wrap">
        <h1>Integration by Flexicon</h1>
        <form method="post" class="ibf-form">
            <?php wp_nonce_field('ibf_form_action', 'ibf_form_nonce'); ?>
            <label for="ibf_name">Name</label>
            <input type="text" id="ibf_name" name="ibf_name" required>

            <label for="ibf_email">Company Email</label>
            <input type="email" id="ibf_email" name="ibf_email" required>

            <label for="ibf_mobile">Mobile Number</label>
            <input type="tel" id="ibf_mobile" name="ibf_mobile" required>

            <label for="ibf_message">Message</label>
            <textarea id="ibf_message" name="ibf_message" required></textarea>

            <button type="submit" name="ibf_submit">Submit</button>
        </form>
    </div>
    <?php
}
