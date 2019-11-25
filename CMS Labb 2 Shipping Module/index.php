<?php
/*
Plugin Name: CMS Labb 2 Shipping Module
Description: CMS Labb 2 Uppgift 3
Author: Kristian Ziampas Olausson
Author URI: https://iths.se
*/
/**
 * Check if WooCommerce is active
 */
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	function your_shipping_method_init() {
		if ( ! class_exists( 'WC_Your_Shipping_Method' ) ) {
			class WC_Your_Shipping_Method extends WC_Shipping_Method {
				/**
				 * Constructor for your shipping class
				 *
				 * @access public
				 * @return void
				 */
				public function __construct() {
					$this->id                 = 'your_shipping_method'; // Id for your shipping method. Should be uunique.
					$this->method_title       = __( 'Your Shipping Method' );  // Title shown in admin
					$this->method_description = __( 'Description of your shipping method' ); // Description shown in admin
					$this->enabled            = "yes"; // This can be added as an setting but for this example its forced enabled
					$this->title              = "My Shipping Method"; // This can be added as an setting but for this example its forced.
					$this->init();
				}
				/**
				 * Init your settings
				 *
				 * @access public
				 * @return void
				 */
				function init() {
					// Load the settings API
					$this->init_form_fields(); // This is part of the settings API. Override the method to add your own settings
					$this->init_settings(); // This is part of the settings API. Loads settings you previously init.
					// Save settings in admin if you have any defined
					add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
				}
				/**
				 * calculate_shipping function.
				 *
				 * @access public
				 * @param mixed $package
				 * @return void
				 */
				public function calculate_shipping( $package=array() )  {
					//Först börjar vi med att sätta vikt och kostnad till 0

					$weight = 0;
					$cost = 0;

					//Här loopar vi igenom varukorgen för att räkna ut sammanlagd vikt på alla varor och lägger sedan totalvikten i $weightvariabel
					foreach ( $package['contents'] as $item_id => $values ) 
					{ 
						$_product = $values['data']; 
						$weight = $weight + $_product->get_weight() * $values['quantity']; 
					}

					//If-satser för att räkna ut totalkostnad på frakt

					if( $weight <= 1 ) {
 
						$cost = 30;
				  
					} elseif( $weight <= 5 ) {
				  
						$cost = 60;
				  
					} elseif( $weight <= 10 ) {
				  
						$cost = 100;
				  
					} elseif ( $weight <= 20 ) {
						$cost = 200;

					} else {
						$cost = $weight * 10; //om inte paketet väger mindre än 1 kg, eller mindre än 20, väger paketet >20
					}

					$weight = wc_get_weight( $weight, 'kg' ); //konverterar vikten till kilo

					//retunerar fraktkostnaden där cost är variabeln $cost, som bestäms genom våra olika IF-påståenden

					$rate = array(
						'id' => $this->id,
						'label' => $this->title,
						'cost' => $cost,
						'calc_tax' => 'per_item'
					);
					// Register the rate
					$this->add_rate( $rate );
				}
			}
		}
	}
	add_action( 'woocommerce_shipping_init', 'your_shipping_method_init' );
	function add_your_shipping_method( $methods ) {
		$methods['your_shipping_method'] = 'WC_Your_Shipping_Method';
		return $methods;
	}
	add_filter( 'woocommerce_shipping_methods', 'add_your_shipping_method' );
}