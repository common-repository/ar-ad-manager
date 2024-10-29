<?php

/**
 * Class Partials_Ar_Ad_Manager_Public_Adzones
 */
class Partials_Ar_Ad_Manager_Public_Adzones
{
    /**
     * @param $adzone
     * @param $device
     * @return array
     */
    public function prepareAdzoneData($adzone, $device)
    {
        $sizes = [
            'desktop' => [
                'size' => $adzone->ar_ad_manager_extra_desktop_adzone_size,
                'custom_width' => $adzone->ar_ad_manager_extra_desktop_adzone_width,
                'custom_height' => $adzone->ar_ad_manager_extra_desktop_adzone_height,
                'is_adzone_hide' => $adzone->ar_ad_manager_extra_desktop_is_adzone_hide === 'true',
                'show_adzone_on_init' => $adzone->ar_ad_manager_extra_desktop_show_adzone_on_init === 'true',
            ],
            'tablet' => [
                'size' => $adzone->ar_ad_manager_extra_tablet_adzone_size,
                'custom_width' => $adzone->ar_ad_manager_extra_tablet_adzone_width,
                'custom_height' => $adzone->ar_ad_manager_extra_tablet_adzone_height,
                'is_adzone_hide' => $adzone->ar_ad_manager_extra_tablet_is_adzone_hide === 'true',
                'show_adzone_on_init' => $adzone->ar_ad_manager_extra_tablet_show_adzone_on_init === 'true',
            ],
            'mobile' => [
                'size' => $adzone->ar_ad_manager_extra_mobile_adzone_size,
                'custom_width' => $adzone->ar_ad_manager_extra_mobile_adzone_width,
                'custom_height' => $adzone->ar_ad_manager_extra_mobile_adzone_height,
                'is_adzone_hide' => $adzone->ar_ad_manager_extra_mobile_is_adzone_hide === 'true',
                'show_adzone_on_init' => $adzone->ar_ad_manager_extra_mobile_show_adzone_on_init === 'true',
            ]
        ];

        $adzone = [
            'id' => $adzone->ID,
            'title' => $adzone->post_title,
            'adzone_align' => $adzone->ar_ad_manager_extra_adzone_align,
            'is_adzone_transpared' => $adzone->ar_ad_manager_extra_is_adzone_transparent === 'true',
            'hide_adzone_if_empty' => $adzone->ar_ad_manager_extra_hide_adzone_if_empty === 'true',
            'adzone_css_class' => $adzone->ar_ad_manager_extra_adzone_css_class,
            'adzone_text' => $adzone->ar_ad_manager_extra_adzone_text,
            'adzone_margin' => $adzone->ar_ad_manager_extra_adzone_margin,
            'adzone_background_color' => $adzone->ar_ad_manager_extra_adzone_background_color,
            'adzone_default_image' => $adzone->ar_ad_manager_extra_adzone_default_image,
            'adzone_default_link' => $adzone->ar_ad_manager_extra_adzone_default_link
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

        return array_merge($adzone, $currentDeviceValues);
    }

    /**
     * @param $windowWidth
     * @return string
     */
    public function getCurrentDevice($windowWidth)
    {
        $windowWidth = (int)$windowWidth;
        $device = 'desktop';

        if ($windowWidth < 968) {
            $device = 'tablet';
        }

        if ($windowWidth < 768) {
            $device = 'mobile';
        }

        return $device;
    }
}