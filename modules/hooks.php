<?php 
add_filter( 'manage_edit-contract_address_columns', 'wtwp_contract_address_columns' ) ;
function wtwp_contract_address_columns( $columns ) {

	$columns = array(
		'cb' => '<input type="checkbox" />',
		
		'title' => __( 'Title' ),
		'contract_address' => __( 'Contract Address' ),
		
		'token_name' => __( 'Token Name' ),
		'logo' => __( 'Logo' ),

	);

	return $columns;
}

add_action( 'manage_contract_address_posts_custom_column', 'wtwp_contract_address_posts_custom_column', 10, 2 );

function wtwp_contract_address_posts_custom_column( $column, $post_id ) {
	global $post;
	switch( $column ) {
		/* If displaying the 'duration' column. */
		case 'contract_address' :
			echo esc_html( get_post_meta( $post->ID, 'contract_address', true ) );
			break;

		case 'token_name' :
		
			echo esc_html( get_post_meta( $post->ID, 'token_name', true ) );
			break;	
		
		case 'logo' :
			echo '<img src="'.get_the_post_thumbnail_url( $post->ID  ).'" class="image_logo_preview" />';
			break;		

		/* Just break out of the switch statement for everything else. */
		default :
			break;
	}
}

?>