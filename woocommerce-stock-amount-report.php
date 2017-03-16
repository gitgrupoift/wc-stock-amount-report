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

/**
* # WooCommerce Stock Amount Report Main Plugin Class
*
* ## Plugin Overview
*
* This plugin adds a new report to WooCommerce a new section in the WooCommerce Reports -> Stocks area called 'Stock amount'.
* The report visualizes how much you have in your available stock if you sell everything.
*
*/
class WC_StockAmount_Report {
  /** plugin version number */
  public static $version = '0.0.1';

  /** @var string the plugin file */
  public static $plugin_file = __FILE__;

  /** @var string the plugin file */
  public static $plugin_dir;

  /**
  * Initializes the plugin
  *
  * @since 0.0.1
  */
  public function init() {
    global $wpdb;

    self::$plugin_dir = dirname(__FILE__);

    // Add the reports layout to the WooCommerce -> Reports admin section
    add_filter('woocommerce_admin_reports',  __CLASS__ . '::initialize_location_admin_report', 12, 1);
  }

  /**
  * Add our location report to the WooCommerce stock reports array.
  *
  * @param array Array of All Report types & their labels
  * @return array Array of All Report types & their labels, including the 'Stock amount' report.
  * @since 0.0.1
  */
  public static function initialize_location_admin_report($report) {
    $report['stock']['reports']['stock_amount'] = array(
      'title'       => __('Sales by location', 'woocommerce-location-report'),
      'description' => '',
      'hide_title'  => true,
      'callback'    => array('WC_Admin_Reports', 'get_report'),
    );

    return $report;
  }
}

WC_StockAmount_Report::init();
