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


// Product single page items 
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50);
remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10);

// Compare,Quick view, Wishlist plugin defualt button disable filter
add_filter('woosc_button_position_archive', '__return_false');
add_filter('woosc_button_position_single', '__return_false');
add_filter('woosq_button_position', '__return_false');
add_filter('woosw_button_position_archive', '__return_false');
add_filter('woosw_button_position_single', '__return_false');

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


// Product grid layout function 
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
          
          <?php if (function_exists('woosc_init')): ?>
            <div class="tp-product-action-btn tp-product-add-cart-btn">
              <?php echo do_shortcode('[woosc]'); ?>
              <span class="tp-product-tooltip"><?php echo esc_html__('Add To Compare', 'artly'); ?></span>
            </div>
          <?php endif; ?>

          <?php if (function_exists('woosq_init')): ?>
            <div class="tp-product-action-btn tp-product-quick-view-btn">
              <?php echo do_shortcode('[woosq]'); ?>
              <span class="tp-product-tooltip"><?php echo esc_html__('Quick View', 'artly'); ?></span>
            </div>
          <?php endif; ?>

          <?php if (function_exists('woosw_init')): ?>
            <div class="tp-product-action-btn tp-product-add-to-wishlist-btn">
              <?php echo do_shortcode('[woosw]'); ?>
              <span class="tp-product-tooltip"><?php echo esc_html__('Add To Wishlist', 'artly'); ?></span>
            </div>
          <?php endif; ?>

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

// Product details page layout function 
function artly_product_details()
{
  $post_cats = get_the_terms(get_the_ID(), 'product_cat');
  global $product;
  // var_dump($product);

?>

  <div class="tp-product-details-wrapper pb-50">
    <div class="tp-product-details-category">
      <?php
      $html = '';
      foreach ($post_cats as $key => $cat) {

        $html .= '<span><a href="' . get_category_link($cat->term_id) . '">' . $cat->name . '</a></span>,';
      }
      echo rtrim($html, ',');

      ?>
    </div>

    <h3 class="tp-product-details-title mb-20"><?php the_title(); ?> </h3>

    <!-- inventory details -->
    <div class="tp-product-details-inventory mb-25 d-flex align-items-center justify-content-between">
      <!-- price -->
      <div class="tp-product-details-price-wrapper">
        <span class="tp-product-details-price"><?php woocommerce_template_single_price(); ?></span>
        <div class="tp-product-details-rating-wrapper d-flex align-items-center">
          <?php
          global $product;

          if ($product && $product->get_rating_count() > 0) {
            // যদি রেটিং থাকে
            woocommerce_template_single_rating();
          } else {
            // যদি রেটিং না থাকে
            echo '<div class="woocommerce-no-rating">' . esc_html__('No ratings yet', 'artly') . '</div>';
          }
          ?>

        </div>
      </div>
    </div>
    <p><?php echo $product->get_short_description(); ?></p>


    <!-- actions -->
    <div class="tp-product-details-action-wrapper mb-10">
      <h3 class="tp-product-details-action-title"><?php echo esc_html__('Quantity', 'artly'); ?> </h3>

      <div class="tp-product-details-action-item-wrapper">
        <?php woocommerce_template_single_add_to_cart(); ?>
      </div>
    </div>

    <div class="tp-product-details-query">
      <?php woocommerce_template_single_meta(); ?>
    </div>

    <div class="tp-product-details-social">
      <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" target="_blank"><i class="fab fa-facebook-f"></i>
      </a>
      <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>" target="_blank">
        <i class="fab fa-twitter"></i>
      </a>
      <a href="https://www.instagram.com/?url=<?php echo urlencode(get_permalink()); ?>" target="_blank">
        <i class="fab fa-instagram"></i>
      </a>
      <a href="https://www.linkedin.com/shareArticle?url=<?php echo urlencode(get_permalink()); ?>&title=<?php echo urlencode(get_the_title()); ?>" target="_blank">
        <i class="fab fa-linkedin-in"></i>
      </a>
    </div>
  </div>

<?php

}
add_action('woocommerce_single_product_summary', 'artly_product_details');



// custom_quantity_fields_script 
function custom_quantity_fields_script()
{
?>
  <script type='text/javascript'>
    jQuery(function($) {
      if (!String.prototype.getDecimals) {
        String.prototype.getDecimals = function() {
          var num = this,
            match = ('' + num).match(/(?:\.(\d+))?(?:[eE]([+-]?\d+))?$/);
          if (!match) {
            return 0;
          }
          return Math.max(0, (match[1] ? match[1].length : 0) - (match[2] ? +match[2] : 0));
        }
      }
      // Quantity "plus" and "minus" buttons
      $(document.body).on('click', '.plus, .minus', function() {
        var $qty = $(this).closest('.quantity').find('.qty'),
          currentVal = parseFloat($qty.val()),
          max = parseFloat($qty.attr('max')),
          min = parseFloat($qty.attr('min')),
          step = $qty.attr('step');

        // Format values
        if (!currentVal || currentVal === '' || currentVal === 'NaN') currentVal = 0;
        if (max === '' || max === 'NaN') max = '';
        if (min === '' || min === 'NaN') min = 0;
        if (step === 'any' || step === '' || step === undefined || parseFloat(step) === 'NaN') step = 1;

        // Change the value
        if ($(this).is('.plus')) {
          if (max && (currentVal >= max)) {
            $qty.val(max);
          } else {
            $qty.val((currentVal + parseFloat(step)).toFixed(step.getDecimals()));
          }
        } else {
          if (min && (currentVal <= min)) {
            $qty.val(min);
          } else if (currentVal > 0) {
            $qty.val((currentVal - parseFloat(step)).toFixed(step.getDecimals()));
          }
        }

        // Trigger change event
        $qty.trigger('change');
      });
    });
  </script>
<?php
}
add_action('wp_footer', 'custom_quantity_fields_script');
