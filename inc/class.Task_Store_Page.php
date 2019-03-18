<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Store Options page class.
 */

class Task_Store_Page {

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
        require_once WP_TASK_PLUGIN_PATH . 'inc/class.Screen_Options_Framework.php';
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
        $user_meta = get_user_meta( get_current_user_id(), '_store_screen_options' );

        $query_args = array(
            'post_type'         => 'task_product',
            'posts_per_page'    => -1,
            'orderby'           => 'title',
            'order'             => 'ASC',
        );

        query_posts( $query_args );

        ob_start();

        foreach ( $this->options() as $option_name ) {
            $option     = "_store_screen_options_$option_name";
            if ( $user_meta ) {
                $user_value = isset( $user_meta[ $option_name ] ) ? 'true' : 'false';
            } else {
                $user_value = var_export( $screen->get_option( $option, 'value' ), true );
            }
            ?>
            <li class="<?php echo esc_attr( $option_name ); ?>-option">
                <strong><?php echo esc_attr( ucwords( $option_name ) ); ?>:</strong> <code><?php echo esc_html( $user_value ); ?></code>
            </li>
        <?php } ?>



        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

        <?php if ( have_posts() ) { ?>

            <table class="widefat">
            <thead>
            <tr>
                <th><strong>Name</strong></th>
                <th><strong>Price</strong></th>
                <th><strong>Quantity</strong></th>
                <th><strong>Stock</strong></th>
                <th><strong>Promo Start Date</strong></th>
                <th><strong>Promo End Date</strong></th>
                <th><strong>Promo Price</strong></th>
            </tr>
            </thead>
            <tbody>

            <?php while ( have_posts() ) { the_post(); ?>

                <tr>
                    <th><?php the_title(); ?></th>
                    <th>
                        <?php echo esc_attr( get_post_meta( get_the_ID(), 'product_price', true ) ); ?>
                    </th>
                    <th>
                        <?php echo esc_attr( get_post_meta( get_the_ID(), 'product_quantity', true ) ); ?>
                    </th>
                    <th>
                        <?php echo esc_attr( get_post_meta( get_the_ID(), 'product_stock', true ) ); ?>
                    </th>
                    <th>
                        <?php echo esc_attr( get_post_meta( get_the_ID(), 'product_promo_start', true ) ); ?>
                    </th>
                    <th>
                        <?php echo esc_attr( get_post_meta( get_the_ID(), 'product_promo_end', true ) ); ?>
                    </th>
                    <th>
                        <?php echo esc_attr( get_post_meta( get_the_ID(), 'product_promo_price', true ) ); ?>
                    </th>
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
new Task_Store_Page();