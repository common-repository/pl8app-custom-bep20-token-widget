<?php 

add_action('wp_ajax_get_coin_price', 'wtwp_get_coin_price');
add_action('wp_ajax_nopriv_get_coin_price', 'wtwp_get_coin_price');

function wtwp_get_coin_price(){
	global $current_user, $wpdb;
	if( check_ajax_referer( 'ajax_call_nonce', 'security') ){
		
		$currency_type = sanitize_text_field( $_POST['currency_type'] );
	 
		$url = 'https://api.coingecko.com/api/v3/simple/price?ids=binancecoin&vs_currencies='.$currency_type;
		$results = wp_remote_get( $url );
		if( !is_wp_error( $results ) ){
			$parsed_content = json_decode( $results['body'] );
	 
			$count_name = strtolower( $currency_type );

			if( isset( $parsed_content->binancecoin->$count_name ) ){
				echo json_encode( ['result' => 'success', 'value' => $parsed_content->binancecoin->$count_name ] );
			}else{
				echo json_encode( ['result' => 'error', 'message' => 'Sorry, cant make calculations' ] );
			}
			

		}else{
			echo json_encode( ['result' => 'error', 'message' => 'Sorry, cant make calculations' ] );
		}
		 
		
	}
	die();
}



?>