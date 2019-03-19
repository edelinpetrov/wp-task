<?php

namespace Tasks\Plugins\WpTask;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Store options page class.
 */

class TaskStore {

    private static $instance = null;

    public static $admin_page = 'toplevel_page_task-store-page';

    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor.
     */
    public function __construct() {
        $admin_page = self::$admin_page;
        // Include the screen options framework.
        require_once WP_TASK_PLUGIN_PATH . 'inc/class.ScreenOptionsFramework.php';
        add_action( 'admin_menu', array( $this, 'add_admin_page' ) );
    }

    public function options() {
        return array(
            'product_price',
            'product_quantity',
            'product_stock',
            'product_promo_start',
            'product_promo_end',
            'product_promo_price',
        );
    }

    /**
     * Adds the admin page.
     */
    public function add_admin_page() {
        add_menu_page(
            __( 'Store', 'wp-task-admin' ),
            __( 'Store', 'wp-task-admin' ),
            'manage_options',
            'task-store-page',
            array( $this, 'display_store_callback' ),
            'dashicons-store',
            15
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

        $screen    = get_current_screen();
        $user_meta = get_user_meta( get_current_user_id(), 'task_store_screen_options', true );

        $query_args = array(
            'post_type'         => 'task_product',
            'posts_per_page'    => -1,
            'order_by'           => 'title',
            'order'             => 'ASC',
        );

        query_posts( $query_args );

        ob_start();
        ?>

        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

        <?php if ( have_posts() ) { ?>

            <table class="widefat">
                <thead>
                    <tr>
                        <th><strong>Name</strong></th>

                        <?php foreach ( $this->options() as $option_name ) {
                            $option = "store_screen_option_$option_name";
                            if ( $user_meta ) {
                                $user_value = array_key_exists( $option_name, $user_meta );
                            } else {
                                $user_value = $screen->get_option( $option, 'value' );
                            }

                            if ( $user_value ) { ?>
                                <th>
                                    <strong><?php echo esc_attr( ucwords( str_replace('_', ' ', $option_name ) ) ); ?></strong>
                                </th>
                            <?php
                            }
                        } ?>
                    </tr>
                </thead>
                <tbody>

                <?php while ( have_posts() ) { the_post(); ?>

                    <tr>
                        <td><?php the_title(); ?></td>

                        <?php foreach ( $this->options() as $option_name ) {
                            $option = "store_screen_option_$option_name";
                            if ( $user_meta ) {
                                $user_value = array_key_exists( $option_name, $user_meta );
                            } else {
                                $user_value = $screen->get_option( $option, 'value' );
                            }

                            if ( $user_value ) { ?>
                                <td>
                                    <?php echo get_post_meta( get_the_ID(), $option_name, true ); ?>
                                </td>
                                <?php
                            }
                        } ?>

                    </tr>

                <?php }

                echo '</tbody>';
            echo '</table>';

        } else {
            echo 'No products were found.';
        }

        wp_reset_query();
        ob_get_flush();
    }
}

/**
 * Instantiate.
 */
new TaskStore();