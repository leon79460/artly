<?php

/**
 * Single Product Meta
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/meta.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     9.7.0
 */

use Automattic\WooCommerce\Enums\ProductType;

if (! defined('ABSPATH')) {
	exit;
}
$post_cats = get_the_terms(get_the_ID(), 'product_cat');
$post_tags = get_the_terms(get_the_ID(), 'product_tag');
global $product;
?>
<div class="product_meta">

	<div class="tp-product-details-query-item d-flex align-items-center">
		<?php if (wc_product_sku_enabled() && ($product->get_sku() || $product->is_type(ProductType::VARIABLE))) : ?>
			<span><?php esc_html_e('SKU:', 'artly'); ?></span>
			<p> <?php echo ($sku = $product->get_sku()) ? $sku : esc_html__('N/A', 'woocommerce'); ?> </p>
		<?php endif; ?>
	</div>

	<div class="tp-product-details-query-item d-flex align-items-center">
		<span><?php esc_html_e('Category:', 'artly'); ?> </span>
		<p>
			<?php
			$html = '';

			foreach ($post_cats as $key => $cat) {

				$html .= '<span>' . $cat->name . '</span>,';
			}
			echo rtrim($html, ',');

			?>
		</p>
	</div>

	<div class="tp-product-details-query-item d-flex align-items-center">
		<span><?php esc_html_e('Tag:', 'artly'); ?> </span>
		<p>
			<?php
			$html = '';
			if (!empty($post_tags) && !is_wp_error($post_tags)) {
				foreach ($post_tags as $key => $tag) {
					$html .= '<span>' . esc_html($tag->name) . '</span>,';
				}
				echo rtrim($html, ',');
			}
			?>
		</p>
	</div>

</div>