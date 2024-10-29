<?php
/*
 * Plugin Name:       ANKA Commerce
 * Description:       Accept payments through ANKA Pay on your WooCommerce store using Credit Cards, Mobile Money, Nigerian Bank Transfer, and PayPal.
 * Version:           1.0.0
 * Text Domain:       anka-commerce
 * Domain Path:       /languages
 * License:           GPL-3.0+
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ANKA_COMMERCE_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'ANKA_COMMERCE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'ANKA_COMMERCE_VERSION', '1.0.0' );

/**
 * ANKA Pay payment gateway plugin class for WooCommerce.
 *
 * @class Anka_Commerce_Anka_Pay_Woocommerce
 */
class Anka_Commerce_Anka_Pay_Woocommerce {

	/**
	 * Plugin bootstrapping.
	 */
	public static function init() {
		// ANKA Pay gateway class.
		add_action( 'plugins_loaded', array( __CLASS__, 'anka_commerce_includes' ), 0 );

		// Make the ANKA Pay gateway available to WC.
		add_filter( 'woocommerce_payment_gateways', array( __CLASS__, 'anka_commerce_add_gateway' ) );

		// Registers WooCommerce Blocks integration.
		add_action( 'woocommerce_blocks_loaded', array( __CLASS__, 'anka_commerce_woocommerce_gateway_block_support' ) );

		// Declare compatibility with WooCommerce Blocks.
		add_action( 'before_woocommerce_init', array( __CLASS__, 'anka_commerce_cart_checkout_blocks_compatibility' ) );

		// Enqueue scripts and styles.
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'anka_commerce_enqueue_scripts' ) );

		// Load plugin text domain.
		add_action( 'plugins_loaded', array( __CLASS__, 'anka_commerce_load_plugin_textdomain' ) );

		// Add settings link on plugin page
		add_filter('plugin_action_links_' . plugin_basename(__FILE__), array(__CLASS__, 'anka_commerce_add_plugin_page_settings_link'));
	}

	/**
	 * Add ANKA Pay gateway to the list of available gateways.
	 *
	 * @param array $gateways
	 * @return array
	 */
	public static function anka_commerce_add_gateway( $gateways ) {
		$gateways[] = 'Anka_Commerce_Gateway_Anka_Pay';
		return $gateways;
	}

	/**
	 * Plugin includes.
	 */
	public static function anka_commerce_includes() {
		// Make the Anka_Commerce_Gateway_Anka_Pay class available.
		if ( class_exists( 'WC_Payment_Gateway' ) ) {
			require_once ANKA_COMMERCE_PLUGIN_DIR . 'includes/class-anka-commerce-gateway-anka-pay.php';
		}
	}

	/**
	 * Plugin url.
	 *
	 * @return string
	 */
	public static function anka_commerce_plugin_url() {
		return untrailingslashit( plugins_url( '/', __FILE__ ) );
	}

	/**
	 * Plugin abspath.
	 *
	 * @return string
	 */
	public static function anka_commerce_plugin_abspath() {
		return trailingslashit( plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Registers WooCommerce Blocks integration.
	 *
	 */
	public static function anka_commerce_woocommerce_gateway_block_support() {
		if ( class_exists( 'Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType' ) ) {
			require_once ANKA_COMMERCE_PLUGIN_DIR . 'includes/class-anka-commerce-gateway-anka-pay-blocks-support.php';
			add_action(
				'woocommerce_blocks_payment_method_type_registration',
				function( Automattic\WooCommerce\Blocks\Payments\PaymentMethodRegistry $payment_method_registry ) {
					$payment_method_registry->register( new Anka_Commerce_Gateway_Anka_Pay_Blocks_Support() );
				}
			);
		}
	}

	/**
	 * Declare compatibility with WooCommerce Blocks.
	 *
	 */
	public static function anka_commerce_cart_checkout_blocks_compatibility() {
		if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility(
				'cart_checkout_blocks',
				__FILE__,
				true
			);
		}
	}

	/**
	 * Enqueue scripts and styles.
	 */
	public static function anka_commerce_enqueue_scripts() {
		if ( is_checkout() ) {
			wp_enqueue_style( 'ankapay-style', esc_url( ANKA_COMMERCE_PLUGIN_URL . 'assets/css/style.css' ), array(), ANKA_COMMERCE_VERSION );
		}
	}

	/**
	 * Load plugin textdomain.
	 */
	public static function anka_commerce_load_plugin_textdomain() {
		load_plugin_textdomain( 'anka-commerce', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}

	/**
	 * Add settings link on plugin page.
	 *
	 * @param array $links
	 * @return array
	 */
	public static function anka_commerce_add_plugin_page_settings_link( $links ) {
		$settings_link = '<a href="' . esc_url(admin_url('admin.php?page=wc-settings&tab=checkout&section=ankapay')) . '">' . esc_html__('Settings', 'anka-commerce') . '</a>';
		array_unshift($links, $settings_link);
		return $links;
	}
}

Anka_Commerce_Anka_Pay_Woocommerce::init();
?>
