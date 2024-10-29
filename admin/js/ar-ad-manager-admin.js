(function ($) {
    'use strict';

    $(window).load(function () {
        if (typeof jQuery !== 'undefined') {
            var selectors = document.querySelectorAll('.ar-ad-manager-box select');

            if (selectors.length) {
                selectors.forEach(function (selector) {
                    $(selector).selectize({
                        plugins: [ "remove_button" ],
                        onChange: function (value) {
                            if (selector.classList.contains('banner-linked-adzones')) {
                                var postId = selector.dataset.postId;

                                $.ajax({
                                    type: 'POST',
                                    url: ajaxurl,
                                    data: 'action=ar_ad_manager_grid_adzone_change&post_id=' + postId + '&value=' + value.join(',') + '&security=' + ar_ad_managermin_variables.ajax_nonce
                                }).done(function (msg) {

                                });
                            }

                            if (selector.classList.contains('ar-ad-manager-size-selector')) {
                                var customSizeBlock = selector.closest('.mdl-tabs__panel').querySelector('.ar-ad-manager-custom-size');

                                if (value === 'custom') {
                                    customSizeBlock.style.display = '';
                                } else {
                                    customSizeBlock.style.display = 'none';
                                }
                            }
                        }
                    });
                })
            }
        }

        // Advertiser grid process
        var isActiveToggleGrid = document.querySelectorAll('.is-active-toggle-grid');

        if (isActiveToggleGrid.length) {
            isActiveToggleGrid.forEach(function (toggleElBlock) {
                var postId = toggleElBlock.dataset.postId;
                var fieldId = toggleElBlock.dataset.field;
                var toggleInput = toggleElBlock.querySelector('.mdl-switch__input');

                toggleInput.addEventListener('change', function (e) {
                    $.ajax({
                        type: 'POST',
                        url: ajaxurl,
                        data: 'action=ar_ad_manager_grid_status_toggle&post_id=' + postId + '&field=' + fieldId + '&is_active=' + e.target.checked + '&security=' + ar_ad_managermin_variables.ajax_nonce
                    }).done(function (msg) {

                    });
                })
            })
        }

        // Toggle edit form process

        var buttons = document.querySelectorAll('.ar-ad-manager-box .mdl-switch__input');

        if (buttons && buttons.length) {
            buttons.forEach(function (inputEl) {
                var btnId = inputEl.id

                if (btnId) {
                    var hiddenValue = document.getElementById(btnId + '-hidden');

                    if (hiddenValue) {
                        inputEl.addEventListener('change', function (e) {
                            hiddenValue.value = e.target.checked;
                        })
                    }

                }
            })
        }

        // Adzone shortcode grid process


        var showDialogButtons = document.querySelectorAll('.show-dialog');

        if (showDialogButtons && showDialogButtons.length) {
            showDialogButtons.forEach(function (showDialogButton) {
                var dialog = showDialogButton.closest('.ar-ad-manager-shortcode-grid').querySelector('dialog');

                if (!dialog.showModal) {
                    dialogPolyfill.registerDialog(dialog);
                }

                showDialogButton.addEventListener('click', function () {
                    dialog.showModal();
                });

                dialog.querySelector('.close').addEventListener('click', function () {
                    dialog.close();
                });
            })
        }
    })
})(jQuery);
