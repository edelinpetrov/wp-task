<?php

namespace Tasks\Plugins\WpTask;

/**
 * REST_Controller
 */
class TaskEndpoints {

    /**
     * Initialize
     */
    public function __construct() {

        // hook to WP
        add_action( 'rest_api_init', array( $this, 'register_routes' ) );
    }

    /**
     * Register the routes
     */
    public function register_routes() {

        foreach ( TaskStore::get_instance()->options() as $option_name ) {
            register_rest_field( 'task_product',
                $option_name,
                array(
                    'get_callback'      => function ( $params)  use ( $option_name ) {
                        return \get_post_meta( $params['id'], $option_name, true );
                    },
                )
            );
        }

        register_rest_route( '/wp/v2', '/promo/', array(
            array(
                'methods'               => \WP_REST_Server::CREATABLE,
                'callback'              => array( $this, 'update_callback' ),
                'permission_callback'   => array( $this, 'permissions_check' ),
                'args'                  => array(),
            ),
        ) );
        register_rest_route( '/wp/v2', '/promo/', array(
            array(
                'methods'               => \WP_REST_Server::EDITABLE,
                'callback'              => array( $this, 'update_callback' ),
                'permission_callback'   => array( $this, 'permissions_check' ),
                'args'                  => array(),
            ),
        ) );
    }

    /**
     * Create OR Update
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return \WP_REST_Response
     */
    public function update_callback( $request ) {
        $promo_product = $request->get_param( 'promo_product' );

        if ( is_array( $promo_product ) && ! empty( $promo_product ) && isset( $promo_product['id'] ) ) {
            foreach ( $promo_product as $key => $value ) {
                if ( strpos( $key, 'promo' ) !== false ) {
                    update_post_meta(
                        intval( $promo_product['id'] ),
                        'product_' . $key,
                        $value
                    );
                } else {
                    continue;
                }
            }

            return new \WP_REST_Response( array(
                'success'   => true,
            ), 200 );
        }
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