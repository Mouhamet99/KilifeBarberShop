<?php
$widget_id  = $args['widget_id'];

if ( !wcd_is_pro_activated() && !wcd_is_preview_mode() && !wcd_is_edit_mode() ) {
    $wishlist_show_hide = 'no';
}

$empty_message = __( 'No Product Found!', 'woolementor' );

$settings   = $args['settings'];

extract( $settings );

$products = wcd_query_products( $settings );
$user_id  = get_current_user_id();
$wishlist = wcd_get_wishlist( $user_id );
?>

<div class="wl-ss-product-style">
	<div class="cx-container">
		<div class="cx-grid">
		
	<?php
	if( $products->have_posts()) : 
		while( $products->have_posts()) : $products->the_post();
			$product_id = get_the_ID();
			$product    = wc_get_product( $product_id );
			$thumbnail  = get_the_post_thumbnail_url( $product_id, $image_thumbnail_size );
			$fav_product= in_array( $product_id, $wishlist );

            if ( !empty( $fav_product ) ) {
                $fav_item = 'fav-item';
            }
            else{
                $fav_item = '';
            }
			?>

		   <div class="wl-ss-single-product <?php echo esc_attr( $alignment ); ?>">
			  <div class="wl-ss-single-widget">

				 <?php if ( 'yes' == $sale_ribbon_show_hide && $product->is_on_sale() ): ?>
					<div class="wl-ss-corner-ribbon">
						<?php
						printf( '<span>%1$s</span>',
                            esc_html( $settings['sale_ribbon_text' ] )
                        );
						?>
					</div>
				 <?php endif;

				 if( 'outofstock' == $product->get_stock_status() && 'yes' == $stock_show_hide ): ?>
				     <div class="wl-ss-stock">
				         <?php echo $stock_ribbon_text; ?>
				     </div>
				 <?php endif; ?>

				<div class="wl-ss-product-img">

					 <?php if ( 'none' == $image_on_click ): ?>
						 <img src="<?php echo esc_url( $thumbnail ); ?>" alt="<?php echo esc_attr( $product->get_name() ); ?>"/>  
					 <?php elseif ( 'zoom' == $image_on_click ) : ?>
						 <a id="wl-ss-product-image-zoom" href="<?php echo esc_url( $thumbnail ); ?>"><img src="<?php echo esc_url( $thumbnail ); ?>" alt=""/></a>
					 <?php elseif ( 'product_page' == $image_on_click ) : ?>
						 <a href="<?php the_permalink(); ?>">
							 <img src="<?php echo esc_url( $thumbnail ); ?>" alt="<?php echo esc_attr( $product->get_name() ); ?>"/>                              
						 </a>
					 <?php endif; ?>

				</div>
				
				 <div class="wl-ss-product-details">

					<?php if ( 'yes' == $wishlist_show_hide ): ?>
						<div class="wl-ss-product-fav"><i class="<?php echo esc_attr( $wishlist_icon['value'] );?> <?php echo esc_attr( $fav_item ); ?> ajax_add_to_wish" data-product_id="<?php echo $product_id; ?>"></i></div>
					<?php endif; ?>

					<div class="wl-ss-product-info">
					   <div class="wl-ss-product-name"><a class="wl-gradient-heading" href="<?php the_permalink(); ?>"><?php echo esc_html( $product->get_name() ); ?></a></div>

					   <?php if( 'yes' == $short_description_show_hide ): ?>
						   <div class="wl-ss-product-desc">
							<p><?php echo wp_trim_words( $product->get_short_description(), $product_desc_words_count ); ?></p>
						   </div>
					   <?php endif; ?>

					</div>
					<div class="wl-ss-info-icons">
						<div class="wl-ss-price">
							<h2><?php echo $product->get_price_html(); ?></h2>
						</div>

						<?php 
						do_action( 'codesigner_before_cart_button', $product, $widget_id, $settings );
						
						if ( 'yes' == $cart_show_hide ):
							if( 'simple' == $product->get_type() ) : ?>
								<div class="wl-ss-product-cart">
									<div class="wl-cart-area">
										<a href="?add-to-cart=<?php echo $product_id; ?>" data-quantity="1" class="product_type_<?php echo esc_attr( $product->get_type() ); ?> add_to_cart_button ajax_add_to_cart" data-product_id="<?php echo $product_id; ?>" ><i class="<?php echo esc_attr( $cart_icon['value'] ); ?>"></i></a>
									</div>
								</div>
							<?php else: ?>
								<div class="wl-ss-product-cart">
									<a href="<?php echo get_permalink( $product_id ); ?>"><i class="<?php echo esc_attr( $cart_icon['value'] ); ?>"></i></a>
								</div>
							<?php endif;
						endif;

						do_action( 'codesigner_after_cart_button', $product, $widget_id, $settings );
						?>
					</div>
				 </div>
			  </div>
		   </div>

	<?php endwhile; wp_reset_query(); else: 

	echo '<p>' . __( 'No Product Found!', 'woolementor' ) . '</p>';

	endif; ?>
	</div>
</div>
</div>

<?php 
if ( 'yes' == $pagination_show_hide ):

    $class = '';
    if ( defined('DOING_AJAX') && DOING_AJAX ) {
        $class = 'wl-ajax-filter-pagination';
    }

    echo "<div class='wl-ss-pagination {$class}'>";
    
    /**
    * woolementor pagination
    */
    wcd_pagination( $products, $pagination_left_icon, $pagination_right_icon ); 

    echo '</div>';
endif;