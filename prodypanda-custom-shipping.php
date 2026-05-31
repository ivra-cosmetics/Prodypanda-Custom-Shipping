<?php
/**
 * Plugin Name: Prodypanda Custom Shipping
 * Description: A strictly-coded WooCommerce shipping method plugin boilerplate.
 * Version: 1.0.0
 * Author: Naserdine
 * Text Domain: prodypanda-custom-shipping
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'woocommerce_shipping_init', 'prodypanda_init_custom_shipping_method' );

function prodypanda_init_custom_shipping_method() {
    if ( ! class_exists( 'WC_Prodypanda_Shipping_Method' ) ) {
        class WC_Prodypanda_Shipping_Method extends WC_Shipping_Method {

            public function __construct( $instance_id = 0 ) {
                $this->id                 = 'prodypanda_shipping';
                $this->instance_id        = absint( $instance_id );
                $this->method_title       = esc_html__( 'Prodypanda Shipping', 'prodypanda-custom-shipping' );
                $this->method_description = esc_html__( 'Custom shipping method for WooCommerce.', 'prodypanda-custom-shipping' );
                $this->supports           = array(
                    'shipping-zones',
                    'instance-settings',
                    'instance-settings-modal',
                );

                $this->init();
            }

            public function init() {
                $this->init_form_fields();
                $this->init_settings();

                $this->title = $this->get_option( 'title', esc_html__( 'Prodypanda Shipping', 'prodypanda-custom-shipping' ) );
                $this->cost  = $this->get_option( 'cost', '10.00' );

                add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
            }

            public function init_form_fields() {
                $this->instance_form_fields = array(
                    'title' => array(
                        'title'       => esc_html__( 'Method Title', 'prodypanda-custom-shipping' ),
                        'type'        => 'text',
                        'description' => esc_html__( 'This controls the title which the user sees during checkout.', 'prodypanda-custom-shipping' ),
                        'default'     => esc_html__( 'Prodypanda Shipping', 'prodypanda-custom-shipping' ),
                        'desc_tip'    => true,
                    ),
                    'cost' => array(
                        'title'       => esc_html__( 'Cost', 'prodypanda-custom-shipping' ),
                        'type'        => 'text',
                        'description' => esc_html__( 'Set the cost for this shipping method.', 'prodypanda-custom-shipping' ),
                        'default'     => '10.00',
                        'desc_tip'    => true,
                    ),
                );
            }

            public function calculate_shipping( $package = array() ) {
                $rate = array(
                    'id'      => $this->get_rate_id(),
                    'label'   => $this->title,
                    'cost'    => $this->cost,
                    'package' => $package,
                );

                $this->add_rate( $rate );
            }
        }
    }
}

add_filter( 'woocommerce_shipping_methods', 'prodypanda_add_custom_shipping_method' );

function prodypanda_add_custom_shipping_method( $methods ) {
    $methods['prodypanda_shipping'] = 'WC_Prodypanda_Shipping_Method';
    return $methods;
}
