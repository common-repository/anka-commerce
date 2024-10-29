<?php

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

class Anka_Pay_API {
  private $api_url = 'https://api.anka.africa/v1/payment';
  private $api_token;
  const CENTLESS_CURRENCIES = array( 'XOF', 'XAF', 'RWF' );

  public function __construct( $api_token ) {
    $this->api_token = $api_token;
  }

  public function enable_webhook() {
    $webhook_url = esc_url(rest_url('anka-pay/v1/webhook'));

    $data = array(
      'type' => 'payment_webhooks',
      'attributes' => array(
        'webhook_url' => $webhook_url,
        'webhook_enabled' => true
      )
    );

    $body = wp_json_encode(array(
      'data' => $data
    ));

    $response = $this->send_post_request($this->api_url . '/webhook', $body);

    if (is_wp_error($response)) {
      error_log('ANKA Pay API Error: ' . print_r($response, true), 0);
    } else {
      $body = wp_remote_retrieve_body($response);
    }
  }

  public function create_payment_link($order) {
    $data = $this->build_payment_link_data($order);

    $body = wp_json_encode(array(
      'data' => $data
    ));

    $response = $this->send_post_request($this->api_url . '/links', $body);

    return $this->handle_payment_link_response($response);
  }

  private function build_payment_link_data($order) {
    $products = $order->get_items();

    return array(
      'type' => 'payment_links',
      'attributes' => array(
        'title' => 'Order #' . $order->get_id(),
        'description' => $this->payment_link_description($products),
        'amount_cents' => $this->amount_in_cents($order),
        'amount_currency' => get_woocommerce_currency(),
        'shippable' => $this->is_product_shippable($products),
        'reusable' => false,
        'callback_url' => esc_url($order->get_checkout_order_received_url()),
        'order_reference' => strval($order->get_id())
      )
    );
  }

  private function handle_payment_link_response($response) {
    if (is_wp_error($response)) {
      error_log('ANKA Pay API Error: ' . print_r($response, true), 0);
      return false;
    }

    $body = json_decode(wp_remote_retrieve_body($response));

    $status_code = wp_remote_retrieve_response_code($response);

    if ($status_code === 201) {
      return esc_url($body->redirect_url);
    } else {
      error_log('ANKA Pay API Error: ' . print_r($body, true), 0);
      return false;
    }
  }

  public function get_order( $order_id ) {
    $response = $this->send_get_request( $this->api_url . '/orders/' . $order_id );

    if ( is_wp_error( $response ) ) {
      error_log( 'ANKA Pay API Error: ' . print_r( $response, true ), 0 );
      return false;
    }

    $body = json_decode( wp_remote_retrieve_body( $response ) );

    $status_code = wp_remote_retrieve_response_code( $response );

    if ( $status_code === 200 ) {
      return $body->data;
    } else {
      error_log( 'ANKA Pay API Error: ' . print_r( $body, true ), 0 );
      return false;
    }
  }

  private function send_post_request( $url, $body ) {
    return wp_remote_post( $url, array(
      'body'    => $body,
      'headers' => array(
        'Content-Type'  => 'application/json',
        'Authorization' => 'Token ' . $this->api_token,
        'Accept'        => 'application/vnd.api+json',
        'charset'       => 'utf-8',
      ),
    ) );
  }

  private function send_get_request( $url ) {
    return wp_remote_get( $url, array(
      'headers' => array(
        'Authorization' => 'Token ' . $this->api_token,
      ),
    ) );
  }

  private function is_product_shippable( $products ) {
    foreach ( $products as $product ) {
      $product = wc_get_product( $product->get_product_id() );
      if ( !$product->is_virtual() && !$product->is_downloadable() ) {
        return true;
      }
    }

    return false;
  }

  private function payment_link_description( $products ) {
    $product_details = array();
    foreach ( $products as $product ) {
      $product_name = sanitize_text_field( $product->get_name() );
      $product_quantity = (int) $product->get_quantity();
      $product_details[] = $product_name . ' x ' . $product_quantity;
    }

    $description = implode( ', ', $product_details );

    return substr($description, 0, 250);
  }

  private function amount_in_cents( $order ) {
    $currency = get_woocommerce_currency();
    $cent_multiplier = in_array( $currency, self::CENTLESS_CURRENCIES ) ? 1 : 100;
    return intval( $order->get_total() * $cent_multiplier );
  }
}
?>
