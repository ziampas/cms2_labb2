<?php
/**
 * Plugin Name: CMS Labb 2 Most sold
 * Description: CMS 2 Labb 2 Uppgift 1
 * Author: Kristian Ziampas Olausson
 * Author URI: http://iths.se
 */

if (!defined("ABSPATH")) {
    // Se till att inte filen laddas direkt
    exit;
}

// Verifiera att woocommerce är aktiverat
if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {

    add_action('plugins_loaded', 'cms2_most_sold');
    function cms2_most_sold()
    {
            
                $args = array(
                    'limit'     => '10',
                    'orderby'   => array( 'meta_value_num' => 'DESC', 'title' => 'ASC' ),
                    'meta_key'  => 'total_sales',
                );

                $products = wc_get_products( $args );
                
                echo '<dl>';
                if (!empty($products)) {
                    foreach ($products as $product) {
                        echo '<dt>' . $product->get_name() . ' ' . $product->get_price() . ':-';
                        echo '</dt>';
                        echo '<dd>' . $product->get_description();
                        echo '</dd>';                    }
                } else {
                    echo ( 'No products found' );
                }
                echo '</dl>';
             add_shortcode('toplist', 'cms2_most_sold');
               }
           
    
}

