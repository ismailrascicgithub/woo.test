<?php
/**
 * UnderStrap functions and definitions
 *
 * @package UnderStrap
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

// UnderStrap's includes directory.
$understrap_inc_dir = get_template_directory() . '/inc';

// Array of files to include.
$understrap_includes = array(
	'/theme-settings.php',        // Initialize theme default settings.
	'/setup.php',                 // Theme setup and custom theme supports.
	'/widgets.php',               // Register widget area.
	'/enqueue.php',               // Enqueue scripts and styles.
	'/template-tags.php',         // Custom template tags for this theme.
	'/pagination.php',            // Custom pagination for this theme.
	'/hooks.php',                 // Custom hooks.
	'/extras.php',                // Custom functions that act independently of the theme templates.
	'/customizer.php',            // Customizer additions.
	'/custom-comments.php',       // Custom Comments file.
	'/class-wp-bootstrap-navwalker.php', // Load custom WordPress nav walker.
	'/editor.php',                // Load Editor functions.
	'/deprecated.php',            // Load deprecated functions.
);

// Load WooCommerce functions if WooCommerce is activated.
if (class_exists('WooCommerce')) {
	$understrap_includes[] = '/woocommerce.php';
}

// Load Jetpack compatibility file if Jetpack is activated.
if (class_exists('Jetpack')) {
	$understrap_includes[] = '/jetpack.php';
}

// Include files.
foreach ($understrap_includes as $file) {
	require_once $understrap_inc_dir . $file;
}

/**
 * Enqueue scripts and localize variables for AJAX.
 */
function theme_enqueue_ajax_scripts()
{
	wp_enqueue_script('main-js', get_template_directory_uri() . '/js/dist/app.js', array('jquery'), null, true);
	wp_localize_script('main-js', 'ajaxurl', admin_url('admin-ajax.php'));
}
add_action('wp_enqueue_scripts', 'theme_enqueue_ajax_scripts');

/**
 * Enable multi-currency in AJAX.
 */
function load_multi_currency_in_ajax($load)
{
	return true;
}
add_filter('wcml_load_multi_currency_in_ajax', 'load_multi_currency_in_ajax', 10, 1);

/**
 * AJAX callback for filtering products.
 */
function filter_products()
{
	$category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : 'all';
	$tax = isset($_POST['tax']) ? sanitize_text_field($_POST['tax']) : 'no';

	$tax_query = array();
	if ('all' !== $category) {
		$tax_query[] = array(
			'taxonomy' => 'product_cat',
			'field' => 'slug',
			'terms' => $category,
		);
	}
	$args = array(
		'post_type' => 'product',
		'post_status' => 'publish',
		'posts_per_page' => -1,
		'tax_query' => $tax_query,
		'lang' => ICL_LANGUAGE_CODE,
	);

	$query = new WP_Query($args);

	ob_start();

	if ($query->have_posts()) {
		while ($query->have_posts()) {
			$query->the_post();
			global $product;
			global $prices;
			$prices = custom_tax_calculation($product, $tax);
			get_template_part('partials/product');
		}
	} else {
		echo '<div class="no-products">';
		echo '<p><strong>' . esc_html__('Ni izdelkov v tej kategoriji.', 'understrap') . '</strong></p>';
		echo '</div>';
	}

	$html = ob_get_clean();
	wp_reset_postdata();
	wp_send_json_success($html);
}

add_action('wp_ajax_filter_products', 'filter_products');
add_action('wp_ajax_nopriv_filter_products', 'filter_products');

/**
 * Custom tax calculation for products.
 *
 * @param WC_Product $product The product object.
 * @param string     $tax     'yes' to include tax, 'no' otherwise.
 * @return array|null
 */
function custom_tax_calculation($product, $tax = 'no')
{
	$current_locale = get_locale();
	$locale_parts = explode('_', $current_locale);
	$current_country = isset($locale_parts[1]) ? $locale_parts[1] : strtoupper($locale_parts[0]);

	$tax_class = $product->get_tax_class();
	$tax_rates = WC_Tax::get_rates_for_tax_class($tax_class);

	$relevant_rate = null;
	foreach ($tax_rates as $rate) {
		if (isset($rate->tax_rate_country) && $rate->tax_rate_country === $current_country) {
			$relevant_rate = $rate;
			break;
		}
	}

	if (!$relevant_rate) {
		return null;
	}

	$regular_price = floatval($product->get_regular_price());
	$sale_price = floatval($product->get_sale_price());
	$base_price = $sale_price > 0 ? $sale_price : $regular_price;

	$tax_rate_percentage = floatval($relevant_rate->tax_rate);
	$tax_amount_base = $base_price * ($tax_rate_percentage / 100);
	$tax_amount_regular = $regular_price * ($tax_rate_percentage / 100);
	$tax_amount_sale = $sale_price > 0 ? $sale_price * ($tax_rate_percentage / 100) : 0;

	$price_including_tax = $base_price + $tax_amount_base;
	$regular_price_including_tax = $regular_price + $tax_amount_regular;
	$sale_price_including_tax = $sale_price > 0 ? $sale_price + $tax_amount_sale : null;

	$discount_amount = 0;
	$discount_percentage = 0;

	if ($sale_price > 0 && $sale_price < $regular_price) {
		$discount_amount = $regular_price - $sale_price;
		$discount_percentage = ($discount_amount / $regular_price) * 100;
	}

	if ($tax === 'yes') {
		return array(
			'base_price' => wc_price($price_including_tax),
			'regular_price' => wc_price($regular_price_including_tax),
			'sale_price' => $sale_price > 0 ? wc_price($sale_price_including_tax) : null,
			'discount_amount' => wc_price($discount_amount + $tax_amount_regular - $tax_amount_sale),
			'discount_percentage' => round($discount_percentage, 2) . '%',
			'is_with_tax' => true,
		);
	} else {
		return array(
			'base_price' => wc_price($base_price),
			'regular_price' => wc_price($regular_price),
			'sale_price' => $sale_price > 0 ? wc_price($sale_price) : null,
			'discount_amount' => wc_price($discount_amount),
			'discount_percentage' => round($discount_percentage, 2) . '%',
			'is_with_tax' => false,
		);
	}
}
