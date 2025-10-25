<?php

// Product archive items 
remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
remove_action('woocommerce_shop_loop_header', 'woocommerce_product_taxonomy_archive_header', 10);
remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);
remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);

// Product grid items 
remove_action('woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10);
remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10);
remove_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10);
remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);
remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);
remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5);
remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);

// Compare plugin defualt button hide
add_filter('woosc_button_position_archive', '__return_false');
add_filter('woosc_button_position_single', '__return_false');
add_filter('woosq_button_position', '__return_false');

// product add to cart button
function artly_wooc_add_to_cart($args = array())
{
  global $product;

  if ($product) {
    $defaults = array(
      'quantity'   => 1,
      'class'      => implode(
        ' ',
        array_filter(
          array(
            'tp-product-add-cart-btn-large text-center',
            'product_type_' . $product->get_type(),
            $product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
            $product->supports('ajax_add_to_cart') && $product->is_purchasable() && $product->is_in_stock() ? 'ajax_add_to_cart' : '',
          )
        )
      ),
      'attributes' => array(
        'data-product_id'  => $product->get_id(),
        'data-product_sku' => $product->get_sku(),
        'aria-label'       => $product->add_to_cart_description(),
        'rel'              => 'nofollow',
      ),
    );

    $args = wp_parse_args($args, $defaults);

    if (isset($args['attributes']['aria-label'])) {
      $args['attributes']['aria-label'] = wp_strip_all_tags($args['attributes']['aria-label']);
    }
  }


  // check product type 
  if ($product->is_type('simple')) {
    $btntext = esc_html__("Add to Cart", 'harry');
  } elseif ($product->is_type('variable')) {
    $btntext = esc_html__("Select Options", 'harry');
  } elseif ($product->is_type('external')) {
    $btntext = esc_html__("Buy Now", 'harry');
  } elseif ($product->is_type('grouped')) {
    $btntext = esc_html__("View Products", 'harry');
  } else {
    $btntext = esc_html__("Add to Cart", 'harry');
  }

  echo sprintf(
    '<a title="%s" href="%s" data-quantity="%s" class="%s" %s>%s</a>',
    $btntext,
    esc_url($product->add_to_cart_url()),
    esc_attr(isset($args['quantity']) ? $args['quantity'] : 1),
    esc_attr(isset($args['class']) ? $args['class'] : 'tp-product-add-cart-btn-large'),
    isset($args['attributes']) ? wc_implode_html_attributes($args['attributes']) : '',
    $btntext
  );
}


// Product grid function 
function artly_product_grid()
{

  $post_cats = get_the_terms(get_the_ID(), 'product_cat');


?>

  <div class="tp-product-item mb-50">
    <div class="tp-product-thumb mb-15 fix p-relative z-index-1">
      <a href="<?php the_permalink(); ?>">
        <?php echo woocommerce_get_product_thumbnail(); ?>
      </a>
      <div class="tp-product-badge">
        <?php woocommerce_show_product_loop_sale_flash(); ?>
      </div>

      <!-- product action -->
      <div class="tp-product-action tp-product-action-blackStyle">
        <div class="tp-product-action-item d-flex flex-column">

          <div class="tp-product-action-btn tp-product-add-cart-btn">
            <?php echo do_shortcode('[woosc]'); ?>
            <span class="tp-product-tooltip">Add To Compare</span>
          </div>

          <div class="tp-product-action-btn tp-product-quick-view-btn">
            <?php echo do_shortcode('[ ]'); ?>
            <span class="tp-product-tooltip">Quick View</span>
          </div>

          <div class="tp-product-action-btn tp-product-add-to-wishlist-btn">
            <?php echo do_shortcode('[ ]'); ?>
            <span class="tp-product-tooltip">Quick View</span>
          </div>


          <button type="button" class="tp-product-action-btn tp-product-add-to-wishlist-btn">
            <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path fill-rule="evenodd" clip-rule="evenodd" d="M1.60355 7.98635C2.83622 11.8048 7.7062 14.8923 9.0004 15.6565C10.299 14.8844 15.2042 11.7628 16.3973 7.98985C17.1806 5.55102 16.4535 2.46177 13.5644 1.53473C12.1647 1.08741 10.532 1.35966 9.40484 2.22804C9.16921 2.40837 8.84214 2.41187 8.60476 2.23329C7.41078 1.33952 5.85105 1.07778 4.42936 1.53473C1.54465 2.4609 0.820172 5.55014 1.60355 7.98635ZM9.00138 17.0711C8.89236 17.0711 8.78421 17.0448 8.68574 16.9914C8.41055 16.8417 1.92808 13.2841 0.348132 8.3872C0.347252 8.3872 0.347252 8.38633 0.347252 8.38633C-0.644504 5.30321 0.459792 1.42874 4.02502 0.284605C5.69904 -0.254635 7.52342 -0.0174044 8.99874 0.909632C10.4283 0.00973263 12.3275 -0.238878 13.9681 0.284605C17.5368 1.43049 18.6446 5.30408 17.6538 8.38633C16.1248 13.2272 9.59485 16.8382 9.3179 16.9896C9.21943 17.0439 9.1104 17.0711 9.00138 17.0711Z" fill="currentColor" />
              <path fill-rule="evenodd" clip-rule="evenodd" d="M14.203 6.67473C13.8627 6.67473 13.5743 6.41474 13.5462 6.07159C13.4882 5.35202 13.0046 4.7445 12.3162 4.52302C11.9689 4.41097 11.779 4.04068 11.8906 3.69666C12.0041 3.35175 12.3724 3.16442 12.7206 3.27297C13.919 3.65901 14.7586 4.71561 14.8615 5.96479C14.8905 6.32632 14.6206 6.64322 14.2575 6.6721C14.239 6.67385 14.2214 6.67473 14.203 6.67473Z" fill="currentColor" />
            </svg>
            <span class="tp-product-tooltip">Add To Wishlist</span>
          </button>
        </div>
      </div>

      <div class="tp-product-add-cart-btn-large-wrapper">
        <?php artly_wooc_add_to_cart(); ?>
      </div>
    </div>
    <div class="tp-product-content">
      <div class="tp-product-tag">
        <?php
        $html = '';
        foreach ($post_cats as $key => $cat) {

          $html .= '<span><a href="' . get_category_link($cat->term_id) . '">' . $cat->name . '</a></span>,';
        }
        echo rtrim($html, ',');

        ?>

      </div>
      <h3 class="tp-product-title">
        <a href="<?php the_permalink(); ?>"> <?php the_title(); ?> </a>
      </h3>
      <div class="tp-product-price-wrapper">
        <?php woocommerce_template_loop_price(); ?>
      </div>
    </div>
  </div>

<?php

}
add_action('woocommerce_before_shop_loop_item', 'artly_product_grid');
