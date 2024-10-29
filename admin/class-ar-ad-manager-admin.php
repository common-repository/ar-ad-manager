<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/CoolS2/ar-ad-manager
 * @since      1.0.0
 *
 * @package    Ar_Ad_Manager
 * @subpackage Ar_Ad_Manager/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Ar_Ad_Manager
 * @subpackage Ar_Ad_Manager/admin
 * @author     Aleksandrs Reidzans <aleksandrs.reidzans@gmail.com>
 */
class Ar_Ad_Manager_Admin
{
    /**
     * @var string
     */
    public const AR_AD_MANAGER_PREFIX = 'ar-wp-ad-';
    public const AR_AD_MANAGER_PAGE_MENU_NAME = 'page_ar_ad_manager_advertising';

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $ar_ad_manager The ID of this plugin.
     */
    private $ar_ad_manager;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @param string $ar_ad_manager The name of this plugin.
     * @param string $version The version of this plugin.
     * @since    1.0.0
     */
    public function __construct($ar_ad_manager, $version)
    {
        $this->ar_ad_manager = $ar_ad_manager;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        global $current_screen;

        if (
            $current_screen
            && (in_array(
                    $current_screen->post_type,
                    ['ar-ad-manager-campaigns', 'ar-wp-ad-advertisers', 'ar-wp-ad-adzones', 'ar-wp-ad-banners']
                )
                || $current_screen->id === 'toplevel_page_page_ar_ad_manager_advertising')
        ) {
            wp_enqueue_style(
                $this->ar_ad_manager . '-material',
                plugin_dir_url(__FILE__) . 'mdl/material.indigo-blue.min.css',
                [],
                $this->version,
                'all'
            );

            wp_enqueue_style(
                $this->ar_ad_manager . '-selectize',
                plugin_dir_url(__FILE__) . 'css/selectize.default.min.css',
                [],
                $this->version,
                'all'
            );

            wp_enqueue_style(
                $this->ar_ad_manager . '-main',
                plugin_dir_url(__FILE__) . 'css/ar-ad-manager-admin.min.css',
                [],
                $this->version,
                'all'
            );
        }
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {
        global $current_screen;

        if (
            $current_screen
            && (in_array(
                    $current_screen->post_type,
                    ['ar-ad-manager-campaigns', 'ar-wp-ad-advertisers', 'ar-wp-ad-adzones', 'ar-wp-ad-banners']
                )
                || $current_screen->id === 'toplevel_page_page_ar_ad_manager_advertising')
        ) {
            wp_enqueue_script(
                $this->ar_ad_manager . 'material',
                plugin_dir_url(__FILE__) . 'mdl/material.min.js',
                [],
                $this->version,
                [
                    'in_footer' => true,
                    'strategy' => 'async',
                ]
            );

            wp_enqueue_script(
                $this->ar_ad_manager . 'selectize',
                plugin_dir_url(__FILE__) . 'js/selectize.min.js',
                ['jquery'],
                $this->version,
                [
                    'in_footer' => true,
                    'strategy' => 'async',
                ]
            );

            wp_enqueue_script(
                $this->ar_ad_manager . 'main_js',
                plugin_dir_url(__FILE__) . 'js/ar-ad-manager-admin.min.js',
                ['jquery'],
                $this->version,
                [
                    'in_footer' => true,
                    'strategy' => 'async',
                ]
            );

            wp_localize_script(
                $this->ar_ad_manager . 'main_js',
                'ar_ad_managermin_variables',
                [
                    'ajax_nonce' => wp_create_nonce( "ar-ad-manager-admin-ajax-nonce" )
                ]
            );

            wp_enqueue_media();
        }
    }

    /**
     * @return void
     */
    public function add_menu()
    {
        add_menu_page(
            esc_html__('Advertising', 'ar-ad-manager'),
            esc_html__('Advertising', 'ar-ad-manager'),
            'manage_options',
            Ar_Ad_Manager_Admin::AR_AD_MANAGER_PAGE_MENU_NAME,
            [$this, 'page_ar_ad_manager_advertising'],
            plugin_dir_url(__FILE__) . 'img/ar-ad-manager-logo.png',
            25
        );

        add_submenu_page(
            Ar_Ad_Manager_Admin::AR_AD_MANAGER_PAGE_MENU_NAME,
            esc_html__('Dashboard', 'ar-ad-manager'),
            esc_html__('Dashboard', 'ar-ad-manager'),
            'manage_options',
            Ar_Ad_Manager_Admin::AR_AD_MANAGER_PAGE_MENU_NAME,
            [$this, 'page_ar_ad_manager_advertising'],
            100
        );
    }

    /**
     * @return void
     */
    public function page_ar_ad_manager_advertising()
    {
        include(plugin_dir_path(__FILE__) . 'partials/ar-ad-manager-admin-dashboard.php');
    }

    /**
     * @return void
     */
    public function add_types_and_taxonomies()
    {
        include(plugin_dir_path(__FILE__) . 'partials/ar-ad-manager-types.php');

        $types = new Partials_Ar_Ad_Manager_Types();
        $types->registerPostTypes();
    }

    /**
     * @return void
     */
    public function add_meta_box()
    {
        include(plugin_dir_path(__FILE__) . 'partials/ar-ad-manager-meta-box-abstract.php');
        include(plugin_dir_path(__FILE__) . 'partials/ar-ad-manager-advertisers-meta-box.php');
        include(plugin_dir_path(__FILE__) . 'partials/ar-ad-manager-adzones-meta-box.php');
        include(plugin_dir_path(__FILE__) . 'partials/ar-ad-manager-banners-meta-box.php');
    }

    /**
     * @return void
     */
    public function add_ajax_class()
    {
        include(plugin_dir_path(__FILE__) . 'partials/ar-ad-manager-admin-ajax.php');
    }
}
