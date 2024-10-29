<?php

/**
 * Class Partials_Ar_Ad_Manager_Public_Ads_Process
 */
class Partials_Ar_Ad_Manager_Public_Ads_Process
{
    /**
     * @param $adzone
     * @param $banner
     * @return string
     */
    public function toHtml($adzone, $banner = [])
    {
        $html = '';

        $defaultAdzoneClass = get_option(Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX . "_default_adzone_class", '');
        $adzoneAlignStyles = '';

        if ($alignAdzoneValue = $adzone['adzone_align']) {
            $adzoneAlignStyles = 'display: flex;align-items: center;justify-content: '. $alignAdzoneValue .';';
            $html .= '<div style="display: flex;align-items: center;justify-content: '. $alignAdzoneValue .';">';

            switch ($alignAdzoneValue) {
                case 'start':
                    $adzoneAlignStyles .= 'text-align: left';
                    break;
                case 'end':
                    $adzoneAlignStyles .= 'text-align: right';
                    break;
                case 'center':
                    $adzoneAlignStyles .= 'text-align: center';
                    break;
                default:
                    // Do nothing
            }
        }

        $adzoneBlock = '<div';
        $adzoneClasses = [
            'ar-wp-happy-zone',
            'ar-wp-happy-zone-' . $adzone['id'],
            $adzone['adzone_css_class'],
            $defaultAdzoneClass
        ];

        $adzoneSize = $adzone['size'];

        if ($adzoneSize === 'custom') {
            $adzoneWidth = $adzone['custom_width'];
            $adzoneHeight = $adzone['custom_height'];
        } else {
            $adzoneSize = explode('x', $adzoneSize);
            $adzoneWidth = $adzoneSize[0] . 'px';
            $adzoneHeight = $adzoneSize[1] . 'px';
        }

        $adzoneMargin = $adzone['adzone_margin'] ? 'margin:' . $adzone['adzone_margin'] . 'px' : '';
        $adzoneBgc = $adzone['adzone_background_color'] ? 'background-color:' . $adzone['adzone_background_color'] : '';

        if ($adzone['is_adzone_transpared']) {
            $adzoneBgc = 'background-color: transparent';
        }

        $adzoneBgi = '';

        if ($adzoneBgiUrl = $adzone['adzone_default_image']) {
            $adzoneBgi = 'background-image: url(' . $adzoneBgiUrl . ')';
        }

        $adzoneStyles = [
            'overflow: hidden',
            'background-size: cover',
            'background-repeat: no-repeat',
            'width:' . $adzoneWidth,
            'height:' . $adzoneHeight,
            $adzoneMargin,
            $adzoneAlignStyles,
            $adzoneBgc,
            $adzoneBgi
        ];

        $adzoneClasses = array_filter($adzoneClasses);
        $adzoneStyles = array_filter($adzoneStyles);

        $adzoneAttributes = [
            'class' => implode(' ', $adzoneClasses),
            'style' => implode(';', $adzoneStyles)
        ];

        foreach ($adzoneAttributes as $attribute => $value) {
            $adzoneBlock .= ' ' . $attribute . '="' . $value . '"';
        }

        $adzoneBlock .= '>';
        $html .= $adzoneBlock;

        if ($adzone['adzone_text'] && !$banner) {
            $html .= '<span>' . $adzone['adzone_text'] . '</span>';
        }

        if ($banner) {
            $bannerHeight = 'height:100%';

            if (isset($banner['height']) && $banner['height']) {
                $bannerHeight = 'height:' . $banner['height'];
            }

            $bannerWidth = isset($banner['width']) && $banner['width'] ? 'width:' . $banner['width'] : '';

            $bannerStyles = [
                $bannerHeight,
                $bannerWidth
            ];

            $bannerClasses = [
                'ar-wp-happy-banner',
                'ar-wp-happy-banner-' . $banner['id']
            ];

            $bannerClasses = array_filter($bannerClasses);
            $bannerStyles = array_filter($bannerStyles);

            $bannerAttributes = [
                'class' => implode(' ', $bannerClasses),
                'style' => implode(';', $bannerStyles)
            ];

            $bannerBlock = '<div';

            foreach ($bannerAttributes as $attribute => $value) {
                $bannerBlock .= ' ' . $attribute . '="' . $value . '"';
            }

            $bannerBlock .= '>';

            $html .= $bannerBlock;

            if ($banner['script']) {
                $html .= $banner['script'];
            } else if ($banner['image']) {
                $imageAttributes = [
                    'src' => $banner['image']
                ];

                if ($banner['width']) {
                    $imageAttributes['width'] = $banner['width'];
                }

                if ($banner['height']) {
                    $imageAttributes['height'] = $banner['height'];
                }

                $img = '<img style="max-height:100%;width:auto;"';

                foreach ($imageAttributes as $attribute => $value) {
                    $img .= ' ' . $attribute . '="' . $value . '"';
                }

                $img .= '/>';

                if ($banner['link']) {
                    $html .= '<a href="' . $banner['link'] . '" target="_blank">';
                } else if ($adzone['adzone_default_link']) {
                    $html .= '<a href="' . $adzone['adzone_default_link'] . '" target="_blank">';
                }

                $html .= $img;

                if ($banner['link'] || $adzone['adzone_default_link']) {
                    $html .= '</a>';
                }
            }

            $html .= '</div>';
        }

        $html .= '</div>';

        if ($adzone['adzone_align']) {
            $html .= '</div>';
        }

        return $html;
    }

    /**
     * @param $adzoneId
     * @param $windowWidth
     * @return array|null
     * @throws Exception
     */
    public function initAdzone($adzoneId, $windowWidth)
    {
        $adzone = get_post($adzoneId);

        if (!$adzone) {
            return null;
        }

        /** @var Partials_Ar_Ad_Manager_Public_Adzones $publicAdzonesClass */
        global $publicAdzonesClass;

        $device = $publicAdzonesClass->getCurrentDevice($windowWidth);
        $adzone = $publicAdzonesClass->prepareAdzoneData($adzone, $device);

        if ($adzone['is_adzone_hide']) {
            return null;
        }

        $availableBannersData = $this->getAvailableBanners($adzoneId, $device);
        $availableAdvertisers = $availableBannersData['availableAdvertisers'] ?? [];
        $availableBanners = $availableBannersData['availableBanners'] ?? [];
        $countryCode = $availableBannersData['countryCode'] ?? null;

        $availableCount = count($availableBanners);
        $winnerBanner = [];

        if (!$availableCount) {
            $html = $adzone['hide_adzone_if_empty']
                ? null
                : $this->toHtml($adzone);
        } else if ($availableCount === 1) {
            $winnerBanner = $availableBanners[0];
            $html = $this->toHtml($adzone, $availableBanners[0]);
        } else {
            $winnerBanner = $this->getByWeightRandom($availableBanners);
            $html = $this->toHtml($adzone, $winnerBanner);
        }

        return [
            'advertisers' => $availableAdvertisers,
            'html' => $html,
            'adzone_name' => $adzone['title'],
            'banner_id' => $winnerBanner ? $winnerBanner['id'] : '',
            'banner_name' => $winnerBanner ? $winnerBanner['title'] : '',
            'country' => $countryCode
        ];
    }

    /**
     * @param array $banners
     * @return mixed
     * @throws Exception
     */
    public function getByWeightRandom($banners)
    {
        $bannerWeightValues = array_column($banners, 'weight');
        $totalWeight = array_sum($bannerWeightValues);
        $totalWeight = $totalWeight
            ?: 1;

        $selection = random_int(1, $totalWeight);
        $count = 0;

        foreach ($banners as $bannerId => $value) {
            $weight = $value['weight'];
            $chosen = $bannerId;
            $count += $weight;

            if ($count >= $selection) {
                break;
            }
        }

        return isset($chosen)
            ? $banners[$chosen]
            : $banners[0];
    }

    /**
     * @param $adzoneId
     * @param $device
     * @return array
     */
    public function getAvailableBanners($adzoneId, $device)
    {
        global $publicBannerClass, $publicAdvertisersClass;

        /** @var Partials_Ar_Ad_Manager_Public_Banners $publicBannerClass */
        $bannersResult = $publicBannerClass->getBanners($adzoneId, $device);

        if (!$bannersResult) {
            return [];
        }

        $bannersResult = $publicBannerClass->sortByAvailableSize($bannersResult);
        $advertiserIds = array_column($bannersResult, 'id', 'advertiser');

        /** @var Partials_Ar_Ad_Manager_Public_Advertisers $publicAdvertisersClass */
        $availableAdvertisers = $publicAdvertisersClass->getAvailableAdvertisers(array_keys($advertiserIds));

        if (!$availableAdvertisers) {
            return [];
        }

        // Availability by advertiser
        $availableBanners = [];

        foreach ($availableAdvertisers as $availableAdvertiserId => $availableAdvertisersData) {
            foreach ($bannersResult as $banner) {
                if ((int)$banner['advertiser'] === (int)$availableAdvertiserId) {
                    $availableBanners[] = $banner;
                }
            }
        }

        $countryCode = 'not set';

        // Remove unused advertisers
        $advertiserIdsFromBanner = array_column($availableBanners, 'advertiser');
        $advertiserIdsFromBanner = array_unique($advertiserIdsFromBanner);

        foreach ($availableAdvertisers as $advertiserId => $advertiserData) {
            if (!in_array($advertiserId, $advertiserIdsFromBanner)) {
                unset($availableAdvertisers[$advertiserId]);
            }
        }

        return [
            'availableAdvertisers' => $availableAdvertisers,
            'availableBanners' => $availableBanners,
            'countryCode' => $countryCode
        ];
    }

    /**
     * @return mixed|null
     */
    public function getClientIp()
    {
        $ip = null;

        foreach ([
                     'HTTP_CLIENT_IP',
                     'HTTP_X_FORWARDED_FOR',
                     'HTTP_X_FORWARDED',
                     'HTTP_X_CLUSTER_CLIENT_IP',
                     'HTTP_FORWARDED_FOR',
                     'HTTP_FORWARDED',
                     'REMOTE_ADDR'
                 ] as $key) {
            if (array_key_exists($key, $_SERVER) === true && $_SERVER[$key]) {
                foreach (array_map('trim', explode(',', $_SERVER[$key])) as $ip) {
                    if (
                        filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)
                        !== false
                    ) {
                        return sanitize_text_field($ip);
                    }
                }
            }
        }

        return $ip;
    }
}
