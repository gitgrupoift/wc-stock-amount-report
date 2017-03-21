<?php
if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly
}

if (!class_exists('WP_List_Table')) {
  require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

/**
* WC_Report_Stock_Amount
*
* @author      Thiago Guimarães (https://github.com/thiagogsr)
* @category    Admin
* @package     WooCommerce/Admin/Reports
* @version     0.0.1
*/

class WC_Report_Stock_Amount extends WP_List_Table {
  private $max_items;

  /**
   * Output the report.
   */
  public function output_report() {
    $this->prepare_items();
    $this->display_total();

    echo '<div id="poststuff" class="woocommerce-reports-wide">';
    $this->display();
    echo '</div>';
  }

  public function display_total() {
    $total = 0;

    foreach ($this->items as $item) {
      $product = wc_get_product($item->id);
      $total += $this->product_total_price($product);
    }

    echo '<br />';
    echo '<span class="title-count">';
    echo __('Total', 'woocommerce') . ' ' . wc_price($total);
    echo '</span>';
  }

  /**
   * Calculate the product total price.
   *
   * @param string $product
   */
  private function product_total_price($product) {
    return $product->get_stock_quantity() * $product->get_price();
  }

  /**
   * Don't need this.
   *
   * @param string $position
   */
  public function display_tablenav($position) {
    if ('top' !== $position) {
      parent::display_tablenav($position);
    }
  }

  /**
   * Get column value.
   *
   * @param mixed $item
   * @param string $column_name
   */
  public function column_default($item, $column_name) {
    global $product;

    if (!$product || $product->get_id() !== $item->id) {
      $product = wc_get_product($item->id);
    }

    switch ($column_name) {
      case 'product':
        if ($sku = $product->get_sku()) {
          echo esc_html($sku) . ' - ';
        }
        echo esc_html($product->get_title());
      break;

      case 'stock_level':
        echo esc_html($product->get_stock_quantity());
      break;

      case 'price':
        echo wc_price($product->get_price());
      break;

      case 'total_price':
        echo wc_price($this->product_total_price($product));
      break;
    }
  }

  /**
   * Get columns.
   *
   * @return array
   */
  public function get_columns() {
    return array(
      'product'     => __('Product', 'woocommerce'),
      'stock_level' => __('Units in stock', 'woocommerce'),
      'price'  => __('Price', 'woocommerce'),
      'total_price' => __('Total Price', 'woocommerce')
    );
  }

  /**
   * Prepare customer list items.
   */
  public function prepare_items() {
    $this->_column_headers = array($this->get_columns(), array(), $this->get_sortable_columns());
    $this->get_items();
  }

  /**
   * Get all data needed for this report and store in the class.
   */
  private function get_items() {
    global $wpdb;

    $this->items = array();

    $query_from = "FROM {$wpdb->posts} as posts
      INNER JOIN {$wpdb->postmeta} AS postmeta ON posts.ID = postmeta.post_id
      INNER JOIN {$wpdb->postmeta} AS postmeta2 ON posts.ID = postmeta2.post_id
      WHERE 1=1
      AND posts.post_type = 'product_variation'
      AND posts.post_status = 'publish'
      AND postmeta2.meta_key = '_manage_stock' AND postmeta2.meta_value = 'yes'
      AND postmeta.meta_key = '_stock' AND CAST(postmeta.meta_value AS SIGNED) > '0'
    ";

    $this->items = $wpdb->get_results("SELECT posts.ID as id {$query_from} GROUP BY posts.ID ORDER BY CAST(postmeta.meta_value AS SIGNED) DESC");
  }
}
