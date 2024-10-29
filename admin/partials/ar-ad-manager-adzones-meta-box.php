<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) exit;
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class Partials_Ar_Ad_Manager_Adzones_Meta_Box
 */
class Partials_Ar_Ad_Manager_Adzones_Meta_Box extends \Partials_Ar_Ad_Manager_Meta_Box_Abstract
{
    /**
     * @param $post_type
     * @return mixed|void
     */
    public function add_meta_box($post_type)
    {
        $post_types = [\Ar_Ad_Manager_Admin::AR_AD_MANAGER_PREFIX . 'adzones'];

        if (in_array($post_type, $post_types)) {
            add_meta_box(
                \Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX . '_adzone_size_options',
                esc_html__('Adzone Size', 'ar-ad-manager'),
                [$this, 'render_meta_box_content_size'],
                $post_type,
                'normal',
                'high'
            );
            add_meta_box(
                \Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX . '_adzone_options',
                esc_html__('Adzone Options', 'ar-ad-manager'),
                [$this, 'render_meta_box_content'],
                $post_type,
                'normal',
                'high'
            );
        }
    }

    /**
     * @param $post
     * @return void
     */
    public function render_meta_box_content_size($post)
    {
        wp_enqueue_style(
            'ar-ad-manager-google-fonts-styles',
            'https://fonts.googleapis.com/icon?family=Material+Icons',
            [],
            AR_AD_MANAGER_VERSION,
            false
        );
        
        $sizes = [
            'desktop' => '<span class="material-icons">computer</span>',
            'tablet' => '<span class="material-icons">tablet_android</span>',
            'mobile' => '<span class="material-icons">phone_android</span>'
        ];
        $adSizes = ['120x60', '120x90', '120x600', '125x125', '160x600', '180x150', '300x250', '468x60', '728x90', 'custom'];
        ?>

        <div class="ar-ad-manager-box">
            <div class="mdl-tabs mdl-js-tabs mdl-js-ripple-effect">
                <div class="mdl-tabs__tab-bar">
                    <?php foreach ($sizes as $size => $icon): ?>
                        <a href="#panel-<?php echo esc_html($size); ?>" class="mdl-tabs__tab <?php echo $size === 'desktop' ? 'is-active' : ''; ?>">
                            <span class="icon"><?php echo $icon; ?></span>
                            <span class="name"><?php echo esc_html($size); ?></span>
                        </a>
                    <?php endforeach; ?>
                </div>

                <?php foreach ($sizes as $size => $icon): ?>
                    <div class="mdl-tabs__panel <?php echo $size === 'desktop' ? 'is-active' : ''; ?>" id="panel-<?php echo esc_html($size); ?>">
                        <div class="mdl-grid">
                            <div class="mdl-cell mdl-cell--2-col mdl-cell--12-col-tablet">
                                <h4><?php echo esc_html__('Adzone size', 'ar-ad-manager');?> (<?php echo esc_html($size); ?>)</h4>
                            </div>
                            <div class="mdl-cell mdl-cell--6-col mdl-cell--12-col-tablet">
                                <?php $sizeValue = get_post_meta($post->ID, Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX . '_' . $size . "_adzone_size", true); ?>

                                <select
                                    class="ar-ad-manager-size-selector"
                                    name="<?php echo esc_html(\Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX); ?>[<?php echo esc_html($size); ?>_adzone_size]"
                                    id="<?php echo esc_html($size); ?>_adzone_size"
                                    <?php echo $size === 'desktop' ? 'required' : ''; ?>
                                >
                                    <option value=""><?php echo esc_html__('Select size - (empty)', 'ar-ad-manager'); ?></option>

                                    <?php foreach ($adSizes as $adSize): ?>
                                        <option value="<?php echo esc_html($adSize); ?>" <?php echo ($sizeValue === $adSize) ? 'selected' : ''; ?>>
                                            <?php echo esc_html($adSize); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>

                                <?php if ($size === 'desktop'): ?>
                                    <div class="note"><?php echo esc_html__('Required', 'ar-ad-manager'); ?></div>
                                <?php else: ?>
                                    <div class="note"><?php echo esc_html__('Optional', 'ar-ad-manager'); ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="mdl-grid ar-ad-manager-custom-size" style="<?php echo $sizeValue !== 'custom' ? 'display:none;' : ''; ?>">
                            <div class="mdl-cell mdl-cell--2-col mdl-cell--12-col-tablet">
                                <h4><?php echo esc_html__('Custom size', 'ar-ad-manager');?></h4>
                            </div>
                            <div class="mdl-cell mdl-cell--6-col mdl-cell--12-col-tablet">
                                <input
                                    type="text"
                                    id="<?php echo esc_html($size); ?>_adzone_width"
                                    name="<?php echo esc_html(\Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX); ?>[<?php echo esc_html($size); ?>_adzone_width]"
                                    value="<?php echo esc_html(get_post_meta($post->ID, Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX . '_' . esc_html($size) . "_adzone_width", true)); ?>"
                                    placeholder="100%"
                                />
                                <input
                                    type="text"
                                    id="<?php echo esc_html($size); ?>_adzone_height"
                                    name="<?php echo esc_html(\Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX); ?>[<?php echo esc_html($size); ?>_adzone_height]"
                                    value="<?php echo esc_html(get_post_meta($post->ID, Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX) . '_' . esc_html($size) . "_adzone_height", true); ?>"
                                    placeholder="90px"
                                />
                            </div>
                        </div>

                        <?php $isHideValue = esc_html(get_post_meta($post->ID, Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX . '_' . esc_html($size) . "_is_adzone_hide", true)); ?>

                        <div class="mdl-grid">
                            <div class="mdl-cell mdl-cell--2-col mdl-cell--12-col-tablet">
                                <h4><?php echo esc_html__('Hide adzone on this device', 'ar-ad-manager');?></h4>
                                <p><?php echo esc_html__('Do you want to hide this adzone if its viewed from the selected device?', 'ar-ad-manager');?></p>
                            </div>
                            <div class="mdl-cell mdl-cell--6-col mdl-cell--12-col-tablet">
                                <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="<?php echo esc_html($size); ?>_is_adzone_hide">
                                    <input
                                        type="checkbox"
                                        id="<?php echo esc_html($size); ?>_is_adzone_hide"
                                        class="mdl-switch__input"
                                        name="<?php echo esc_html(\Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX); ?>[<?php echo esc_html($size); ?>_is_adzone_hide]"
                                        <?php echo ($isHideValue === 'true') ? 'checked' : ''; ?>
                                    >
                                    <span class="mdl-switch__label"></span>
                                </label>
                            </div>

                            <input
                                type="hidden"
                                id="<?php echo esc_html($size); ?>_is_adzone_hide-hidden"
                                name="<?php echo esc_html(\Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX); ?>[<?php echo esc_html($size); ?>_is_adzone_hide]"
                                value="<?php echo esc_html($isHideValue); ?>"
                            >
                        </div>

                        <?php $showAdzoneOnInit = get_post_meta(
                            $post->ID,
                            Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX . '_' . esc_html($size) . "_show_adzone_on_init",
                            true
                        ); ?>

                        <div class="mdl-grid">
                            <div class="mdl-cell mdl-cell--2-col mdl-cell--12-col-tablet">
                                <h4><?php echo esc_html__('Show adzone on init', 'ar-ad-manager');?></h4>
                                <p><?php echo esc_html__('Since all banners are loaded after the site has fully loaded, is it necessary to show adzone?', 'ar-ad-manager');?></p>
                            </div>
                            <div class="mdl-cell mdl-cell--6-col mdl-cell--12-col-tablet">
                                <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="<?php echo esc_html($size); ?>_show_adzone_on_init">
                                    <input
                                        type="checkbox"
                                        id="<?php echo esc_html($size); ?>_show_adzone_on_init"
                                        class="mdl-switch__input"
                                        name="<?php echo esc_html(\Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX); ?>[<?php echo esc_html($size); ?>_show_adzone_on_init]"
                                        <?php echo ($showAdzoneOnInit === 'true') ? 'checked' : ''; ?>
                                    >
                                    <span class="mdl-switch__label"></span>
                                </label>
                            </div>

                            <input
                                type="hidden"
                                id="<?php echo esc_html($size); ?>_show_adzone_on_init-hidden"
                                name="<?php echo esc_html(\Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX); ?>[<?php echo esc_html($size); ?>_show_adzone_on_init]"
                                value="<?php echo esc_html($showAdzoneOnInit); ?>"
                            >
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }

    /**
     * @param $post
     * @return mixed|void
     */
    public function render_meta_box_content($post)
    {
        $selectedAlignValue = get_post_meta($post->ID, Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX . "_adzone_align", true);

        $alignValues = [
            '' => 'None',
            'start' => 'Align left',
            'end' => 'Align right',
            'center' => 'Align center',
        ];
        ?>
        <div class="ar-ad-manager-box">
            <div class="mdl-grid">
                <div class="mdl-cell mdl-cell--2-col mdl-cell--12-col-tablet">
                    <h4><?php echo esc_html__('Align Adzone', 'ar-ad-manager');?></h4>
                    <p><?php echo esc_html__('Do you want align this adzone?', 'ar-ad-manager');?></p>
                </div>
                <div class="mdl-cell mdl-cell--6-col mdl-cell--12-col-tablet">
                    <select
                        name="<?php echo esc_html(\Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX); ?>[adzone_align]"
                        id="adzone_align"
                    >
                        <?php foreach ($alignValues as $alignValue => $alignLabel): ?>
                            <option value="<?php echo esc_html($alignValue); ?>" <?php echo ($alignValue === $selectedAlignValue) ? 'selected' : ''; ?>>
                                <?php echo esc_html($alignLabel); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <?php $hideIfEmptyValue = get_post_meta($post->ID, Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX . "_hide_adzone_if_empty", true); ?>

            <div class="mdl-grid">
                <div class="mdl-cell mdl-cell--2-col mdl-cell--12-col-tablet">
                    <h4><?php echo esc_html__('Hide adzone if empty', 'ar-ad-manager');?></h4>
                    <p><?php echo esc_html__('Do you want to hide this adzone if its empty?', 'ar-ad-manager');?></p>
                </div>
                <div class="mdl-cell mdl-cell--6-col mdl-cell--12-col-tablet">
                    <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="hide-if-empty">
                        <input
                            type="checkbox"
                            id="hide-if-empty"
                            class="mdl-switch__input"
                            name="<?php echo esc_html(\Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX); ?>[hide_adzone_if_empty]"
                            <?php echo ($hideIfEmptyValue === 'true') ? 'checked' : ''; ?>
                        >
                        <span class="mdl-switch__label"></span>
                    </label>
                </div>

                <input
                    type="hidden"
                    id="hide-if-empty-hidden"
                    name="<?php echo esc_html(\Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX); ?>[hide_adzone_if_empty]"
                    value="<?php echo esc_html($hideIfEmptyValue); ?>"
                >
            </div>

            <div class="mdl-grid">
                <div class="mdl-cell mdl-cell--2-col mdl-cell--12-col-tablet">
                    <h4><?php echo esc_html__('CSS Class', 'ar-ad-manager');?></h4>
                    <p><?php echo esc_html__('Add a class to the wrapping Adzone element.', 'ar-ad-manager');?></p>
                </div>
                <div class="mdl-cell mdl-cell--6-col mdl-cell--12-col-tablet">
                    <div class="mdl-textfield mdl-js-textfield">
                        <input
                            class="mdl-textfield__input"
                            type="text"
                            name="<?php echo esc_html(\Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX); ?>[adzone_css_class]"
                            id="adzone_css_class"
                            value="<?php echo esc_html(get_post_meta($post->ID, Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX . "_adzone_css_class", true)); ?>"
                        >
                    </div>
                </div>
            </div>

            <div class="mdl-grid">
                <div class="mdl-cell mdl-cell--2-col mdl-cell--12-col-tablet">
                    <h4><?php echo esc_html__('Adzone Text', 'ar-ad-manager');?></h4>
                    <p><?php echo esc_html__('Show a small text of the adzone.', 'ar-ad-manager');?></p>
                </div>
                <div class="mdl-cell mdl-cell--6-col mdl-cell--12-col-tablet">
                    <div class="mdl-textfield mdl-js-textfield">
                        <input
                            class="mdl-textfield__input"
                            type="text"
                            name="<?php echo esc_html(\Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX); ?>[adzone_text]"
                            id="adzone_text"
                            value="<?php echo esc_html(get_post_meta($post->ID, Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX . "_adzone_text", true)); ?>"
                        >
                    </div>
                </div>
            </div>

            <div class="mdl-grid">
                <div class="mdl-cell mdl-cell--2-col mdl-cell--12-col-tablet">
                    <h4><?php echo esc_html__('Adzone margin', 'ar-ad-manager');?></h4>
                    <p><?php echo esc_html__('Select the margin for the adzone in px', 'ar-ad-manager');?></p>
                </div>
                <div class="mdl-cell mdl-cell--6-col mdl-cell--12-col-tablet">
                    <div class="mdl-textfield mdl-js-textfield">
                        <input
                            class="mdl-textfield__input"
                            type="text"
                            pattern="-?[0-9]*(\.[0-9]+)?"
                            name="<?php echo esc_html(\Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX); ?>[adzone_margin]"
                            id="adzone_margin"
                            value="<?php echo esc_html(get_post_meta($post->ID, Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX . "_adzone_margin", true)); ?>"
                        >
                        <span class="mdl-textfield__error"><?php echo esc_html__('Input is not a number!', 'ar-ad-manager');?></span>
                    </div>
                </div>
            </div>

            <div class="mdl-grid">
                <?php $isAdzoneTransparent = get_post_meta($post->ID, Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX . "_is_adzone_transparent", true); ?>

                <div class="mdl-cell mdl-cell--2-col mdl-cell--12-col-tablet">
                    <h4><?php echo esc_html__('Set adzone transparent', 'ar-ad-manager');?></h4>
                </div>
                <div class="mdl-cell mdl-cell--6-col mdl-cell--12-col-tablet">
                    <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="is_adzone_transparent">
                        <input
                            type="checkbox"
                            id="is_adzone_transparent"
                            class="mdl-switch__input"
                            name="<?php echo esc_html(\Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX); ?>[is_adzone_transparent]"
                            <?php echo ($isAdzoneTransparent === 'true') ? 'checked' : ''; ?>
                        >
                        <span class="mdl-switch__label"></span>
                    </label>
                </div>

                <input
                    type="hidden"
                    id="is_adzone_transparent-hidden"
                    name="<?php echo esc_html(\Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX); ?>[is_adzone_transparent]"
                    value="<?php echo esc_html($isAdzoneTransparent); ?>"
                >
            </div>

            <?php $adzoneBgc = get_post_meta($post->ID, Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX . "_adzone_background_color", true); ?>

            <div class="mdl-grid">
                <div class="mdl-cell mdl-cell--2-col mdl-cell--12-col-tablet">
                    <h4><?php echo esc_html__('Adzone Background Color', 'ar-ad-manager');?></h4>
                </div>
                <div class="mdl-cell mdl-cell--6-col mdl-cell--12-col-tablet">
                    <div class="mdl-textfield mdl-js-textfield">
                        <input
                            class="mdl-textfield__input"
                            type="color"
                            name="<?php echo esc_html(\Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX); ?>[adzone_background_color]"
                            id="adzone_background_color"
                            value="<?php echo $adzoneBgc ?: '#ffffff'; ?>"
                        >
                    </div>
                </div>
            </div>

            <div class="mdl-grid">
                <div class="mdl-cell mdl-cell--2-col mdl-cell--12-col-tablet">
                    <h4><?php echo esc_html__('Default adzone image', 'ar-ad-manager');?></h4>
                    <p><?php echo esc_html__('Upload/Select a default image that will show when the azone is empty.', 'ar-ad-manager');?></p>
                </div>
                <div class="mdl-cell mdl-cell--6-col mdl-cell--12-col-tablet">
                    <?php $defaultImageUrl = get_post_meta($post->ID, Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX . "_adzone_default_image", true); ?>

                    <div class="mdl-textfield mdl-js-textfield">
                        <input
                            class="mdl-textfield__input"
                            type="text"
                            name="<?php echo esc_html(\Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX); ?>[adzone_default_image]"
                            id="adzone_default_image"
                            placeholder="<?php echo esc_html__('Default image url', 'ar-ad-manager');?>"
                            value="<?php echo esc_html($defaultImageUrl); ?>"
                        >
                        <label class="mdl-textfield__label" for="adzone_default_link"></label>
                    </div>

                    <button class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored" id="adzone-default-image-banner-btn">
                        <?php echo esc_html__('Upload image', 'ar-ad-manager');?>
                    </button>

                    <div id="adzone-default-banner-preview" class="ar-ad-manager-image-preview">
                        <?php if ($defaultImageUrl): ?>
                            <img src="<?php echo esc_html($defaultImageUrl); ?>" alt="<?php echo esc_html__('Default image', 'ar-ad-manager');?>">
                        <?php else: ?>
                            <?php echo esc_html__('No image selected', 'ar-ad-manager'); ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="mdl-grid">
                <div class="mdl-cell mdl-cell--2-col mdl-cell--12-col-tablet">
                    <h4><?php echo esc_html__('Default adzone image link', 'ar-ad-manager');?></h4>
                    <p><?php echo esc_html__('Add a link that will be added to the default showing when the adzone is empty.', 'ar-ad-manager');?></p>
                </div>
                <div class="mdl-cell mdl-cell--6-col mdl-cell--12-col-tablet">
                    <div class="mdl-textfield mdl-js-textfield">
                        <input
                            class="mdl-textfield__input"
                            type="text"
                            name="<?php echo esc_html(\Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX); ?>[adzone_default_link]"
                            id="adzone_default_link"
                            placeholder="https://"
                            value="<?php echo esc_html(get_post_meta($post->ID, Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX . "_adzone_default_link", true)); ?>"
                        >
                        <label class="mdl-textfield__label" for="adzone_default_link"></label>
                    </div>
                </div>
            </div>
        </div>
        <script>
            var imageUploadBtn = document.getElementById('adzone-default-image-banner-btn');
            var adzoneDefaultImage = document.getElementById('adzone_default_image');
            var adzoneDefaultImagePreview = document.getElementById('adzone-default-banner-preview');

            if (imageUploadBtn && adzoneDefaultImage && adzoneDefaultImagePreview) {
                imageUploadBtn.addEventListener('click', function (e) {
                    e.preventDefault();

                    if( typeof wp !== "undefined" && wp.media && wp.media.editor ) {
                        wp.media.editor.send.attachment = function (props, attachment) {
                            adzoneDefaultImage.value = attachment.url;
                            adzoneDefaultImagePreview.innerHTML = '<img src="' + attachment.url + '" />';
                        }
                        wp.media.editor.open(this);
                    }

                    return false;
                })
            }
        </script>

        <input
            type="hidden"
            name="<?php echo esc_html(\Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX); ?>_fields_nonce"
            value="<?php echo esc_html(wp_create_nonce(\Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_NONCE)); ?>"
        />
        <?php
    }
}

new Partials_Ar_Ad_Manager_Adzones_Meta_Box();