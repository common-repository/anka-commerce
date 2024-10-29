<?php
use Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType;

/**
 * ANKA Pay Blocks integration
 *
 * @since 1.0.0
 */
final class Anka_Commerce_Gateway_Anka_Pay_Blocks_Support extends AbstractPaymentMethodType {

	/**
	 * The gateway instance.
	 *
	 * @var Anka_Commerce_Gateway_Anka_Pay
	 */
	private $gateway;

	/**
	 * Payment method name/id/slug.
	 *
	 * @var string
	 */
	protected $name = 'ankapay';

	/**
	 * Initializes the payment method type.
	 */
	public function initialize() {
		$this->settings = array_map( 'sanitize_text_field', get_option( 'woocommerce_ankapay_settings', [] ) );
		$gateways = WC()->payment_gateways->payment_gateways();
		$this->gateway = $gateways[ $this->name ];
	}

	/**
	 * Returns if this payment method should be active. If false, the scripts will not be enqueued.
	 *
	 * @return boolean
	 */
	public function is_active() {
		return $this->gateway->is_available();
	}

  /**
   * Returns an array of scripts/handles to be registered for this payment method.
   *
   * @return array
   */
  public function get_payment_method_script_handles() {
		$script_path       = '/build/block.js';
		$script_asset_path = Anka_Commerce_Anka_Pay_Woocommerce::anka_commerce_plugin_abspath() . 'build/block.asset.php';
		$script_asset      = file_exists( $script_asset_path )
			? require( $script_asset_path )
			: array(
				'dependencies' => array(),
				'version'      => '1.0.0'
			);
		$script_url        = esc_url( Anka_Commerce_Anka_Pay_Woocommerce::anka_commerce_plugin_url() . $script_path );

		wp_register_script(
			'anka_commerce_wc_anka_pay_blocks',
			$script_url,
			$script_asset[ 'dependencies' ],
			$script_asset[ 'version' ],
			true
		);

		if ( function_exists( 'wp_set_script_translations' ) ) {
			wp_set_script_translations( 'anka_commerce_wc_anka_pay_blocks', 'anka-commerce', Anka_Commerce_Anka_Pay_Woocommerce::anka_commerce_plugin_abspath() . 'languages/' );
		}

		return [ 'anka_commerce_wc_anka_pay_blocks' ];
  }

	/**
	 * Returns an array of key=>value pairs of data made available to the payment methods script.
	 *
	 * @return array
	 */
	public function get_payment_method_data() {
		return array(
			'title'       => sanitize_text_field( $this->get_setting( 'title' ) ),
			'description' => sanitize_text_field( $this->get_setting( 'description' ) ),
			'icon'        => esc_url( apply_filters( 'anka_commerce_ankapay_logo', ANKA_COMMERCE_PLUGIN_URL . 'assets/img/anka-pay-logo.png' ) ),
			'supports'    => array_filter( $this->gateway->supports, [ $this->gateway, 'supports' ] )
		);
	}
}
