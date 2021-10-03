# Autotelex Inventory

This plugin enables you to show Autotelex inventory on a Wordpress website. The plugin includes Yoast SEO integration, such that SEO data is correctly loaded from Autotelex to your Wordpress website.

## Installation

First, make sure that you enable the Javascript plugin within the Voorraadmodule settings. This plugin only works via the Javascript plugin of Voorraadmodule.

Intallation is fairly simple, the `autotelex-inventory` folder in this repository acts as the plugin, copy it to your `plugins` directory over in `wp-content/plugins`. After this, you should activate the plugin and setup the Autotelex settings. The Voorraadmodule URL should probably be a subdomain of your current domain such as `https://voorraad.example.com/`. Make sure you end the domain with a `/`. The Voorraadmodule SEO URL is used to get SEO data and put it in your pages. This is most likely a URL starting with `https://www.voorraadmodule.nl/random-string-of-numbers-and-digits/random-numbers`.

After setting up the Autotelex setting, the setup message should disappear in your admin dashboard. You are now able to use the `[autotelex-inventory]` shortcode on one of your pages which will load the Autotelex page there.
