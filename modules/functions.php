<?php

/**
 * Fetches the token/WBNB pair from CoinMarketCap API
 * It will cache the result for 2 hours
 * The coin pair address never changes, so it's safe to cache it for 2 hours
 * @param string $token_address
 */
function pl8app_fetch_token_wbnb_pair($token_address) {
	
	$token_address = strtolower($token_address);

	$transient_key = "token_wbnb_pair_${token_address}";

	$cache_from_transient = get_transient($transient_key);
	if ($cache_from_transient) {
		$token_wbnb_pair = json_decode($cache_from_transient);

		return $token_wbnb_pair;
	}

	// If file doesn't exist or is older than 2 hours, fetching the data from API
	$url = "https://api.coinmarketcap.com/dexer/v3/dexer/search/main-site?keyword=${token_address}&all=true&limit=50&record=true";

	$results = wp_remote_get($url, [
		"user-agent" => "okhttp/4.10.0",
		"headers" => [
			'accept-encoding' => 'gzip',
			'appbuild' => '1785',
			'appversion' => '4.23.4',
			'connection' => 'Keep-Alive',
			'host' => 'api.coinmarketcap.com',
			'languagecode' => 'en',
			'platform' => 'android',
			'user-agent' => 'okhttp/4.10.0'
		],
	]);

	if (!is_wp_error($results)) {
		$parsed_content = json_Decode($results['body']);

		// If no data is found, return false
		if (empty($parsed_content->data)) {
			return false;
		}

		$pairs = $parsed_content->data->pairs;

		// filtering the data to get WBNB pair

		$filtered_data = array_filter($pairs, function ($pair) {
			return $pair->quoteTokenSymbol == 'WBNB';
		});

		// If no WBNB pair is found, return false
		if (empty($filtered_data)) {
			return false;
		}

		// If WBNB pair is found, return the first item
		$filtered_data = array_values($filtered_data);

		$pair = $filtered_data[0];

		$token_wbnb_pair_json = json_encode($pair);

		set_transient($transient_key, $token_wbnb_pair_json, 60 * 60 * 2);
		
		return $pair;
	} else {
		return false;
	}
}
/**
 * Fetches the token price from CoinMarketCap API
 * @param string $token_address
 */
function wtwp_token_bnb_price($token_address) {

	if (empty($token_address)) {
		return [
			'date' => 0,
			'price' => 0,
			'name' => 'No token address provided',
		];
	}

	// If token address is provided, checking if it's a valid address
	$token_address = strtolower($token_address);

	if (!preg_match('/^(0x)?[0-9a-f]{40}$/i', $token_address)) {
		return false;
	}

	// If it's WBNB, send the price as 1
	if ($token_address == '0xbb4cdb9cbd36b01bd1cbaebf2de08d9173bc095c') {
		return [
			'date' => 0,
			'price' => 1,
			'name' => 'WBNB',
		];
	}

	// Step 1: Search for the Token/WBNB pair

	$token_wbnb_pair = pl8app_fetch_token_wbnb_pair($token_address);

	if (!$token_wbnb_pair) {
		return false;
	}

	$pairContractAddress = $token_wbnb_pair->pairContractAddress;
	$token_name = $token_wbnb_pair->baseTokenName;
		// Step 2: Get the price of the pair

	$url = "https://api.coinmarketcap.com/dexer/v3/dexer/pair-info?platform-id=14&dexer-platform-name=BSC&address=$pairContractAddress";

	$results = wp_remote_get($url, [
		"user-agent" => "okhttp/4.10.0",
		"headers" => [
			'accept-encoding' => 'gzip',
			'appbuild' => '1785',
			'appversion' => '4.23.4',
			'connection' => 'Keep-Alive',
			'host' => 'api.coinmarketcap.com',
			'languagecode' => 'en',
			'platform' => 'android',
			'user-agent' => 'okhttp/4.10.0'
		],
	]);

	if (!is_wp_error($results)) {
		$parsed_content = json_Decode($results['body']);

		// If no data is found, return false
		if (empty($parsed_content->data)) {
			return false;
		}

		$price = $parsed_content->data->priceQuote;
		$date = round($parsed_content->data->tokenSecurityDTO->updateDate / 1000);

		return [
			'date' => $date,
			'price' => $price,
			'name' => $token_name,
		];
		
	} else {
		return false;
	}
}

function wtwp_get_list_of_coins() {
	$loaded_coins = get_transient('coins_transient');
	if ($loaded_coins) {
		return json_decode($loaded_coins);
	} else {
		$url = 'https://api.coingecko.com/api/v3/simple/supported_vs_currencies';
		$results = wp_remote_get($url);
		if (!is_wp_error($results)) {
			set_transient('coins_transient', $results['body'], 300);
			$parsed_content = json_decode($results['body']);
			return $parsed_content;
		}
	}
}
?>