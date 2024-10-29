<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) exit;
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class Partials_Ar_Ad_Manager_Admin_Ajax
 */
class Partials_Ar_Ad_Manager_Admin_Ajax
{
    public function __construct()
    {
        add_action('wp_ajax_ar_ad_manager_grid_status_toggle', [$this, "ar_ad_manager_grid_status_toggle"]);
        add_action('wp_ajax_ar_ad_manager_grid_adzone_change', [$this, "ar_ad_manager_grid_adzone_change"]);
    }

    /**
     * @return void
     */
    public function ar_ad_manager_grid_adzone_change()
    {
        if (!current_user_can('edit_posts')) {
            return;
        }

        $nonce = $_POST['security'] ? sanitize_text_field($_POST['security']) : null;

        if (!wp_verify_nonce($nonce, 'ar-ad-manager-admin-ajax-nonce')) {
            return;
        }

        $postId = $_POST['post_id'] ? sanitize_text_field($_POST['post_id']) : null;
        $value = $_POST['value'] ? sanitize_text_field($_POST['value']) : null;

        if (!$postId) {
            return;
        }

        update_post_meta(
            esc_html($postId),
            \Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX . '_banner_linked_adzones',
            esc_html($value)
        );
    }

    /**
     * @return void
     */
    public function ar_ad_manager_grid_status_toggle()
    {
        if (!current_user_can('edit_posts')) {
            return;
        }

        $nonce = $_POST['security'] ? sanitize_text_field($_POST['security']) : null;

        if (!wp_verify_nonce($nonce, 'ar-ad-manager-admin-ajax-nonce')) {
            return;
        }

        $postId = $_POST['post_id'] ? sanitize_text_field($_POST['post_id']) : null;
        $field = $_POST['field'] ? sanitize_text_field($_POST['field']) : null;
        $isActive = $_POST['is_active'] ? sanitize_text_field($_POST['is_active']) : null;

        if (!$postId || !$isActive || !$field) {
            return;
        }

        update_post_meta(
            esc_html($postId),
            \Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX . '_' . esc_html($field),
            esc_html($isActive)
        );
    }
}

new Partials_Ar_Ad_Manager_Admin_Ajax();