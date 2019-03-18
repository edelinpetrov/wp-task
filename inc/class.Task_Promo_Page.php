<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Promo Options page class.
 */

class Task_Promo_Page {

    /**
     * Constructor.
     */
    public function __construct() {

        // hook to WP
        add_action( 'admin_menu', array( $this, 'register_menu_pages' ) );
    }

    /**
     * Register menu pages
     *
     * @see add_menu_page() & add_submenu_page()
     */
    public function register_menu_pages() {

        add_submenu_page(
            'task-store-page',
            __( 'Mass Promo', 'wp-task-admin' ),
            __( 'Mass Promo', 'wp-task-admin' ),
            'manage_options',
            'task-mass-promo-page',
            'display_mass_promo_callback'
        );
    }

    /**
     * Menu page display callback.
     *
     * @see register_menu_pages()
     */
    public function display_mass_promo_callback() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die(__('You do not have sufficient permissions to access this page.', 'wp-task-admin'));
        }

    }
}