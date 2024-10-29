<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) exit;
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class Partials_Ar_Ad_Manager_Advertisers_Meta_Box
 */
class Partials_Ar_Ad_Manager_Advertisers_Meta_Box extends \Partials_Ar_Ad_Manager_Meta_Box_Abstract
{
    /**
     * @param $post_type
     * @return mixed|void
     */
    public function add_meta_box($post_type)
    {
        $post_types = [\Ar_Ad_Manager_Admin::AR_AD_MANAGER_PREFIX . 'advertisers'];

        if (in_array($post_type, $post_types)) {
            add_meta_box(
                \Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX . '_advertiser_options',
                esc_html__('Advertiser Options', 'ar-ad-manager'),
                [$this, 'render_meta_box_content'],
                $post_type,
                'normal',
                'high'
            );
        }
    }

    /**
     * @param $post
     * @return mixed|void
     */
    public function render_meta_box_content($post)
    {
        ?>
        <?php $activeValue = get_post_meta($post->ID, Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX . "_is_advertiser_active", true); ?>
        <div class="ar-ad-manager-box">
            <div class="mdl-grid">
                <div class="mdl-cell mdl-cell--2-col mdl-cell--12-col-tablet">
                    <h4><?php echo esc_html__('Is active', 'ar-ad-manager');?></h4>
                    <p><?php echo esc_html__('Disable all banners within the current advertiser', 'ar-ad-manager');?></p>
                </div>
                <div class="mdl-cell mdl-cell--6-col mdl-cell--12-col-tablet">
                    <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="is-active">
                        <input
                            type="checkbox"
                            id="is-active"
                            class="mdl-switch__input"
                            name="<?php echo \Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX; ?>[is_advertiser_active]"
                            <?php echo ($activeValue === 'true') ? 'checked' : ''; ?>
                        >
                        <span class="mdl-switch__label"></span>
                    </label>
                </div>

                <input
                    type="hidden"
                    id="is-active-hidden"
                    name="<?php echo \Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX; ?>[is_advertiser_active]"
                    value="<?php echo $activeValue; ?>"
                >
            </div>

            <div class="mdl-grid">
                <div class="mdl-cell mdl-cell--2-col mdl-cell--12-col-tablet">
                    <h4><?php echo esc_html__('Script', 'ar-ad-manager');?></h4>
                    <p><?php echo esc_html__('The main script for initializing advertising, use only the link, without the tag.', 'ar-ad-manager');?></p>
                </div>
                <div class="mdl-cell mdl-cell--6-col mdl-cell--12-col-tablet">
                    <input
                        type="text"
                        id="advertiser_script"
                        name="<?php echo \Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX; ?>[advertiser_script]"
                        value="<?php echo get_post_meta($post->ID, Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX . "_advertiser_script", true); ?>"
                        style="width: 100%;"
                    />
                </div>
            </div>

            <div class="mdl-grid">
                <div class="mdl-cell mdl-cell--2-col mdl-cell--12-col-tablet">
                    <h4><?php echo esc_html__('Period', 'ar-ad-manager');?></h4>
                    <p><?php echo esc_html__('Add a start and end date', 'ar-ad-manager');?></p>
                </div>
                <div class="mdl-cell mdl-cell--6-col mdl-cell--12-col-tablet">
                    <input
                        type="date"
                        id="start-date"
                        name="<?php echo \Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX; ?>[advertiser_start_date]"
                        value="<?php echo get_post_meta($post->ID, Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX . "_advertiser_start_date", true); ?>"
                        min="<?php echo date('Y-m-d'); ?>"
                    />
                    <input
                        type="date"
                        id="end-date"
                        name="<?php echo \Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX; ?>[advertiser_end_date]"
                        value="<?php echo get_post_meta($post->ID, Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX . "_advertiser_end_date", true); ?>"
                        min="<?php echo date('Y-m-d'); ?>"
                    />

                    <div class="note"><?php echo esc_html__('Leave empty to keep running all the time.', 'ar-ad-manager');?></div>
                </div>
            </div>

            <div class="mdl-grid">
                <div class="mdl-cell mdl-cell--2-col mdl-cell--12-col-tablet">
                    <h4><?php echo esc_html__('Timing', 'ar-ad-manager');?></h4>
                    <p><?php echo esc_html__('Run between specific times only.', 'ar-ad-manager');?></p>
                </div>
                <div class="mdl-cell mdl-cell--6-col mdl-cell--12-col-tablet">
                    <input
                        type="time"
                        id="start-time"
                        name="<?php echo \Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX; ?>[advertiser_start_time]"
                        value="<?php echo get_post_meta($post->ID, Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX . "_advertiser_start_time", true); ?>"
                        min="00:00"
                        max="23:59"
                    />
                    <input
                        type="time"
                        id="end-time"
                        name="<?php echo \Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX; ?>[advertiser_end_time]"
                        value="<?php echo get_post_meta($post->ID, Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX . "_advertiser_end_time", true); ?>"
                        min="00:00"
                        max="23:59"
                    />

                    <div class="note"><?php echo esc_html__('Leave empty to keep running all the time.', 'ar-ad-manager');?></div>
                </div>
            </div>

            <div class="mdl-grid">
                <div class="mdl-cell mdl-cell--2-col mdl-cell--12-col-tablet">
                    <h4><?php echo esc_html__('Weekday', 'ar-ad-manager');?></h4>
                    <p><?php echo esc_html__('Run on specific weekdays only.', 'ar-ad-manager');?></p>
                </div>
                <div class="mdl-cell mdl-cell--6-col mdl-cell--12-col-tablet">
                    <?php $weekDays = [
                        'Monday' => 'Mon',
                        'Tuesday' => 'Tue',
                        'Wednesday' => 'Wed',
                        'Thursday' => 'Thu',
                        'Friday' => 'Fri',
                        'Saturday' => 'Sat',
                        'Sunday' => 'Sun'
                    ]; ?>
                    <?php $weekDayStartValue = get_post_meta($post->ID, Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX . "_advertiser_weekday_start", true); ?>
                    <?php $weekDayEndValue = get_post_meta($post->ID, Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX . "_advertiser_weekday_end", true); ?>

                    <select
                        name="<?php echo esc_html(\Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX); ?>[advertiser_weekday_start]"
                        id="weekday_start"
                    >
                        <option value=""><?php echo esc_html__('Select a start weekday - (empty)', 'ar-ad-manager'); ?></option>

                        <?php foreach ($weekDays as $weekDay => $weekVal): ?>
                            <option value="<?php echo esc_html($weekVal); ?>" <?php echo ($weekDayStartValue === $weekVal) ? 'selected' : ''; ?>>
                                <?php echo esc_html($weekDay); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <select
                        name="<?php echo esc_html(\Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX); ?>[advertiser_weekday_end]"
                        id="weekday_end"
                    >
                        <option value=""><?php echo esc_html__('Select an end weekday - (empty)', 'ar-ad-manager');?></option>

                        <?php foreach ($weekDays as $weekDay => $weekVal): ?>
                            <option value="<?php echo esc_html($weekVal); ?>" <?php echo ($weekDayEndValue === $weekVal) ? 'selected' : ''; ?>>
                                <?php echo esc_html($weekDay); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="note"><?php echo esc_html__('Leave empty to keep running all the time.', 'ar-ad-manager');?></div>
                </div>
            </div>
        </div>
        <input
            type="hidden"
            name="<?php echo esc_html(\Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX); ?>_fields_nonce"
            value="<?php echo esc_html(wp_create_nonce(\Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_NONCE)); ?>"
        />
        <?php
    }
}

new Partials_Ar_Ad_Manager_Advertisers_Meta_Box();