<?php

namespace Tasks\Plugins\WpTask;

/**
 * REST_Controller
 */
class TaskEndpoints {

    /**
     * Initialize
     *
     */
    public function __construct() {

        // hook to WP
        add_action( 'rest_api_init', array( $this, 'register_routes' ) );
    }

    /**
     * Register the routes for the objects of the controller.
     */
    public function register_routes() {

        register_rest_field( 'task_product',
            'product_metadata',
            array(
                'get_callback'      => array( $this, 'rest_get_field' ),
                'update_callback'   => array( $this, 'rest_update_field' ),
            )
        );
    }

    public function rest_get_field( $object ) {
        $post_id = $object['id'];

        return get_post_meta( $post_id );
    }

    public function rest_update_field( $value, $post, $field_name ) {
        var_dump( $value, $post, $field_name );
        exit;
        if ( ! $value || ! is_string ($value ) ) {
            return new WP_Error(
                'rest_product_meta_failed',
                __( 'Failed to update product meta.' ),
                array( 'status' => 500 )
            );
        }

        return update_post_meta( $post->ID, $field_name, strip_tags( $value ) );
    }

    /**
     * Check if a given request has access to update a setting
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|bool
     */
    public function permissions_check( $request ) {
        return current_user_can( 'manage_options' );
    }
}

/**
 * Instantiate.
 */
new TaskEndpoints();