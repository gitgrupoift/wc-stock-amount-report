<?php
/**
* Plugin Name: WooCommerce Stock Amount
* Plugin URI: https://github.com/thiagogsr/woocommerce-stock-amount-report
* Description: WooCommerce report to visualize how much you have in your available stock if you sale everything.
* Author: Thiago Guimarães
* Author URI: https://github.com/thiagogsr
* Version: 0.0.1
* Text Domain: wc_stock_amount_report
* Domain Path: /languages/
*
* License: GNU General Public License v3.0
* License URI: http://www.gnu.org/licenses/gpl-3.0.html
*
* @package   WC-Stock-Amount-Report
* @author    Thiago Guimarães Santa Rosa
* @category  Reports
* @copyright Thiago Guimarães
* @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
*/

if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly
}

// Check if WooCommerce is active
if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
  return;
}
