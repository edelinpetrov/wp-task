<div>
    <style scoped>
        .product_meta_box{
            display: grid;
            grid-template-columns: max-content 1fr;
            grid-row-gap: 10px;
            grid-column-gap: 20px;
        }
        .task_field{
            display: contents;
        }
    </style>

    <div class="product_meta_box">
        <p class="meta-options task_field">
            <label for="product_price">Price</label>
            <input id="product_price"
                   type="number"
                   name="product_price"
                   value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'product_price', true ) ); ?>"
            >
        </p>
        <p class="meta-options task_field">
            <label for="product_quantity">Quantity</label>
            <input id="product_quantity"
                   type="number"
                   name="product_quantity"
                   value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'product_quantity', true ) ); ?>"
            >
        </p>
        <p class="meta-options task_field">
            <label for="product_stock">Stock</label>
            <input id="product_stock"
                   type="text"
                   name="product_stock"
                   value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'product_stock', true ) ); ?>"
            >
        </p>

    </div>

    <h4>Product Promotion</h4>
    <div class="product_meta_box">
        <p class="meta-options task_field">
            <label for="product_promo_start">Promo Date Start</label>
            <input id="product_promo_start"
                   type="datetime-local"
                   name="product_promo_start"
                   value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'product_promo_start', true ) ); ?>"
            >
        </p>
        <p class="meta-options task_field">
            <label for="product_promo_end">Promo Date End</label>
            <input id="product_promo_end"
                   type="datetime-local"
                   name="product_promo_end"
                   value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'product_promo_end', true ) ); ?>"
            >
        </p>
        <p class="meta-options task_field">
            <label for="product_promo_price">Promo Price</label>
            <input id="product_promo_price"
                   type="number"
                   name="product_promo_price"
                   value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'product_promo_price', true ) ); ?>"
            >
        </p>
    </div>
</div>
