=== AR Advertising Management ===
Contributors: CoolS2
Tags: advertising, advertising management, advertise, ad
Requires at least: 6.0.0
Tested up to: 6.4.2
Requires PHP: 7.4
Stable tag: 1.0.5
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Plugin to manage advertisements on your website. Beautifully, Easily and Professional.

== Description ==

A plugin for managing advertising on your website is a powerful tool that provides easy and flexible management of your advertising content.

One of the key features of the plugin is the ability to advertise materials, which helps pages load faster and improves the user experience. Also, thanks to optimized loading, the plugin characterizes itself as an SEO-friendly tool, helping to improve your site\'s ranking in search results.

The plugin is easy to install and use, while providing plenty of options to fine-tune it to suit your needs. Regardless of the size of your site or its theme, this plugin provides all the necessary tools to successfully manage advertising and maximize its effectiveness.

3rd Party or external service

We allow the user to enable Google Analytics on the site if the ID tag is entered and Google Analytics is not initially loaded on the site.
You can view the usage policy using the link below.

Google Analytics Terms of Service: https://www.google.com/analytics/terms

== Installation ==

1. First of all, you need to create adzones. Adzones are the places where advertisements will be displayed.

In order to create an adzone, you need to go to the "Advertising->Adzones" section and click "Add a new adzone"

In this section you need to specify the size of the zone, and you can also indicate on which devices this zone is available, you can specify the color, text, and many other settings.

After you have created an adzone, you can place it anywhere on the site using a shortcode where you need to specify the adzone ID [ar_ad_manager_display_adzone id="123"]

2. You need to create an Advertiser. An advertiser is needed to be able to group and manage banner statuses.

For example, if you want to show ads from Adsense, create an advertiser with the name "Adsense", specify the script link for initializing banners https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-xxxxxxxxxx

Optionally, you can also specify the date and time, as well as the days of the week when you want to display advertising from this advertiser.

3. Next you need to create a Banner. Let's continue with the Adsense example. For example, you want to install a banner on the title of an article where you have already placed the ad zone mentioned above.

For convenience, name the banner (Adaptive Google, under the title).

Enter the advertiser you created in the previous step

Specify the adzone you created in the first step.

Enter the script in the "Banner script" section
```php
<ins class="adsbygoogle"
      style="display:inline-block;width:728px;height:90px"
      data-ad-client="ca-pub-xxxxxxxxxxxxx"
      data-ad-slot="23434567"></ins>
<script>
      (adsbygoogle = window.adsbygoogle || []).push({});
</script>
```
You can also specify other settings, for example, specify a country if you want to display a banner only for specific countries or ID posts and categories if you want to display a banner for specific articles and categories.

4. Additional settings

Go to the "Dashboard" section, here you can set additional classes for adzones, if you want to set styles for your project, you can enable statistics. And also indicate the provider through which we will identify user countries

== Screenshots ==

1. Advertiser Grid
2. Adzone Grid
3. Banner Grid
