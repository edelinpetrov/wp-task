<?php

namespace Tasks\Plugins\WpTask;

if ( !defined( 'ABSPATH' ) )
    exit;

/**
 * Custom content meta.
 *
 * Register custom meta fields
 */
class ContentMeta {

    /**
     * Constructor.
     */
    public function __construct() {

        // hook to WP
        add_action( 'add_meta_boxes', array( $this, 'register_product_metabox' ) );
        add_action( 'save_post', array( $this, 'save_product_metafields' ) );
    }

    /**
     * Register meta boxes.
     */
    public function register_product_metabox() {
        add_meta_box(
            'task-prod-meta',
            __( 'Product Meta', 'wp-task-admin' ),
            array( $this, 'display_product_meta_callback' ),
            'task_product'
        );
    }

    /**
     * Meta box display callback.
     *
     * @param WP_Post $post Current post object.
     */
    public function display_product_meta_callback( $post ) {
        include WP_TASK_PLUGIN_PATH . 'admin/partials/product-meta.php';
    }

    /**
     * Save meta box content.
     *
     * @param int $post_id Post ID
     */
    function save_product_metafields( $post_id ) {
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        if ( $parent_id = wp_is_post_revision( $post_id ) ) {
            $post_id = $parent_id;
        }

        $fields = array(
            'product_price',
            'product_quantity',
            'product_stock',
            'product_promo_start',
            'product_promo_end',
            'product_promo_price',
        );

        foreach ( $fields as $field ) {
            if ( array_key_exists( $field, $_POST ) ) {
                update_post_meta( $post_id, $field, sanitize_text_field( $_POST[ $field ] ) );
            }
        }
    }
}

/**
 * Instantiate.
 */
new ContentMeta();