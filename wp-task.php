<?php

/**
 * Plugin Name: WP Products Task
 * Description: Task for job application
 * Version: 1.0
 * Author: edelin

 * Text Domain: wp_task
 * Domain Path: /lang
 */
namespace  Tasks\Plugins\WpTask;

if ( ! defined( 'ABSPATH' ) )
    exit;

/**
 * Define constants
 */
define( 'WP_TASK_PLUGIN_FILE', __FILE__ );
define( 'WP_TASK_PLUGIN_PATH', plugin_dir_path( WP_TASK_PLUGIN_FILE ) );
define( 'WP_TASK_PLUGIN_URL', plugin_dir_url( WP_TASK_PLUGIN_FILE ) );

/**
 * i18n
 *
 * @return void
 */
function i18n() {

    $domain = 'wp_task';
    $plugin_dirname = dirname( plugin_basename( WP_TASK_PLUGIN_FILE ) );

    load_plugin_textdomain( $domain, FALSE, $plugin_dirname . '/lang/' );
}
add_action( 'plugins_loaded', __NAMESPACE__ . '\i18n' );

/**
 * Include plugin functions
 */

/**
 * Include plugin classes
 */
require_once WP_TASK_PLUGIN_PATH . 'inc/class.Content_Types.php';
require_once WP_TASK_PLUGIN_PATH . 'inc/class.Content_Meta.php';
require_once WP_TASK_PLUGIN_PATH . 'inc/class.Task_Store_Page.php';

