<?php
add_shortcode('token_widget', 'wtwp_token_widget');
function wtwp_token_widget($atts, $content = null)
{
	$settings = get_option('wtw_options');

	$coins_list = wtwp_get_list_of_coins();
	$out = '
	<div class="tw-bs4">
		 <div class="container">';

	$all_tokens = get_posts([
		'post_type' => 'contract_address',
		'showposts' => -1
	]);


	foreach ($all_tokens as $s_token) {

		$token_info = wtwp_token_bnb_price(esc_html(trim(get_post_meta($s_token->ID, 'contract_address', true))));
		if (!$token_info) {
			continue;
		}

		$out .= '
			<div class="row single_widget_row mt-1 mb-1" data-pricebnb="' . $token_info['price'] . '">
				<div class="col">
					<label for="" class="font-weight-bold">Time</label> <br/>
					' . date('Y-m-d H:i:s', $token_info['date']) . '
			   </div>
				<div class="col">
				   <label for="" class="font-weight-bold">Currency Units</label>
				   <input value="1" class="currency_unit form-control" />
			   </div>
				<div class="col">
				   <label for="" class="font-weight-bold">Currency Type</label>
				   <select class="currency_type form-control">
				 	<option value="">Select  
				   ';

		foreach ($coins_list as $s_list) {
			$out .= '<option value="' . strtoupper($s_list) . '">' . strtoupper($s_list);
		}
		$out .= '
				   </select>
			   </div>
			   <div class="col">
				   <label for="" class="font-weight-bold">Number of tokens</label>
				   <input readonly class="number_of_tokens form-control" value="0" />
			   </div>
			   <div class="col">
				   <label for="" class="font-weight-bold">Token Name</label><br/>
				   ' . esc_html($token_info['name']) . '
			   </div>
			   <div class="col">
				   <label for="" class="font-weight-bold">Token Logo</label><br/>
				   <div class="div text-center1">
				   <img src="' . get_the_post_thumbnail_url($s_token->ID) . '" class="image_logo_preview" />  
				   </div>
				   
			   </div>
			   <div class="col-12 message_placeholder"></div>
			</div>';
	}


	if (isset($settings['show_credentials'])) {
		if ($settings['show_credentials'] == 'on') {
			$out .= '
				<div class="row powered">
					<div class="col-12 text-right text-12 mt-3 mb-3">
					Powered by <a href="https://www.coingecko.com/" target="_blank" rel="nofollow">CoinGecko API</a> and <a href="https://CoinMarketCap.finance/" target="_blank" rel="nofollow">CoinMarketCap API</a>
					</div>
				</div>';
		}

	}


	$out .= '
		 </div>
	</div>
	';

	return $out;
}


?>