<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://github.com/CoolS2/ar-ad-manager
 * @since      1.0.0
 *
 * @package    Ar_Ad_Manager
 * @subpackage Ar_Ad_Manager/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Ar_Ad_Manager
 * @subpackage Ar_Ad_Manager/public
 * @author     Aleksandrs Reidzans <aleksandrs.reidzans@gmail.com>
 */
class Ar_Ad_Manager_Public
{
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
     * @param string $ar_ad_manager The name of the plugin.
     * @param string $version The version of this plugin.
     * @since    1.0.0
     */
    public function __construct($ar_ad_manager, $version)
    {
        $this->ar_ad_manager = $ar_ad_manager;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        wp_enqueue_style(
            $this->ar_ad_manager,
            plugin_dir_url(__FILE__) . 'css/ar-ad-manager-public.css',
            [],
            $this->version,
            'all'
        );
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {
        wp_enqueue_script(
            $this->ar_ad_manager . '-main-public-js',
            plugin_dir_url(__FILE__) . 'js/ar-ad-manager-main.min.js',
            [],
            $this->version,
            [
                'in_footer' => true,
                'strategy' => 'async',
            ]
        );

        $isGoogleAnalyticsIsActive =
            get_option(Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX . "_is_google_analytics_active");
        $IsActiveLazyLoad =
            get_option(Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX . "_is_active_lazy_load");
        $isGoogleAnalyticsTrackId =
            get_option(Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX . "_google_analytic_track_id");

        $mainVariables = [
            'ajaxurl' => admin_url('admin-ajax.php'),
            'ga' => ($isGoogleAnalyticsIsActive && $isGoogleAnalyticsTrackId)
                ? $isGoogleAnalyticsTrackId
                : '',
            'isActiveLazyLoad' => $IsActiveLazyLoad === 'true',
            'post_id' => get_the_ID()
        ];

        wp_localize_script(
            $this->ar_ad_manager . '-main-public-js',
            'ar_wp_main_variables',
            $mainVariables
        );
    }

    /**
     * @return void
     */
    public function add_shortcodes()
    {
        global $publicBannerClass, $publicAdzonesClass, $publicAdvertisersClass, $publicAdsProcess;

        include(plugin_dir_path(__FILE__) . 'partials/ar-ad-manager-public-find-in-set.php');
        include(plugin_dir_path(__FILE__) . 'partials/ar-ad-manager-public-banners.php');
        include(plugin_dir_path(__FILE__) . 'partials/ar-ad-manager-public-adzones.php');
        include(plugin_dir_path(__FILE__) . 'partials/ar-ad-manager-public-advertisers.php');
        include(plugin_dir_path(__FILE__) . 'partials/ar-ad-manager-public-ads-process.php');

        $publicBannerClass = new Partials_Ar_Ad_Manager_Public_Banners();
        $publicAdzonesClass = new Partials_Ar_Ad_Manager_Public_Adzones();
        $publicAdvertisersClass = new Partials_Ar_Ad_Manager_Public_Advertisers();
        $publicAdsProcess = new Partials_Ar_Ad_Manager_Public_Ads_Process();

        include(plugin_dir_path(__FILE__) . 'partials/ar-ad-manager-public-shortcodes.php');
        include(plugin_dir_path(__FILE__) . 'partials/ar-ad-manager-public-ajax.php');
    }
}
