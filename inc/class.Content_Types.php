<?php

namespace Tasks\Plugins\WpTask;

if ( !defined( 'ABSPATH' ) )
    exit;

/**
 * Custom content types.
 *
 * Register post types and taxonomies.
 */
class Content_Types {

    private static $instance;
    public $registered_post_types = array();
    public $registered_taxonomies = array();

    /**
     * Get instance of class.
     */
    public static function get_instance() {
        if ( !self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor.
     */
    private function __construct() {

        // hook to WP
        add_action( 'init', array( $this, 'register_post_types' ) );
        add_action( 'init', array( $this, 'register_taxonomies' ) );
    }

    /**
     * Register custom post types.
     *
     * @return void
     */
    public function register_post_types() {

        $post_types_to_register = array();

        /**
         *  Products
         */
        $labels = array(
            'name' => _x( 'Products', 'post type general name', 'wp-task-admin' ),
            'singular_name' => _x( 'Product', 'post type singular name', 'wp-task-admin' ),
            'add_new' => _x( 'Add New', 'task_product', 'wp-task-admin' ),
            'add_new_item' => __( 'Add New Product', 'wp-task-admin' ),
            'edit_item' => __( 'Edit Product', 'wp-task-admin' ),
            'new_item' => __( 'New Product', 'wp-task-admin' ),
            'all_items' => __( 'All Products', 'wp-task-admin' ),
            'view_item' => __( 'View Product', 'wp-task-admin' ),
            'search_items' => __( 'Search Products', 'wp-task-admin' ),
            'not_found' => __( 'No entries found', 'wp-task-admin' ),
            'not_found_in_trash' => __( 'No entries found in Trash', 'wp-task-admin' ),
            'menu_name' => __( 'Products', 'wp-task-admin' ),
        );

        $args = array(
            'labels' => $labels,
            'public' => true,
            'rewrite' => array(
                'slug' => 'products',
            ),
            'capability_type' => 'post',
            'menu_icon' => 'dashicons-cart',
            'supports' => array( 'title', 'editor', 'excerpt', 'revisions', 'thumbnail' ),
        );

        $post_types_to_register['task_product'] = array(
            'args' => $args,
        );

        /* register post types */
        foreach ( (array) $post_types_to_register as $post_type => $settings ) {
            register_post_type( $post_type, $settings['args'] );
            $this->registered_post_types[] = $post_type;
        }
    }

    /**
     * Register custom taxonomies.
     *
     * @return void
     */
    public function register_taxonomies() {

        $taxonomies_to_register = array();

        /** register taxonomies */
        foreach ( $taxonomies_to_register as $tax => $settings ) {
            register_taxonomy( $tax, $settings['object_type'], $settings['args'] );
            $this->registered_taxonomies[] = $tax;
        }
    }

    /**
     *
     * @return void
     */
    public function unregister_content_types() {

        foreach ( $this->registered_post_types as $post_type ) {
            unregister_post_type( $post_type );
        }

        foreach ( $this->registered_taxonomies as $tax ) {
            unregister_taxonomy( $tax );
        }
    }

    /**
     * Plugin activation
     *
     * @return void
     */
    public static function activate() {

        $instance = self::get_instance();
        $instance->register_post_types();
        $instance->register_taxonomies();

        flush_rewrite_rules();
    }

    /**
     * Plugin deactivation
     *
     * @return void
     */
    public static function deactivate() {

        $instance = self::get_instance();
        $instance->unregister_content_types();

        flush_rewrite_rules();
    }
}

/**
 * Register actitavion/deactivation hooks.
 */
register_activation_hook( WP_TASK_PLUGIN_FILE, [ __NAMESPACE__ . '\Content_Types', 'activate' ] );
register_deactivation_hook( WP_TASK_PLUGIN_FILE, [ __NAMESPACE__ . '\Content_Types', 'deactivate' ] );

/**
 * Instantiate.
 */
Content_Types::get_instance();