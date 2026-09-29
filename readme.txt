=== Membership Site Spam Protection - Integrate Akismet & PMPro ===
Contributors: strangerstudios, paidmembershipspro
Tags: anti-spam, antispam, spam, paid memberships pro, pmpro
Requires at least: 5.4
Tested up to: 7.1
Stable tag: 1.1.1
Requires PHP: 7.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Protect your membership site from checkout spam with Akismet and Paid Memberships Pro.

== Description ==

Protect your membership site's checkout process from spam with Akismet and Paid Memberships Pro.

This plugin integrates the [Akismet Plugin by Automattic](https://wordpress.org/plugins/akismet/) with [Paid Memberships Pro](https://wordpress.org/plugins/paid-memberships-pro/).

By default, Akismet checks your site's comments and certain form submissions against a global database of spam intelligence to prevent your site from publishing malicious content.

With this integration, the same comment spam filters are used to detect checkout form abuse. The plugin checks the submitted email address against Akismet's world-class spam filters. If a checkout is flagged, membership registration is completely blocked.

You can use this plugin to protect free user registrations as well as paid checkouts. Payments will not be processed for flagged email addresses, which is a great way to protect your site from card testing, credit card fraud, as well as fake registrations and spam.

== Installation ==

Note: You must have [Paid Memberships Pro](https://wordpress.org/plugins/paid-memberships-pro/) and [Akismet](https://wordpress.org/plugins/akismet/) installed and activated on your site.

### Install PMPro Akismet from within WordPress

1. Make sure Paid Memberships Pro is active.
1. Visit Memberships > Add Ons
1. Locate this plugin and click "Install"
1. Activate "Paid Memberships Pro - Akismet Integration" through the "Plugins" menu in WordPress
1. Go to "after activation" below.

### Install PMPro Akismet Manually

1. Upload the `pmpro-akismet` folder to the `/wp-content/plugins/` directory
1. Activate "Paid Memberships Pro - Akismet Integration" through the "Plugins" menu in WordPress
1. Go to "after activation" below.

### After Activation: How to Use

This plugin relies on the settings you have configured within the main Akismet plugin.

Navigate to Settings > Akismet Anti-Spam to configure your general Akismet settings.

PMPro Akismet will automatically detect spam at checkout and prevent submissions if they are considered spam. If you have enabled the setting to "Display a privacy notice under your comment forms," this message will also display on your membership checkout page.

View full documentation at: [https://www.paidmembershipspro.com/add-ons/pmpro-akismet/](https://www.paidmembershipspro.com/add-ons/pmpro-akismet/).

== Screenshots ==

1. Membership checkout page with optional message shown about the Akismet service.
2. Message shown on a failed membership checkout when Akismet flags an email address as spam.

== Changelog ==

= 1.1.1 - 2026-09-29 =
* SECURITY: Improved sanitization and escaping throughout the plugin to resolve Plugin Check security findings. #8 (@dparker1005)
* ENHANCEMENT: Added Docs and Support links to the plugin's row on the Plugins page. #7 (@ipokkel)
* BUG FIX: Fixed an undefined index notice at checkout when the request has no Referer header. #8 (@dparker1005)

= 1.1 - 2024-08-12 =
* FEATURE: Now only blocking "definite" spam. For "likely spam", users are prompted to checkout again.
* ENHANCEMENT: Now always tracking likely spam responses from Akismet as "spam activity" for the PMPro spam blocker. Folks testing cards on checkout pages are blocked twice as fast now.
* ENHANCEMENT: You can use the `pmpro_akismet_threshold` filter and have it return 1 instead of 2 to use the stricter rules where all likely spam is treated as spam and blocked.
* ENHANCEMENT/BUG FIX: Fixed formatting of the Akismet link if shown via the `pmpro_akismet_checkout_privacy_notice` filter. Have that return 'display' to display.

= 1.0.1 - 2023-03-30 =
* ENHANCEMENT: Now calling pmpro_track_spam() when spam data is detected. If the PMPro Spam Protection feature is enabled, this will block users from the same IP from submitting checkouts after a certain number of failures.
* ENHANCEMENT/BUG FIX: Bailing on the registration checks early if there is already another issue with checkout. This helps with performance and won't e.g. check for spam if the user submitted the form with missing fields.

= 1.0 - 2023-03-28 =
* Initial version. 
