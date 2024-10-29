<?php

/**
 * Class Partials_Ar_Ad_Manager_Types
 */
class Partials_Ar_Ad_Manager_Types
{
    /**
     * @return void
     */
    public function registerPostTypes()
    {
        $types = [
            [
                'type' => \Ar_Ad_Manager_Admin::AR_AD_MANAGER_PREFIX . 'advertisers',
                'name' => esc_html__('Advertisers', 'ar-ad-manager'),
                'singular_name' => esc_html__('Advertiser', 'ar-ad-manager'),
                'slug' => \Ar_Ad_Manager_Admin::AR_AD_MANAGER_PREFIX . 'advertisers',
                'position' => 10
            ],
            [
                'type' => \Ar_Ad_Manager_Admin::AR_AD_MANAGER_PREFIX . 'adzones',
                'name' => esc_html__('Adzones', 'ar-ad-manager'),
                'singular_name' => esc_html__('Adzone', 'ar-ad-manager'),
                'slug' => \Ar_Ad_Manager_Admin::AR_AD_MANAGER_PREFIX . 'adzones',
                'position' => 20
            ],
            [
                'type' => \Ar_Ad_Manager_Admin::AR_AD_MANAGER_PREFIX . 'banners',
                'name' => esc_html__('Banners', 'ar-ad-manager'),
                'singular_name' => esc_html__('Banner', 'ar-ad-manager'),
                'slug' => \Ar_Ad_Manager_Admin::AR_AD_MANAGER_PREFIX . 'banners',
                'position' => 30
            ]
        ];
        
        foreach ($types as $type) {
            $labels = [
                'name' => $type['name'],
                'singular_name' => $type['singular_name'],
                'add_new' => sprintf(esc_html__('Add New %s', 'ar-ad-manager'), $type['singular_name']),
                'add_new_item' => sprintf(esc_html__('Add New %s', 'ar-ad-manager'), $type['singular_name']),
                'edit_item' => sprintf(esc_html__('Edit %s', 'ar-ad-manager'), $type['singular_name']),
                'new_item' => sprintf(esc_html__('New %s', 'ar-ad-manager'), $type['singular_name']),
                'view_item' => sprintf(esc_html__('View %s', 'ar-ad-manager'), $type['singular_name']),
                'search_items' => sprintf(esc_html__('Search %s', 'ar-ad-manager'), $type['name']),
                'not_found' => sprintf(esc_html__('No %s Found', 'ar-ad-manager'), $type['name']),
                'not_found_in_trash' => sprintf(esc_html__('No %s Found in Trash', 'ar-ad-manager'), $type['name']),
                'parent_item_colon' => '',
                'menu_name' => $type['name']
            ];

            register_post_type(
                $type['type'],
                [
                    'labels' => $labels,
                    'public' => false,
                    'hierarchical' => false,
                    'has_archive' => false,
                    'publicly_queryable'  => false,
                    'exclude_from_search' => true,
                    'query_var' => true,
                    'supports' => ['title'],
                    'rewrite' => [
                        'slug' => $type['slug'],
                        'with_front' => false
                    ],
                    'show_in_menu' => \Ar_Ad_Manager_Admin::AR_AD_MANAGER_PAGE_MENU_NAME,
                    'show_ui' => true,
                    'menu_position' => $type['position']
                ]
            );

            add_filter(
                'manage_edit-' . $type['type'] . '_columns',
                [$this, str_replace('-', '_', $type['type']) . '_columns']
            );
            add_action(
                'manage_posts_custom_column',
                [$this, str_replace('-', '_', $type['type']) . '_show_columns']
            );
        }
    }

    /**
     * @param $columns
     * @return mixed
     */
    public function ar_wp_ad_banners_columns($columns)
    {
        unset($columns['date']);
        $columns['wp_uid'] = esc_html__('ID', 'ar-ad-manager');
        $columns['advertiser'] = esc_html__('Advertiser', 'ar-ad-manager');
        $columns['weight'] = esc_html__('Weight', 'ar-ad-manager');
        $columns['banner_is_active'] = esc_html__('Status', 'ar-ad-manager');
        $columns['banner_linked_adzones'] = esc_html__('Adzone', 'ar-ad-manager');

        return $columns;
    }

    /**
     * @param $name
     * @return void
     */
    public function ar_wp_ad_banners_show_columns($name)
    {
        global $post;

        switch ($name)
        {
            case 'advertiser':
                echo esc_html(get_post_meta( $post->ID, Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX . "_banner_advertiser", true));

                break;
            case 'weight':
                echo esc_html(get_post_meta( $post->ID, Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX . "_banner_weight", true));

                break;
            case 'banner_is_active':
                $isActiveValue = get_post_meta( $post->ID, Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX . "_banner_is_active", true);
                ?>
                <div class="is-active-toggle-grid" data-post-id="<?php echo esc_html($post->ID);?>" data-field="banner_is_active">
                    <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="<?php echo esc_html(\Ar_Ad_Manager_Admin::AR_AD_MANAGER_PREFIX);?>is-active-<?php echo esc_html($post->ID);?>">
                        <input
                            type="checkbox"
                            id="<?php echo esc_html(\Ar_Ad_Manager_Admin::AR_AD_MANAGER_PREFIX);?>is-active-<?php echo esc_html($post->ID);?>"
                            class="mdl-switch__input"
                            name="<?php echo esc_html(\Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX); ?>[banner_is_active]"
                            <?php echo ($isActiveValue === 'true') ? 'checked' : ''; ?>
                        >
                        <span class="mdl-switch__label"></span>
                    </label>
                </div>
                <?php
                break;
            case 'banner_linked_adzones':
                $adzonesArgs = array(
                    'posts_per_page'   => -1,
                    'post_type'        => \Ar_Ad_Manager_Admin::AR_AD_MANAGER_PREFIX . 'adzones',
                    'post_status'      => 'publish'
                );

                $allAdzones = get_posts( $adzonesArgs );
                ?>
                <div class="ar-ad-manager-box">
                    <div class="mdl-grid grid">
                        <div class="mdl-textfield mdl-js-textfield">
                            <?php $adzoneValues = get_post_meta($post->ID, Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX . "_banner_linked_adzones", true); ?>
                            <?php $adzoneValues = $adzoneValues ? explode(',', $adzoneValues) : []; ?>
                            <select
                                class="banner-linked-adzones"
                                data-post-id="<?php echo esc_html($post->ID);?>"
                                name="<?php echo esc_html(\Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX); ?>[banner_linked_adzones][]"
                                id="<?php echo esc_html($post->ID);?>_banner_linked_adzones"
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
                <?php
                break;
        }
    }

    /**
     * @param $columns
     * @return mixed
     */
    public function ar_wp_ad_adzones_columns($columns)
    {
        unset($columns['date']);
        $columns['wp_uid'] = esc_html__('ID', 'ar-ad-manager');
        $columns['adzone_size'] = esc_html__('Adzone Size', 'ar-ad-manager');
        $columns['hide_adzone_if_empty'] = esc_html__('Hide adzone if empty', 'ar-ad-manager');
        $columns['code'] = '';

        return $columns;
    }

    /**
     * @param $name
     * @return void
     */
    public function ar_wp_ad_adzones_show_columns($name)
    {
        global $post;

        switch ($name)
        {
            case 'wp_uid':
                echo esc_html($post->ID);

                break;
            case 'adzone_size':
                echo esc_html(get_post_meta( $post->ID, Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX . "_desktop_adzone_size", true));

                break;
            case 'hide_adzone_if_empty':
                echo esc_html(get_post_meta( $post->ID, Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX . "_hide_adzone_if_empty", true));

                break;
            case 'code':
                ?>
                <div class="ar-ad-manager-shortcode-grid">
                    <a href="javascript:" class="show-dialog">
                        <span class="mdl-chip mdl-chip--contact">
                            <span class="mdl-chip__contact mdl-color--teal mdl-color-text--white">C</span>
                            <span class="mdl-chip__text"><?php echo esc_html__('Get code', 'ar-ad-manager'); ?></span>
                        </span>
                    </a>

                    <dialog class="mdl-dialog">
                        <h4 class="mdl-dialog__title"><?php echo esc_html__('Shortcode', 'ar-ad-manager'); ?></h4>
                        <div class="mdl-dialog__content">
                            <h4><?php echo esc_html__('Post tag', 'ar-ad-manager');?></h4>
                            <p><?php echo esc_html__('If you want to show this ad zone into a single post/page you can use this Post Tag. Just copy the shortcode into your post\'s textfield', 'ar-ad-manager');?></p>
                            <code>[ar_ad_manager_display_adzone id="<?php echo esc_html($post->ID) ?>"]</code>

                            <hr>

                            <h4><?php echo esc_html__('Template tag', 'ar-ad-manager');?></h4>
                            <p><?php echo esc_html__('If you want to use this ad zone on a fixed place inside your website, you can use this Template tag. Just copy the function into your website template, there where you want to show the banners', 'ar-ad-manager');?></p>
                            <code>echo do_shortcode('[ar_ad_manager_display_adzone id=<?php echo esc_html($post->ID) ?>]');</code>
                        </div>
                        <div class="mdl-dialog__actions">
                            <button type="button" class="mdl-button close"><?php echo esc_html__('Close', 'ar-ad-manager'); ?></button>
                        </div>
                    </dialog>
                </div>
                <?php
                break;
        }
    }

    /**
     * @param $columns
     * @return mixed
     */
    public function ar_wp_ad_advertisers_columns($columns)
    {
        unset($columns['date']);
        $columns['wp_uid'] = esc_html__('ID', 'ar-ad-manager');
        $columns['is_advertiser_active'] = esc_html__('Is Active', 'ar-ad-manager');

        return $columns;
    }

    /**
     * @param $name
     * @return void
     */
    public function ar_wp_ad_advertisers_show_columns($name)
    {
        global $post;

        switch ($name) {
            case 'is_advertiser_active':
                $isActiveValue = get_post_meta( $post->ID, Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX . "_is_advertiser_active", true);
                ?>
                <div class="is-active-toggle-grid" data-post-id="<?php echo esc_html($post->ID);?>" data-field="is_advertiser_active">
                    <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="<?php echo esc_html(\Ar_Ad_Manager_Admin::AR_AD_MANAGER_PREFIX);?>is-active-<?php echo esc_html($post->ID);?>">
                        <input
                            type="checkbox"
                            id="<?php echo esc_html(\Ar_Ad_Manager_Admin::AR_AD_MANAGER_PREFIX);?>is-active-<?php echo esc_html($post->ID);?>"
                            class="mdl-switch__input"
                            name="<?php echo esc_html(\Partials_Ar_Ad_Manager_Meta_Box_Abstract::AR_AD_MANAGER_FIELD_PREFIX); ?>[is_advertiser_active]"
                            <?php echo ($isActiveValue === 'true') ? 'checked' : ''; ?>
                        >
                        <span class="mdl-switch__label"></span>
                    </label>
                </div>
                <?php
                break;
        }
    }
}