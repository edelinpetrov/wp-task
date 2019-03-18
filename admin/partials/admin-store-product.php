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
