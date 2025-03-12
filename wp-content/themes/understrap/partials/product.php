<?php
/**
 * Template part for displaying a single product.
 *
 * @package understrap
 */

defined( 'ABSPATH' ) || exit;

global $product, $prices;

if ( ! $product ) {
	return;
}

?>
<div class="product">
	<div class="product-image">
		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'medium' ); ?>
		<?php else : ?>
			<img src="<?php echo esc_url( get_template_directory_uri() . '/img/no-image.png' ); ?>" alt="<?php the_title_attribute(); ?>">
		<?php endif; ?>
	</div>
	<div class="product-details">
		<h2 class="product-title"><?php the_title(); ?></h2>
		<div class="product-categories">
			<?php
			$terms = wp_get_post_terms( get_the_ID(), 'product_cat' );
			if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
				$category_names = array();
				foreach ( $terms as $term ) {
					$category_names[] = esc_html( $term->name );
				}
				echo implode( ', ', $category_names );
			}
			?>
		</div>
		<div class="product-description">
			<?php the_excerpt(); ?>
		</div>
		<div class="product-price">
			<?php if ( $prices ) : ?>
				<div class="price-regular">
					<h4><?php esc_html_e( 'Redna cena:', 'understrap' ); ?></h4>
					<?php if ( ! empty( $prices['regular_price'] ) ) : ?>
						<p><?php echo sprintf( esc_html__( 'Redna cena: %s', 'understrap' ), $prices['regular_price'] ); ?></p>
					<?php endif; ?>
				</div>
				<?php if ( ! empty( $prices['sale_price'] ) ) : ?>
					<div class="price-sale">
						<h4><?php esc_html_e( 'Akcijska cena:', 'understrap' ); ?></h4>
						<p><?php echo sprintf( esc_html__( 'Znižana cena: %s', 'understrap' ), $prices['sale_price'] ); ?></p>
						<?php if ( ! empty( $prices['discount_amount'] ) && ! empty( $prices['discount_percentage'] ) ) : ?>
							<p><?php echo sprintf( esc_html__( 'Znesek popusta: %s (%s)', 'understrap' ), $prices['discount_amount'], $prices['discount_percentage'] ); ?></p>
						<?php endif; ?>
					</div>
				<?php endif; ?>
				<div class="price-tax-info">
					<?php if ( $prices['is_with_tax'] ) : ?>
						<p><strong><?php esc_html_e( 'Z davkom', 'understrap' ); ?></strong></p>
					<?php else : ?>
						<p><strong><?php esc_html_e( 'Brez davka', 'understrap' ); ?></strong></p>
					<?php endif; ?>
				</div>
			<?php else : ?>
				<p><?php esc_html_e( 'Za trenutno državo ni bila najdena davčna stopnja.', 'understrap' ); ?></p>
			<?php endif; ?>
		</div>
	</div>
</div>
