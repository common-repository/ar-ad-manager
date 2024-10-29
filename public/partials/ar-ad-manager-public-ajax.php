<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) exit;
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class Partials_Ar_Ad_Manager_Public_Ajax
 */
class Partials_Ar_Ad_Manager_Public_Ajax
{
    public function __construct()
    {
        add_action('wp_ajax_ar_ad_managerzone_data', [$this, "ar_ad_manager_adzone_data"]);
        add_action('wp_ajax_nopriv_ar_ad_managerzone_data', [$this, "ar_ad_manager_adzone_data"]);
    }

    /**
     * @return string
     */
    public function ar_ad_manager_adzone_data()
    {
        global $postId;
        $adzoneIds = sanitize_text_field($_GET['adzone_ids']);
        $windowWidth = sanitize_text_field($_GET['window_width']);
        $postId = sanitize_text_field($_GET['post_id']);

        if (!$adzoneIds || !$windowWidth) {
            return '{}';
        }

        $userAgent = $_SERVER['HTTP_USER_AGENT'] ? sanitize_text_field($_SERVER['HTTP_USER_AGENT']) : '';

        // if BOT
        if (
            esc_html($userAgent)
            && preg_match('/bot|crawl|slurp|spider|mediapartners/i', esc_html($userAgent))
        ) {
            return '{}';
        }

        /** @var Partials_Ar_Ad_Manager_Public_Ads_Process $publicAdsProcess */
        global $publicAdsProcess;

        $adzoneIdsArray = explode(',', $adzoneIds);
        $adzoneResult = [];
        $advertisers = [];

        foreach ($adzoneIdsArray as $adzoneId) {
            try {
                $adzoneData = $publicAdsProcess->initAdzone($adzoneId, $windowWidth);

                if (!$adzoneData) {
                    throw new Exception('No data for this adzone: ' . $adzoneId);
                }

                if ($adzoneData['advertisers']) {
                    foreach ($adzoneData['advertisers'] as $advertiserId => $advertiser) {
                        $advertisers[$advertiserId] = $advertiser;
                    }
                }

                $adzoneResult[] = [
                    'id' => $adzoneId,
                    'data' => $adzoneData['html'],
                    'adzone_name' => $adzoneData['adzone_name'],
                    'banner_id' => $adzoneData['banner_id'],
                    'banner_name' => $adzoneData['banner_name'],
                    'country' => $adzoneData['country']
                ];
            } catch (Exception $e) {
                $adzoneResult[] = [
                    'id' => $adzoneId,
                    'country' => null
                ];
            }
        }

        $country = array_column($adzoneResult, 'country');
        $country = array_filter(array_unique($country));

        wp_send_json([
            'data' => [
                'id' => 1,
                'attributes' => [
                    'country' => $country
                ],
                'relationships' => [
                    'advertisers' => $advertisers ? array_values($advertisers) : [],
                    'adzones' => $adzoneResult
                ]
            ]
        ]);
    }
}

new Partials_Ar_Ad_Manager_Public_Ajax();