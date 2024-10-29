<?php
/**
 * @since      1.0.0
 *
 * @package    Ar_Ad_Manager
 * @subpackage Ar_Ad_Manager/admin/partials
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) exit;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (
        isset($_POST['ar_ad_manager_extra_fields_nonce'])
        && isset($_POST['ar_ad_manager_extra'])
        && wp_verify_nonce(
            sanitize_text_field(
                wp_unslash($_POST['ar_ad_manager_extra_fields_nonce'])
            ),
            'ar_ad_manager_extra_nonce'
        )
    ) {
        foreach ($_POST['ar_ad_manager_extra'] as $key => $value) {
            $sanitizedValue = sanitize_text_field($value);

            update_option(Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX . '_' . $key, esc_html($value));
        }
    }
}
?>

<div class="wrap">
    <div id="poststuff">
        <div id="post-body">
            <div id="postbox-container-1" class="postbox-container">
                <div id="normal-sortables" class="meta-box-sortables ui-sortable">
                    <div class="postbox">
                        <div class="postbox-header">
                            <h2 class="hndle ui-sortable-handle"><?php echo esc_html__('Dashboard', 'ar-ad-manager'); ?></h2>
                        </div>
                        <div class="inside">
                            <form action="" method="post" enctype="multipart/form-data">
                                <div class="ar-ad-manager-box">
                                    <div class="mdl-grid">
                                        <div class="mdl-cell mdl-cell--2-col mdl-cell--12-col-tablet">
                                            <h4><?php echo esc_html__('Adzone Class', 'ar-ad-manager');?></h4>
                                            <p><?php echo esc_html__('The default adzone class name.', 'ar-ad-manager');?></p>
                                        </div>
                                        <div class="mdl-cell mdl-cell--6-col mdl-cell--12-col-tablet">
                                            <?php $defaultAdzoneClass = get_option(Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX . "_default_adzone_class", ''); ?>

                                            <div class="mdl-textfield mdl-js-textfield">
                                                <input
                                                    class="mdl-textfield__input"
                                                    type="text"
                                                    name="<?php echo esc_html(\Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX); ?>[default_adzone_class]"
                                                    id="banner_default_image"
                                                    placeholder="<?php echo esc_html__('Default adzone class', 'ar-ad-manager');?>"
                                                    value="<?php echo esc_html($defaultAdzoneClass); ?>"
                                                >
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mdl-grid">
                                        <div class="mdl-cell mdl-cell--2-col mdl-cell--12-col-tablet">
                                            <h4><?php echo esc_html__('Enable Statistics', 'ar-ad-manager');?></h4>
                                            <p><?php echo esc_html__('Do you want to send statistics to Google Analytics?', 'ar-ad-manager');?></p>
                                        </div>
                                        <div class="mdl-cell mdl-cell--6-col mdl-cell--12-col-tablet">
                                            <?php $isGoogleAnalyticsIsActive = get_option(Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX . "_is_google_analytics_active"); ?>

                                            <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="is_google_analytics_active">
                                                <input
                                                    type="checkbox"
                                                    id="is_google_analytics_active"
                                                    class="mdl-switch__input"
                                                    name="<?php echo esc_html(\Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX); ?>[is_google_analytics_active]"
                                                    <?php echo ($isGoogleAnalyticsIsActive === 'true') ? 'checked' : ''; ?>
                                                >
                                                <span class="mdl-switch__label"></span>
                                            </label>
                                        </div>

                                        <input
                                            type="hidden"
                                            id="is_google_analytics_active-hidden"
                                            name="<?php echo esc_html(\Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX); ?>[is_google_analytics_active]"
                                            value="<?php echo esc_html($isGoogleAnalyticsIsActive); ?>"
                                        >
                                    </div>

                                    <div class="mdl-grid">
                                        <div class="mdl-cell mdl-cell--2-col mdl-cell--12-col-tablet">
                                            <h4><?php echo esc_html__('Google Analytics track ID', 'ar-ad-manager');?></h4>
                                            <p><?php echo esc_html__('The default adzone class name.', 'ar-ad-manager');?></p>
                                        </div>
                                        <div class="mdl-cell mdl-cell--6-col mdl-cell--12-col-tablet">
                                            <?php $googleAnalyticTrackId = get_option(Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX . "_google_analytic_track_id", ''); ?>

                                            <div class="mdl-textfield mdl-js-textfield">
                                                <input
                                                    class="mdl-textfield__input"
                                                    type="text"
                                                    name="<?php echo esc_html(\Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX); ?>[google_analytic_track_id]"
                                                    id="banner_default_image"
                                                    placeholder="G-*********"
                                                    value="<?php echo esc_html($googleAnalyticTrackId); ?>"
                                                >
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mdl-grid">
                                        <button class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored" type="submit">
                                            <?php echo esc_html__('Save General Settings', 'ar-ad-manager');?>
                                        </button>
                                    </div>
                                </div>

                                <input
                                    type="hidden"
                                    name="<?php echo esc_html(\Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX); ?>_fields_nonce"
                                    value="<?php echo esc_html(wp_create_nonce('ar_ad_manager_extra_nonce')); ?>"
                                />
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
