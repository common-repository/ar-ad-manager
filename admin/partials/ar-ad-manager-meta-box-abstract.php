<?php

/**
 * Class Partials_Ar_Ad_Manager_Campaigns_Meta_Box
 */
abstract class Partials_Ar_Ad_Manager_Meta_Box_Abstract
{
    /**
     * @var string
     */
    public const AR_AD_MANAGER_FIELD_PREFIX = 'ar_ad_manager_extra';
    protected const AR_AD_MANAGER_FIELD_NONCE = 'ar_ad_manager_extra_nonce';

    /**
     * Constructor
     */
    public function __construct()
    {
        add_action('add_meta_boxes', [$this, 'add_meta_box']);
        add_action('save_post', [$this, 'save']);
    }

    /**
     * @param $post_type
     * @return mixed
     */
    abstract public function add_meta_box($post_type);

    /**
     * @param $post
     * @return mixed
     */
    abstract public function render_meta_box_content($post);

    /**
     * @param $post_id
     * @return false|mixed
     */
    public function save($post_id)
    {
        if (
            !isset($_POST[\Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX . '_fields_nonce'])
            || !wp_verify_nonce(
                sanitize_text_field(
                    wp_unslash($_POST[\Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX . '_fields_nonce'])
                ),
                \Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_NONCE
            )
        ) {
            return false;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
            return false;

        if (!current_user_can('edit_post', $post_id))
            return false;

        if (!isset($_POST[\Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX]))
            return false;

        $postValues = $_POST[\Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX];

        if (!is_array($postValues)) {
            return false;
        }

        // multiple select empty fix
        if (isset($postValues['banner_is_active']) && !isset($postValues['banner_linked_adzones'])) {
            $postValues['banner_linked_adzones'] = [];
        }

        if (isset($postValues['banner_is_active']) && !isset($postValues['banner_countries'])) {
            $postValues['banner_countries'] = [];
        }

        foreach ($postValues as $key => $value) {
            if (is_array($value)) {
                $postValues[$key] = implode(',', $value);
            }

            $postValues[$key] = esc_html(sanitize_text_field($postValues[$key]));
        }

        $postValues = array_map(
            'trim',
            $postValues
        );

        foreach ($postValues as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            if (empty($value)) {
                delete_post_meta($post_id, \Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX . '_' . $key);
                continue;
            }

            update_post_meta($post_id, \Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX . '_' . $key, $value);
        }

        return $post_id;
    }
}