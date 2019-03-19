<?php

namespace Tasks\Plugins\WpTask;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Promo options page class.
 */

class TaskPromo {

    public $admin_page = 'store_page_task-mass-promo-page';

    /**
     * Constructor.
     */
    public function __construct() {

        // hook to WP
        add_action( 'admin_menu', array( $this, 'add_submenu_admin_page' ) );
        // Load admin style sheet and JavaScript.
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
    }

    /**
     * Register and enqueue admin-specific style sheet.
     *
     * @since     1.0.0
     *
     * @return    null    Return early if no settings page is registered.
     */
    public function enqueue_admin_styles() {
        if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
            return;
        }
        $screen = get_current_screen();
        if ( $this->admin_page == $screen->base ) {
            wp_enqueue_style( 'wp-task-style', WP_TASK_PLUGIN_URL . 'admin/css/admin.css', array(), mt_rand( 10, 1000) );
        }
    }

    /**
     * Register and enqueue admin-specific javascript
     */
    public function enqueue_admin_scripts() {
        $screen = get_current_screen();
        if ( $this->admin_page == $screen->base ) {
            wp_enqueue_script( 'wp-task-admin-script', WP_TASK_PLUGIN_URL . 'admin/promo-app/assets/js/admin.js', array( 'jquery' ), mt_rand( 10, 1000 ) );
            wp_localize_script( 'wp-task-admin-script', 'task_object', array(
                    'api_nonce'   => wp_create_nonce( 'wp_rest' ),
                    'api_url'	  => 'http://localhost/wordpress/index.php/wp-json/wp/v2/',
//                    'api_url'	  => get_rest_url( null, 'wp/v2/' ),
                )
            );
        }
    }


    /**
     * Register sub menu page
     *
     * @see register_menu_pages
     */
    public function add_submenu_admin_page() {
        add_submenu_page(
            'task-store-page',
            __( 'Mass Promo', 'wp-task-admin' ),
            __( 'Mass Promo', 'wp-task-admin' ),
            'manage_options',
            'task-mass-promo-page',
            array( $this, 'display_store_callback' ),
        );
    }

    /**
     * Menu page display callback.
     *
     * @see register_menu_pages()
     */
    public function display_store_callback() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die(__('You do not have sufficient permissions to access this page.', 'wp-task-admin'));
        }
        ?>

        <div id="promo_root"></div>

        <?php
    }
}

/**
 * Instantiate.
 */
new TaskPromo();