<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) exit;
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class Partials_Ar_Ad_Manager_Public_Shortcodes
 */
class Partials_Ar_Ad_Manager_Public_Shortcodes
{
    public function __construct()
    {
        add_shortcode('ar_ad_manager_display_adzone', [$this, 'ar_ad_manager_display_adzone_shortcodes_process']);
    }

    /***
     * @param $attributes
     * @return string|void
     */
    public function ar_ad_manager_display_adzone_shortcodes_process($attributes)
    {
        if (!isset($attributes['id'])) {
            return;
        }

        $adzone = get_post($attributes['id']);

        if (!$adzone) {
            return;
        }

        /** @var Partials_Ar_Ad_Manager_Public_Ads_Process $publicAdsProcess */
        global $publicAdsProcess;

        /** @var Partials_Ar_Ad_Manager_Public_Adzones $publicAdzonesClass */
        global $publicAdzonesClass;

        $device = wp_is_mobile() ? 'mobile' : 'desktop';

        $adzone = $publicAdzonesClass->prepareAdzoneData($adzone, $device);

        if ($adzone['show_adzone_on_init'] && !$adzone['is_adzone_hide']) {
            $html = '<div class="ar-wp-happy-block-ajax ar-wp-happy-block-ajax-' . $adzone['id'] . '" data-happy-block-id="' . $adzone['id'] . '">';
            $html .= $publicAdsProcess->toHtml($adzone);
            $html .= '</div>';

            return $html;
        } else {
            return '<div class="ar-wp-happy-block-ajax ar-wp-happy-block-ajax-' . $adzone['id'] . '" data-happy-block-id="' . $adzone['id'] . '"></div>';
        }
    }
}

new Partials_Ar_Ad_Manager_Public_Shortcodes();