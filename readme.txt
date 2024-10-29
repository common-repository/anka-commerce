=== ANKA Commerce ===
Contributors: daviddossou
Tags: WordPress, WooCommerce, Payment gateway, ANKA Pay, eCommerce
Donate link: https://www.anka.africa/en/special-offer
Requires at least: 5.0
Tested up to: 6.6
Requires PHP: 7.2
Stable tag: 1.0.0
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Accept payments through ANKA Pay on your WooCommerce store using Credit Cards, Mobile Money, Nigerian Bank Transfer, and PayPal.

== Description ==

**ANKA Commerce** is a powerful payment gateway plugin that enables your WooCommerce store to accept payments through ANKA Pay. With support for various payment methods including credit cards, mobile money, Nigerian bank transfer, and PayPal, ANKA Pay provides a seamless and secure payment experience for your customers.

In order to use ANKA Pay, make sure you have an active account on [ANKA Africa](https://www.anka.africa).

= Key Features =

* Multiple Payment Methods: Accept payments via credit card, mobile money, Nigerian bank transfer, and PayPal.
* Seamless Integration: Easily integrate ANKA Pay with your WooCommerce store.
* Webhook Support: Automatically update order statuses based on real-time notifications from ANKA Pay.
* Internationalization: Supports multiple languages including French.
* WooCommerce Blocks Support: Full compatibility with WooCommerce Blocks for a smooth checkout experience.

= Third-Party Services =

This plugin relies on the ANKA Pay API, a third-party service provided by ANKA, to process payments.

* **Service**: [ANKA Pay](https://www.anka.africa/en/payments)
* **Terms of Use**: [ANKA Terms of Use](https://www.anka.africa/en/pages/terms)
* **Privacy Policy**: [ANKA Legal Mentions](https://www.anka.africa/en/pages/legal)

By using this plugin, you agree to the terms and policies provided by ANKA.

= Supported Languages =

* English (default)
* French

= Usage =

After configuring the plugin, ANKA Pay will appear as a payment option on the checkout page. Customers can select ANKA Pay and complete their purchase using their preferred payment method. They will be redirected to a secure checkout page and redirected back once the payment is successful.

= Handling Webhooks =

ANKA Pay supports webhook notifications to automatically update order statuses. Ensure your webhook URL is configured correctly in your ANKA Pay account settings to enable this feature.

== Installation ==
= From within dashboard (recommended) =

1. Navigate to _Dashboard -> Plugins -> Add New_;
2. Search for _Anka Commerce_;
3. Click _Install_, then _Activate_.

= Manual installation =

1. Download the plugin as a `.zip` file;
2. Unzip downloaded archive and upload `anka-commerce` folder under your `/wp-content/plugins/` directory (resulted plugin path should be `/wp-content/plugins/anka-commerce/`);
3. Navigate to *Dashboard -> Plugins* and activate the plugin.

= Configuration =

1. Enable ANKA Pay: Go to WooCommerce -> Settings -> Payments and enable ANKA Pay.
2. API Token: Enter your ANKA Pay API token.
3. Save Settings: Save your settings to activate ANKA Pay as a payment method on your checkout page.

== Frequently Asked Questions ==
= How do I get my ANKA Pay API token? =
You can find your API token in your ANKA Pay account settings.

= Does this plugin support multiple languages? =
Yes, the plugin supports multiple languages. Currently, it is available in English and French.

= How do I customize the payment method title and description? =
You can update the title and description in the WooCommerce payment gateway settings for ANKA Pay.

= Does this plugin support WooCommerce Blocks? =
Yes, the plugin is fully compatible with WooCommerce Blocks, ensuring a smooth checkout experience.

= Where can I find documentation? =
For help setting up and configuring, please contact our [support team](mailto:tech-support@anka.africa).

== Screenshots ==

1. Plugin Settings: Configure API token.
![Plugin Settings](https://github.com/afrikrea/wordpress-plugin/assets/32579534/02c547f2-615a-4e3e-a359-baaf3560bce4)

2. Checkout Page: ANKA Pay as a payment option during checkout.
![Checkout Page](https://github.com/afrikrea/wordpress-plugin/assets/32579534/8bd9b211-ff88-4d74-aff1-eab37535be38)

== Changelog ==
= 1.0.0 =
* Initial release

== Upgrade Notice ==
Upgrade normally
