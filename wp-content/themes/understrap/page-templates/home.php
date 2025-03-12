<?php
/**
 * Template Name: Home
 *
 * Template for displaying a page with header, footer and a dynamic content area.
 * Displays AJAX filters for product categories and price options.
 *
 * @package understrap
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

get_header();

// Retrieve selected filters from URL parameters.
$selected_category = isset($_GET['category']) ? sanitize_text_field($_GET['category']) : 'all';
$selected_tax = isset($_GET['tax']) ? sanitize_text_field($_GET['tax']) : 'no';

// Get all product categories.
$categories = get_terms( array(
	'taxonomy'   => 'product_cat',
	'hide_empty' => true,
) );

// Create tax_query if the "all" option is not selected.
$tax_query = array();
if ( 'all' !== $selected_category ) {
	$tax_query[] = array(
		'taxonomy' => 'product_cat',
		'field'    => 'slug',
		'terms'    => $selected_category,
	);
}

// Perform WP_Query for products, filtered by language and category.
$products = new WP_Query( array(
	'post_type'      => 'product',
	'post_status'    => 'publish',
	'posts_per_page' => -1,
	'lang'           => ICL_LANGUAGE_CODE,
	'tax_query'      => $tax_query,
	) );
?>
<div class="container">
	<div class="row">
		<div class="col-4 ajax_filter">
			<h6><?php esc_html_e( 'Filter - kategorije:', 'understrap' ); ?></h6>
			<ul class="filter categories">
				<?php
				// Display the "all" option with the total number of products.
				$all_products = $products->found_posts;
				echo '<li class="option ' . ( $selected_category == 'all' ? 'active' : '' ) . '" data-category="all"><b>' . sprintf( esc_html__( 'Vsi (%d)', 'understrap' ), $all_products ) . '</b></li>';
				// Dynamically display each category.
				if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
					foreach ( $categories as $category ) {
						echo '<li class="option ' . ( $selected_category == $category->slug ? 'active' : '' ) . '" data-category="' . esc_attr( $category->slug ) . '">'
							 . esc_html( $category->name ) . ' (' . $category->count . ')'
							 . '</li>';
					}
				}
				?>
			</ul>
			<hr>
			<h6><?php esc_html_e( 'Filter - cena:', 'understrap' ); ?></h6>
			<ul class="filter price">
				<li class="option <?php echo ( $selected_tax == 'no' ? 'active' : '' ); ?>" data-tax="no">
					<b><?php esc_html_e( 'Brez davka', 'understrap' ); ?></b>
				</li>
				<li class="option <?php echo ( $selected_tax == 'yes' ? 'active' : '' ); ?>" data-tax="yes">
					<?php esc_html_e( 'Z davkom', 'understrap' ); ?>
				</li>
			</ul>
		</div>
		<div class="col-8 products">
			<h6><?php esc_html_e( 'Izdelki:', 'understrap' ); ?></h6>
			<div class="row products-container">
				<?php
				if ( $products->have_posts() ) :
					while ( $products->have_posts() ) :
						$products->the_post();
						global $product, $prices;
						// Calculate prices with or without tax.
						$prices = custom_tax_calculation( $product, $selected_tax );
						get_template_part( 'partials/product' );
					endwhile;
					wp_reset_postdata();
				else :
					echo '<div class="no-products"><p><strong>' . esc_html__( 'Ni izdelkov v tej kategoriji.', 'understrap' ) . '</strong></p></div>';
				endif;
				?>
			</div>
		</div>
	</div>
</div>
<?php
get_footer();
