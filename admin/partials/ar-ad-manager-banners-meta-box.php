<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) exit;
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class Partials_Ar_Ad_Manager_Banners_Meta_Box
 */
class Partials_Ar_Ad_Manager_Banners_Meta_Box extends \Partials_Ar_Ad_Manager_Meta_Box_Abstract
{
    /**
     * @param $post_type
     * @return mixed|void
     */
    public function add_meta_box($post_type)
    {
        $post_types = [\Ar_Ad_Manager_Admin::AR_AD_MANAGER_PREFIX . 'banners'];

        if (in_array($post_type, $post_types)) {
            add_meta_box(
                \Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX . '_banners_options',
                esc_html__('Banner Options', 'ar-ad-manager'),
                [$this, 'render_meta_box_content'],
                $post_type,
                'normal',
                'high'
            );
            add_meta_box(
                \Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX . '_banners_type',
                esc_html__('Banner Type', 'ar-ad-manager'),
                [$this, 'render_meta_box_type'],
                $post_type,
                'normal',
                'high'
            );
            add_meta_box(
                \Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX . '_banners_size_settings',
                esc_html__('Banner Settings', 'ar-ad-manager'),
                [$this, 'render_meta_box_settings'],
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
        $advertiserArgs = array(
            'posts_per_page'   => -1,
            'post_type'        => \Ar_Ad_Manager_Admin::AR_AD_MANAGER_PREFIX . 'advertisers',
            'post_status'      => 'publish'
        );

        $allAdvertisers = get_posts( $advertiserArgs );

        $adzonesArgs = array(
            'posts_per_page'   => -1,
            'post_type'        => \Ar_Ad_Manager_Admin::AR_AD_MANAGER_PREFIX . 'adzones',
            'post_status'      => 'publish'
        );

        $allAdzones = get_posts( $adzonesArgs );
        ?>
        <div class="ar-ad-manager-box">
            <div class="mdl-grid">
                <div class="mdl-cell mdl-cell--2-col mdl-cell--12-col-tablet">
                    <h4><?php echo esc_html__('Advertiser', 'ar-ad-manager');?></h4>
                    <p><?php echo esc_html__('Select an advertiser for this banner.', 'ar-ad-manager');?></p>
                </div>
                <div class="mdl-cell mdl-cell--6-col mdl-cell--12-col-tablet">
                    <div class="mdl-textfield mdl-js-textfield">
                        <?php $advertiserValue = get_post_meta($post->ID, Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX . "_banner_advertiser", true); ?>

                        <select
                            name="<?php echo esc_html(\Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX); ?>[banner_advertiser]"
                            id="advertiser"
                            required
                        >
                            <option value=""><?php echo esc_html__('Select an advertiser', 'ar-ad-manager'); ?></option>

                            <?php foreach ($allAdvertisers as $advertiser): ?>
                                <option value="<?php echo esc_html($advertiser->ID); ?>" <?php echo ((int) $advertiserValue === (int) $advertiser->ID) ? 'selected' : ''; ?>>
                                    <?php echo esc_html($advertiser->post_title); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>

            <?php $activeValue = get_post_meta($post->ID, Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX . "_banner_is_active", true); ?>

            <div class="mdl-grid">
                <div class="mdl-cell mdl-cell--2-col mdl-cell--12-col-tablet">
                    <h4><?php echo esc_html__('Is active', 'ar-ad-manager');?></h4>
                </div>
                <div class="mdl-cell mdl-cell--6-col mdl-cell--12-col-tablet">
                    <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="is-active">
                        <input
                            type="checkbox"
                            id="is-active"
                            class="mdl-switch__input"
                            name="<?php echo esc_html(\Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX); ?>[banner_is_active]"
                            <?php echo ($activeValue === 'true') ? 'checked' : ''; ?>
                        >
                        <span class="mdl-switch__label"></span>
                    </label>
                </div>

                <input
                    type="hidden"
                    id="is-active-hidden"
                    name="<?php echo esc_html(\Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX); ?>[banner_is_active]"
                    value="<?php echo esc_html($activeValue); ?>"
                >
            </div>

            <div class="mdl-grid">
                <div class="mdl-cell mdl-cell--2-col mdl-cell--12-col-tablet">
                    <h4><?php echo esc_html__('Adzones', 'ar-ad-manager');?></h4>
                    <p><?php echo esc_html__('Link your banner to one or more adzones.', 'ar-ad-manager');?></p>
                </div>
                <div class="mdl-cell mdl-cell--6-col mdl-cell--12-col-tablet">
                    <div class="mdl-textfield mdl-js-textfield">
                        <?php $adzoneValues = get_post_meta($post->ID, Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX . "_banner_linked_adzones", true); ?>
                        <?php $adzoneValues = $adzoneValues ? explode(',', $adzoneValues) : []; ?>
                        <select
                            name="<?php echo esc_html(\Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX); ?>[banner_linked_adzones][]"
                            id="banner_linked_adzones"
                            multiple
                        >
                            <?php foreach ($allAdzones as $adzone): ?>
                                <option value="<?php echo esc_html($adzone->ID); ?>" <?php echo (in_array($adzone->ID, $adzoneValues)) ? 'selected' : ''; ?>>
                                    <?php echo esc_html($adzone->post_title); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="mdl-grid">
                <div class="mdl-cell mdl-cell--2-col mdl-cell--12-col-tablet">
                    <h4><?php echo esc_html__('Weight', 'ar-ad-manager');?></h4>
                    <p><?php echo esc_html__('If there are several banners in one zone, then the one with more weight will be more likely to be shown', 'ar-ad-manager');?></p>
                </div>
                <div class="mdl-cell mdl-cell--6-col mdl-cell--12-col-tablet">
                    <div class="mdl-textfield mdl-js-textfield">
                        <input
                            class="mdl-textfield__input"
                            type="text"
                            name="<?php echo esc_html(\Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX); ?>[banner_weight]"
                            id="adzone_css_class"
                            value="<?php echo esc_html(get_post_meta($post->ID, Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX . "_banner_weight", true)); ?>"
                        >
                    </div>
                </div>
            </div>

            <div class="mdl-grid">
                <div class="mdl-cell mdl-cell--2-col mdl-cell--12-col-tablet">
                    <h4><?php echo esc_html__('Banner link', 'ar-ad-manager');?></h4>
                </div>
                <div class="mdl-cell mdl-cell--6-col mdl-cell--12-col-tablet">
                    <div class="mdl-textfield mdl-js-textfield">
                        <input
                            class="mdl-textfield__input"
                            type="text"
                            name="<?php echo esc_html(\Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX); ?>[banner_link]"
                            id="banner_link"
                            value="<?php echo esc_html(get_post_meta($post->ID, Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX . "_banner_link", true)); ?>"
                        >
                    </div>
                </div>
            </div>
        </div>

        <input type="hidden" name="<?php echo esc_html(\Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX); ?>_fields_nonce" value="<?php echo esc_html(wp_create_nonce(\Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_NONCE)); ?>" />
        <?php
    }

    /**
     * @param $post
     * @return void
     */
    public function render_meta_box_type($post)
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

        ?>

        <div class="ar-ad-manager-box">
            <div class="mdl-tabs mdl-js-tabs mdl-js-ripple-effect">
                <div class="mdl-tabs__tab-bar">
                    <?php foreach ($sizes as $size => $icon): ?>
                        <a href="#panel-<?php echo esc_html($size); ?>" class="mdl-tabs__tab <?php echo $size === 'desktop' ? 'is-active' : ''; ?>">
                            <span class="icon"><?php echo $icon; ?></span>
                            <span class="name"><?php echo esc_html($size); ?> <?php echo ($size === 'desktop') ? '' : esc_html__('(Optional)', 'ar-ad-manager'); ?></span>
                        </a>
                    <?php endforeach; ?>
                </div>

                <?php foreach ($sizes as $size => $icon): ?>
                    <div class="mdl-tabs__panel <?php echo $size === 'desktop' ? 'is-active' : ''; ?>" id="panel-<?php echo esc_html($size); ?>">
                        <?php $isHideBannerValue = get_post_meta(
                            $post->ID,
                            Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX . '_' . $size
                            . "_is_hide_banner",
                            true
                        ); ?>

                        <div class="mdl-grid">
                            <div class="mdl-cell mdl-cell--2-col mdl-cell--12-col-tablet">
                                <h4><?php echo esc_html__('Hide banner on this device', 'ar-ad-manager');?></h4>
                                <p><?php echo esc_html__('Do you want to hide this banner if its viewed from the selected device?', 'ar-ad-manager');?></p>
                            </div>
                            <div class="mdl-cell mdl-cell--6-col mdl-cell--12-col-tablet">
                                <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="<?php echo esc_html($size); ?>_is_hide_banner">
                                    <input
                                        type="checkbox"
                                        id="<?php echo esc_html($size); ?>_is_hide_banner"
                                        class="mdl-switch__input"
                                        name="<?php echo esc_html(\Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX); ?>[<?php echo esc_html($size); ?>_is_hide_banner]"
                                        <?php echo ($isHideBannerValue === 'true') ? 'checked' : ''; ?>
                                    >
                                    <span class="mdl-switch__label"></span>
                                </label>
                            </div>

                            <input
                                type="hidden"
                                id="<?php echo esc_html($size); ?>_is_hide_banner-hidden"
                                name="<?php echo esc_html(\Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX); ?>[<?php echo $size; ?>_is_hide_banner]"
                                value="<?php echo esc_html($isHideBannerValue); ?>"
                            >
                        </div>

                        <div class="mdl-grid">
                            <div class="mdl-cell mdl-cell--2-col mdl-cell--12-col-tablet">
                                <h4><?php echo esc_html__('Banner image', 'ar-ad-manager');?></h4>
                                <p><?php echo esc_html__('Upload/Select a banner.', 'ar-ad-manager');?></p>
                            </div>
                            <div class="mdl-cell mdl-cell--6-col mdl-cell--12-col-tablet">
                                <?php $defaultImageUrl = get_post_meta($post->ID, Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX . '_' . $size . "_banner_default_image", true); ?>

                                <div class="mdl-textfield mdl-js-textfield">
                                    <input
                                        class="mdl-textfield__input banner_default_image"
                                        type="text"
                                        name="<?php echo esc_html(\Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX); ?>[<?php echo esc_html($size); ?>_banner_default_image]"
                                        id="<?php echo esc_html($size); ?>_banner_default_image"
                                        placeholder="<?php echo esc_html__('Default image url', 'ar-ad-manager');?>"
                                        value="<?php echo esc_html($defaultImageUrl); ?>"
                                    >
                                </div>

                                <button class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored banner-default-image-banner-btn">
                                    <?php echo esc_html__('Upload image', 'ar-ad-manager');?>
                                </button>

                                <div id="banner-default-banner-preview" class="ar-ad-manager-image-preview">
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
                                <h4><?php echo esc_html__('Size', 'ar-ad-manager');?></h4>
                            </div>
                            <div class="mdl-cell mdl-cell--6-col mdl-cell--12-col-tablet">
                                <input
                                    type="text"
                                    id="<?php echo esc_html($size); ?>_banner_width"
                                    name="<?php echo esc_html(\Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX); ?>[<?php echo esc_html($size); ?>_banner_width]"
                                    value="<?php echo get_post_meta($post->ID, Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX . '_' . $size . "_banner_width", true); ?>"
                                    placeholder="100%"
                                />
                                <input
                                    type="text"
                                    id="<?php echo esc_html($size); ?>_banner_height"
                                    name="<?php echo esc_html(\Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX); ?>[<?php echo esc_html($size); ?>_banner_height]"
                                    value="<?php echo esc_html(get_post_meta($post->ID, Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX . '_' . $size . "_banner_height", true)); ?>"
                                    placeholder="90px"
                                />
                            </div>
                        </div>

                        <div class="mdl-grid">
                            <div class="mdl-cell mdl-cell--2-col mdl-cell--12-col-tablet">
                                <h4><?php echo esc_html__('Banner script', 'ar-ad-manager');?></h4>
                                <p><?php echo esc_html__('HTML Code (adSense, iframes, text ads, HTML5 ...)', 'ar-ad-manager');?></p>
                            </div>
                            <div class="mdl-cell mdl-cell--6-col mdl-cell--12-col-tablet">
                                <div class="mdl-textfield mdl-js-textfield">
                                    <textarea
                                        name="<?php echo esc_html(\Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX); ?>[<?php echo esc_html($size); ?>_banner_script]"
                                        id="<?php echo esc_html($size); ?>_banner_script"
                                        cols="30"
                                        rows="10"
                                    ><?php echo get_post_meta($post->ID, Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX . '_' . $size . "_banner_script", true); ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <script>
            var imageUploadBtns = document.querySelectorAll('.banner-default-image-banner-btn');
            var frame;

            imageUploadBtns.forEach(function (imageUploadBtn) {
                var adzoneDefaultImage = imageUploadBtn.closest('.mdl-grid').querySelector('.banner_default_image')
                var adzoneDefaultImagePreview = imageUploadBtn.closest('.mdl-grid').querySelector('.ar-ad-manager-image-preview')

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
            })
        </script>
        <?php
    }

    public function render_meta_box_settings($post)
    {
        ?>
        <div class="ar-ad-manager-box">
            <div class="mdl-grid">
                <div class="mdl-cell mdl-cell--2-col mdl-cell--12-col-tablet">
                    <h4><?php echo esc_html__('Categories', 'ar-ad-manager');?></h4>
                    <p><?php echo esc_html__('Set category ids if you only want to show this banner for specific categories.', 'ar-ad-manager');?></p>
                </div>
                <div class="mdl-cell mdl-cell--6-col mdl-cell--12-col-tablet">
                    <div class="mdl-textfield mdl-js-textfield">
                        <input
                            type="text"
                            id="banner_categories"
                            name="<?php echo esc_html(\Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX); ?>[banner_categories]"
                            value="<?php echo esc_html(get_post_meta($post->ID, Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX . "_banner_categories", true)); ?>"
                            placeholder="602,603"
                        />

                        <div class="note"><?php echo esc_html__('Example: 602,603,604', 'ar-ad-manager');?></div>
                    </div>
                </div>
            </div>

            <div class="mdl-grid">
                <div class="mdl-cell mdl-cell--2-col mdl-cell--12-col-tablet">
                    <h4><?php echo esc_html__('Posts', 'ar-ad-manager');?></h4>
                    <p><?php echo esc_html__('Set post ids if you only want to show this banner for specific posts.', 'ar-ad-manager');?></p>
                </div>
                <div class="mdl-cell mdl-cell--6-col mdl-cell--12-col-tablet">
                    <div class="mdl-textfield mdl-js-textfield">
                        <input
                            type="text"
                            id="banner_posts"
                            name="<?php echo esc_html(\Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX); ?>[banner_posts]"
                            value="<?php echo get_post_meta($post->ID, Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX . "_banner_posts", true); ?>"
                            placeholder="602,603"
                        />

                        <div class="note"><?php echo esc_html__('Example: 602,603,604', 'ar-ad-manager');?></div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}

new Partials_Ar_Ad_Manager_Banners_Meta_Box();