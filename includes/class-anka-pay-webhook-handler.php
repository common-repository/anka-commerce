<?php

/**
 * ANKA Pay Webhook Handler to handle ANKA Pay payment status updates from incoming webhooks
 *
 * @package  WooCommerce ANKA Pay Gateway
 * @since    1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

class Anka_Pay_Webhook_Handler {
  public function __construct() {
    include_once ANKA_COMMERCE_PLUGIN_DIR . 'includes/class-anka-pay-api.php';
  }

  public function handle_webhook(WP_REST_Request $request) {
    $request_data = $request->get_json_params();

    if (!empty($request_data) && isset($request_data['data'])) {
      $response = $this->process_webhook_data($request_data);
      return $response;
    }

    return new WP_REST_Response(['error' => 'Invalid webhook data'], 400);
  }

  private function process_webhook_data($data) {
    if ($this->is_invalid_webhook_data($data)) {
      return new WP_REST_Response(['error' => 'Invalid webhook data structure'], 400);
    }

    $attributes = $data['data']['attributes'];

    if ($this->is_missing_required_data($attributes)) {
      return new WP_REST_Response(['error' => 'Missing required data'], 400);
    }

    $order = $this->get_order($attributes['internal_reference']);

    if (!$order) {
      return new WP_REST_Response(['error' => 'Order not found'], 404);
    }

    $order_status = $this->get_order_status_from_api($order);

    if (!$order_status) {
      return new WP_REST_Response(['error' => 'Failed to retrieve order data'], 400);
    }

    return $this->update_order_status($order, $order_status);
  }

  private function is_invalid_webhook_data($data) {
    return !isset($data['data']['attributes']) || $data['data']['type'] !== 'payment_links_orders';
  }

  private function is_missing_required_data($attributes) {
    return !isset($attributes['internal_reference']) || !isset($attributes['status']);
  }

  private function get_order($internal_reference) {
    $order_id = sanitize_text_field($internal_reference);
    return wc_get_order($order_id);
  }

  private function get_order_status_from_api($order) {
    $ankapay_api = new Anka_Pay_API(sanitize_text_field(get_option('woocommerce_ankapay_settings')['api_token']));
    $data = $ankapay_api->get_order($order->get_id());

    return $data && isset($data->attributes) ? $data->attributes->status : null;
  }

  private function update_order_status($order, $status) {
    switch (sanitize_text_field($status)) {
      case 'initial':
        $order->update_status('pending', __('Awaiting ANKA Pay payment', 'anka-commerce'));
        break;
      case 'verifying':
        wc_reduce_stock_levels($order->get_id());
        $order->update_status('on-hold', __('Verifying ANKA Pay payment', 'anka-commerce'));
        break;
      case 'captured':
        $order->payment_complete();
        break;
      case 'failed':
        $order->update_status('failed', __('ANKA Pay payment failed', 'anka-commerce'));
        break;
      default:
        return new WP_REST_Response(['error' => 'Unknown payment status'], 400);
    }

    return new WP_REST_Response(['success' => 'Webhook handled successfully'], 200);
  }
}
?>
