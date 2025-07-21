<?php
if (!defined('ABSPATH')) exit;

function ibf_handle_form_submission() {
    if (isset($_POST['ibf_submit']) && check_admin_referer('ibf_form_action', 'ibf_form_nonce')) {
        $name    = sanitize_text_field($_POST['ibf_name']);
        $email   = sanitize_email($_POST['ibf_email']);
        $mobile  = sanitize_text_field($_POST['ibf_mobile']);
        $message = sanitize_textarea_field($_POST['ibf_message']);

        $subject = 'New Contact Form Submission - Integration by Flexicon';
        $body = "You have received a new message from the Integration by Flexicon form:\n\n";
        $body .= "Name: $name\n";
        $body .= "Company Email: $email\n";
        $body .= "Mobile Number: $mobile\n";
        $body .= "Message:\n$message\n";

        $headers = ['Content-Type: text/plain; charset=UTF-8'];

        // Send email to fixed recipient
        wp_mail('sanjeevreddymudela@gmail.com', $subject, $body, $headers);

        // Also send to WordPress admin email
        $admin_email = get_option('admin_email');
        if ($admin_email && is_email($admin_email)) {
            wp_mail($admin_email, $subject, $body, $headers);
        }
    }
}
add_action('admin_init', 'ibf_handle_form_submission');
