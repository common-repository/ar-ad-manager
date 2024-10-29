<?php

/**
 * Class Partials_Ar_Ad_Manager_Public_Advertisers
 */
class Partials_Ar_Ad_Manager_Public_Advertisers
{
    /**
     * @param $advertiserIds
     * @return array
     */
    public function getAvailableAdvertisers($advertiserIds)
    {
        $args = [
            'posts_per_page' => -1,
            'post_type' => \Ar_Ad_Manager_Admin::AR_AD_MANAGER_PREFIX . 'advertisers',
            'post_status' => 'publish',
            'post__in' => $advertiserIds
        ];

        $query = new WP_Query($args);
        $advertisers = $query->get_posts();
        $advertisersResult = [];

        foreach ($advertisers as $advertiser) {
            if (!$this->isAvailable($advertiser)) {
                continue;
            }

            $advertisersResult[$advertiser->ID] = [
                'script' => $advertiser->ar_ad_manager_extra_advertiser_script
            ];
        }

        return $advertisersResult;
    }

    /**
     * @param $advertiser
     * @return bool
     */
    private function isAvailable($advertiser)
    {
        if ($advertiser->ar_ad_manager_extra_is_advertiser_active !== 'true') {
            return false;
        }

        $now = current_time('timestamp');
        $dateStart = $advertiser->ar_ad_manager_extra_advertiser_start_date;
        $dateEnd = $advertiser->ar_ad_manager_extra_advertiser_end_date;

        if (!empty($dateStart) && $now < strtotime($dateStart)) {
            return false;
        }

        if (!empty($dateEnd) && $now > strtotime($dateEnd)) {
            return false;
        }

        // Filter on timing.
        $timingStart = $advertiser->ar_ad_manager_extra_advertiser_start_time;

        if (!empty($timingStart)) {
            $timingEnd = $advertiser->ar_ad_manager_extra_advertiser_end_time;

            if (
                str_replace(':', '', date_i18n('G:i', $now)) < str_replace(':', '', $timingStart)
                || str_replace(':', '', date_i18n('G:i', $now)) > str_replace(':', '', $timingEnd)
            ) {
                return false;
            }
        }

        // Filter on weekday
        $weekdayStart = $advertiser->ar_ad_manager_extra_advertiser_weekday_start;

        if (!empty($weekdayStart)) {
            $weekdayEnd = $advertiser->ar_ad_manager_extra_advertiser_weekday_end;
            $weekdayEnd = !empty($weekdayEnd)
                ? $weekdayEnd
                : $weekdayStart;

            $today = date_i18n('D', $now);

            $weekdays = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
            $days = [];
            $sd = 0;

            // Create an array $days including all days the ad should show.
            foreach ($weekdays as $day) {
                $sd = $weekdayStart == $day
                    ? 1
                    : $sd;

                if ($sd) {
                    $days[] = $day;
                }

                $sd = $weekdayEnd == $day
                    ? 0
                    : $sd;
            }

            if (!in_array($today, $days)) {
                return false;
            }
        }

        return true;
    }
}
