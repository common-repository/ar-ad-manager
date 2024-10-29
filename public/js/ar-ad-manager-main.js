addEventListener("load", function (event) {
    var ajaxBlocks = document.querySelectorAll('.ar-wp-happy-block-ajax');
    var initAdzoneIsDone = false;

    if (ajaxBlocks.length) {
        var adZoneIds = [];
        var isGaActive = false;
        var userInteractionEvents = ['mouseover', 'keydown', 'touchstart', 'touchmove', 'wheel'];
        var isActiveLazyLoad = ar_wp_main_variables.isActiveLazyLoad;

        if (ar_wp_main_variables.ga) {
            initGA();
        }

        ajaxBlocks.forEach(function (ajaxBlock) {
            adZoneIds.push(ajaxBlock.dataset.happyBlockId);
        })

        if (isActiveLazyLoad) {
            // LCP solution
            userInteractionEvents.forEach(function (event) {
                window.addEventListener(event, initAdzoneData, { passive: !0 });
            });

            var initAdzoneTimeout = setTimeout(function () {
                initAdzoneData();
            }, 5000)
        } else {
            initAdzoneData();
        }
    }

    function initAdzoneData() {
        if (initAdzoneIsDone) {
            return;
        }

        // Remove listener
        userInteractionEvents.forEach(function (event) {
            window.removeEventListener(event, initAdzoneData);
        });

        initAdzoneIsDone = true;
        clearTimeout(initAdzoneTimeout);

        var params = {
            action: 'ar_ad_managerzone_data',
            adzone_ids: adZoneIds.join(','),
            post_id: ar_wp_main_variables.post_id,
            window_width: window.innerWidth,
        }

        params = new URLSearchParams(params).toString();

        var xhr = new XMLHttpRequest();
        xhr.open("GET", ar_wp_main_variables.ajaxurl + '?' + params, true);
        xhr.setRequestHeader('Content-type', 'application/json; charset=UTF-8');
        xhr.send();

        return xhr.onload = function (e) {
            // Check if the request was a success
            if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
                // Get and convert the responseText into JSON
                var adzoneDataResponse = JSON.parse(xhr.responseText);

                if (adzoneDataResponse) {
                    var relationships = adzoneDataResponse.data.relationships;

                    relationships.advertisers.forEach(function (advertiserData) {
                        if (advertiserData.script) {
                            var script = document.createElement('script');
                            script.async = true;
                            script.crossorigin = "anonymous"
                            script.src = advertiserData.script;

                            document.getElementsByTagName('body')[0].appendChild(script);
                        }
                    })

                    var responseAdzones = relationships.adzones;

                    if (ar_wp_main_variables.isActiveLazyLoad && ('IntersectionObserver' in window)) {
                        var adBlockObserver = new IntersectionObserver(function (entries, observer) {
                            entries.forEach(function (entry) {
                                if (entry.isIntersecting) {
                                    var target = entry.target;
                                    var adzoneId = entry.target.dataset.happyBlockId;

                                    var adzone = responseAdzones.find(function (responseAdzone) {
                                        return parseInt(responseAdzone.id) === parseInt(adzoneId);
                                    })

                                    if (adzone) {
                                        initArWpBlock(adzone);
                                    }

                                    adBlockObserver.unobserve(target);
                                }
                            })
                        })

                        ajaxBlocks.forEach(function (lazyLoadAdzone) {
                            adBlockObserver.observe(lazyLoadAdzone)
                        })
                    } else {
                        responseAdzones.forEach(function (adzoneData) {
                            initArWpBlock(adzoneData);
                        })
                    }
                }
            }
        }
    }

    function initArWpBlock(adzoneData) {
        if (adzoneData && adzoneData.data) {
            var adzoneBlock = document.querySelector('.ar-wp-happy-block-ajax-' + adzoneData.id);

            if (adzoneBlock) {
                adzoneBlock.innerHTML = adzoneData.data;

                var adzoneBlockScripts = adzoneBlock.querySelectorAll('script');

                if (adzoneBlockScripts.length) {
                    adzoneBlockScripts.forEach(function (adzoneBlockScript) {
                        if (adzoneBlockScript.src) {
                            var scriptClone = document.createElement("script")

                            for (var attr of adzoneBlockScript.attributes) {
                                scriptClone.setAttribute(attr.name, attr.value)
                            }

                            scriptClone.text = adzoneBlockScript.innerHTML
                            adzoneBlockScript.parentNode?.replaceChild(scriptClone, adzoneBlockScript)
                        }

                        var F = new Function(adzoneBlockScript.innerHTML);
                        F();
                    })
                }

                if (isGaActive && adzoneData.banner_id) {
                    gtag('event', 'ar-ad-manager-block-init', {
                        'adzone_id': adzoneData.id,
                        'adzone_name': adzoneData.adzone_name,
                        'banner_id': adzoneData.banner_id,
                        'banner_name': adzoneData.banner_name,
                    });

                    var bannerLink = adzoneBlock.querySelector('a');

                    if (bannerLink) {
                        bannerLink.addEventListener('click', function () {
                            gtag('event', 'ar-ad-manager-block-click', {
                                'adzone_id': adzoneData.id,
                                'adzone_name': adzoneData.adzone_name,
                                'banner_id': adzoneData.banner_id,
                                'banner_name': adzoneData.banner_name,
                            });
                        })
                    } else {
                        adzoneBlock.addEventListener('click', function () {
                            gtag('event', 'ar-ad-manager-block-click', {
                                'adzone_id': adzoneData.id,
                                'adzone_name': adzoneData.adzone_name,
                                'banner_id': adzoneData.banner_id,
                                'banner_name': adzoneData.banner_name,
                            });
                        })
                    }
                }
            }
        }
    }

    function initGA() {
        if ((typeof gtag === 'function')) {
            isGaActive = true;
        } else {
            var script = document.createElement('script');
            script.async = true;
            script.src = 'https://www.googletagmanager.com/gtag/js?id=' + ar_wp_main_variables.ga;
            script.onload = function () {
                isGaActive = true;
            }
        }
    }
});