<?php

/**
 * Class Partials_Ar_Ad_Manager_Public_Banners
 */
class Partials_Ar_Ad_Manager_Public_Banners
{
    /**
     * @param $adzoneId
     * @param $device
     * @return array
     */
    public function getBanners($adzoneId, $device)
    {
        $bannerArgs = [
            'posts_per_page' => -1,
            'post_type' => \Ar_Ad_Manager_Admin::AR_AD_MANAGER_PREFIX . 'banners',
            'post_status' => 'publish',
            'meta_query' => [
                'relation' => 'AND',
                [
                    'key' => Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX . '_banner_is_active',
                    'value' => 'true',
                    'compare' => '='
                ],
                [
                    'key' => Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX . '_banner_linked_adzones',
                    'value' => $adzoneId,
                    'compare' => 'find_in_set'
                ]
            ]
        ];

        global $postId;

        if (!$postId) {
            return [];
        }

        $cats = get_the_category($postId);
        $catIds = $cats
            ? array_column($cats, 'term_id')
            : [];

        $query = new WP_Query($bannerArgs);
        $banners = $query->get_posts();
        $bannersResult = [];

        foreach ($banners as $banner) {
            // check for posts and categories
            $bannerPostIds = $banner->ar_ad_manager_extra_banner_posts;
            $bannerCategoryIds = $banner->ar_ad_manager_extra_banner_categories;

            if ($bannerPostIds) {
                $bannerPostIds = explode(',', $bannerPostIds);

                if (!in_array($postId, $bannerPostIds)) {
                    continue;
                }
            }

            if ($bannerCategoryIds) {
                $bannerCategoryIds = explode(',', $bannerCategoryIds);

                if (empty(array_intersect($bannerCategoryIds, $catIds))) {
                    continue;
                }
            }

            $sizes = [
                'desktop' => [
                    'is_hide' => $banner->ar_ad_manager_extra_desktop_is_hide_banner === 'true',
                    'image' => $banner->ar_ad_manager_extra_desktop_banner_default_image,
                    'width' => $banner->ar_ad_manager_extra_desktop_banner_width,
                    'height' => $banner->ar_ad_manager_extra_desktop_banner_height,
                    'script' => $banner->ar_ad_manager_extra_desktop_banner_script
                ],
                'tablet' => [
                    'is_hide' => $banner->ar_ad_manager_extra_tablet_is_hide_banner === 'true',
                    'image' => $banner->ar_ad_manager_extra_tablet_banner_default_image,
                    'width' => $banner->ar_ad_manager_extra_tablet_banner_width,
                    'height' => $banner->ar_ad_manager_extra_tablet_banner_height,
                    'script' => $banner->ar_ad_manager_extra_tablet_banner_script
                ],
                'mobile' => [
                    'is_hide' => $banner->ar_ad_manager_extra_mobile_is_hide_banner === 'true',
                    'image' => $banner->ar_ad_manager_extra_mobile_banner_default_image,
                    'width' => $banner->ar_ad_manager_extra_mobile_banner_width,
                    'height' => $banner->ar_ad_manager_extra_mobile_banner_height,
                    'script' => $banner->ar_ad_manager_extra_mobile_banner_script
                ],
            ];

            $bannerData = [
                'id' => $banner->ID,
                'title' => $banner->post_title,
                'advertiser' => $banner->ar_ad_manager_extra_banner_advertiser,
                'countries' => $banner->ar_ad_manager_extra_banner_countries,
                'weight' => $banner->ar_ad_manager_extra_banner_weight ?? 1,
                'link' => $banner->ar_ad_manager_extra_banner_link,
            ];

            switch ($device) {
                case 'mobile':
                    $priorityOrders = ['mobile', 'tablet', 'desktop'];

                    break;
                case 'tablet':
                    $priorityOrders = ['tablet', 'desktop'];

                    break;
                default:
                    $priorityOrders = ['desktop'];

                    break;
            }

            $currentDeviceValues = [];

            foreach ($priorityOrders as $priorityOrder) {
                if (isset($sizes[$priorityOrder])) {
                    foreach ($sizes[$priorityOrder] as $param => $value) {
                        if (!isset($currentDeviceValues[$param]) && $value !== '') {
                            $currentDeviceValues[$param] = $value;
                        }
                    }
                }
            }

            $bannersResult[$banner->ID] = array_merge($bannerData, $currentDeviceValues);
        }

        return $bannersResult;
    }

    /**
     * @param $banners
     * @param $countryCode
     * @return array|mixed
     */
    public function sortByAvailableCountries($banners, $countryCode)
    {
        if (!$countryCode) {
            return $banners;
        }

        $bannersResult = [];

        foreach ($banners as $banner) {
            if ($bannerCountries = $banner['countries']) {
                $bannerCountries = explode(',', $bannerCountries);

                if ($bannerCountries && !in_array($countryCode, $bannerCountries)) {
                    continue;
                }
            }

            $bannersResult[] = $banner;
        }

        return $bannersResult;
    }

    /**
     * @param $banners
     * @return array
     */
    public function sortByAvailableSize($banners)
    {
        $bannersResult = [];

        foreach ($banners as $bannerId => $banner) {
            if ($banner['is_hide']) {
                continue;
            }

            $bannersResult[$bannerId] = $banner;
        }

        return $bannersResult;
    }
}