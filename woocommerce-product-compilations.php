<?php
/**
 * Plugin Name: WooCommerce Product Compilations
 * Version: 1.0.0
 * Plugin URI: http://www.hughlashbrooke.com/
 * Description: This is your starter template for your next WordPress plugin.
 * Author: Hugh Lashbrooke
 * Author URI: http://www.hughlashbrooke.com/
 * Requires at least: 4.0
 * Tested up to: 4.0
 *
 * Text Domain: woocommerce-product-compilations
 * Domain Path: /lang/
 *
 * @package WordPress
 * @author Hugh Lashbrooke
 * @since 1.0.0
 */

require "vendor/autoload.php";

if (!defined("ABSPATH")) {
  exit();
}

// Load plugin class files.
require_once "includes/class-woocommerce-product-compilations.php";
require_once "includes/class-woocommerce-product-compilations-settings.php";

// Load plugin libraries.
require_once "includes/lib/class-woocommerce-product-compilations-admin-api.php";

// Load DB functions
require_once ABSPATH . "wp-admin/includes/upgrade.php";

/**
 * Returns the main instance of WooCommerce_Product_Compilations to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return object WooCommerce_Product_Compilations
 */
function woocommerce_product_compilations()
{
  $instance = WooCommerce_Product_Compilations::instance(__FILE__, "1.0.0");

  if (is_null($instance->settings)) {
    $instance->settings = WooCommerce_Product_Compilations_Settings::instance(
      $instance
    );
  }

  return $instance;
}

woocommerce_product_compilations();

register_activation_hook(__FILE__, "woocommerce_product_compilations_activate");
function woocommerce_product_compilations_activate()
{
  // Check WooCommerce is active
  if (!class_exists("WooCommerce")) {
    echo "<h3>" .
      __(
        'Please install WooCommerce plugin. <a target="_blank" href="https://wordpress.org/plugins/woocommerce/">WooCommerce plugin page</a>',
        "ap"
      ) .
      "</h3>";

    //Adding @ before will prevent XDebug output
    @trigger_error(
      __("Please install WooCommerce plugin.", "ap"),
      E_USER_ERROR
    );
  }

  global $wpdb;

  $sql = "
CREATE TABLE IF NOT EXISTS `wp_compilations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (user_id)
          REFERENCES wp_users(ID)
          ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
";

  dbDelta($sql);

  $sql = "
CREATE TABLE IF NOT EXISTS `wp_compilation_post` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `compilation_id` bigint(20) unsigned NOT NULL,
  `post_id` bigint(20) unsigned NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (compilation_id)
          REFERENCES wp_compilations(ID)
          ON DELETE CASCADE,
  FOREIGN KEY (post_id)
          REFERENCES wp_posts(id)
          ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
";

  dbDelta($sql);
}
