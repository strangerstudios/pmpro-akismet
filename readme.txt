=== Membership Site Spam Protection - Integrate Akismet & PMPro ===
Contributors: strangerstudios, paidmembershipspro
Tags: anti-spam, antispam, spam, paid memberships pro, pmpro
Requires at least: 5.4
Tested up to: 6.1
Stable tag: 1.0
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

1. Visit the plugins page within your dashboard and select "Add New"
1. Search for "PMPro Akismet"
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

= 1.0 - TBD =
* Initial version. 
