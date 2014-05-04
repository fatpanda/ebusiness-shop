<?php

add_action( 'admin_init', 'stag_shop_settings' );

function stag_shop_settings(){
	$settings['description'] = __( 'Customize your shop settings.', 'stag' );

	$settings[] = array(
		'title' => __( 'Favicon Badge', 'stag' ),
		'desc'  => __( 'Show user&rsquo;s cart item counts as badge in favicon.', 'stag' ),
		'type'  => 'checkbox',
		'id'    => 'shop_favicon_badge',
		'val'   => 'on'
	);

	$settings[] = array(
		'title' => __( 'Products Per Page', 'stag' ),
		'desc'  => __( 'Enter how many products should be displayed per page.', 'stag' ),
		'type'  => 'text',
		'id'    => 'shop_products_per_page',
		'val'   => '12'
	);

	$settings[] = array(
		'title' => __( 'Parent Category on Catalog Pages', 'stag' ),
		'desc'  => __( 'Enable / Disable the parent category link from catalog pages.', 'stag' ),
		'type'  => 'checkbox',
		'id'    => 'shop_parent_category',
		'val'   => 'on'
	);

	$settings[] = array(
		'title' => __( 'Ratings on Catalog Pages', 'stag' ),
		'desc'  => __( 'Show / Hide the ratings meter on the products listed on shop pages.', 'stag' ),
		'type'  => 'checkbox',
		'id'    => 'shop_ratings',
		'val'   => 'on'
	);

	stag_add_framework_page( __( 'Shop Settings', 'stag' ), $settings, 20 );
}
