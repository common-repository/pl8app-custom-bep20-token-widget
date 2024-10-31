<?php
/*
Plugin Name: pl8app custom bep20 token widget
Plugin URI: http://token.pl8app.co.uk
Description: Add Custom BEP20 token prices to your site with this Widget from pl8app
Version: 1.2
Author: pl8app
Author URI: http://token.pl8app.co.uk
Stable tag: 1.2
*/

//error_reporting(E_ALL);
//ini_set('display_errors', 'On');


if ( ! defined( 'ABSPATH' ) ) {
	wp_die( 'Direct Access is not Allowed' );
}

// core initiation
 
	class wtwp_vooMainStart{
		public $locale;
		function __construct( $locale, $includes, $path ){
			$this->locale = $locale;
			
			// include files
			foreach( $includes as $single_path ){
				include( $path.$single_path );				
			}
			// calling localization
			add_action('plugins_loaded', array( $this, 'myplugin_init' ) );

			register_activation_hook( __FILE__, array( $this, 'plugin_activation' ) );
			
			register_uninstall_hook(__FILE__, 'plugin_uninstall');
		}

		function plugin_activation(){
			flush_rewrite_rules();
		}
		
		function plugin_uninstall(){
			 
		}

		function myplugin_init() {
		 	$plugin_dir = basename(dirname(__FILE__));
		 	load_plugin_textdomain( $this->locale , false, $plugin_dir );
		}
	}
	
	
 



// initiate main class

$obj = new wtwp_vooMainStart('wtw', array(
	'modules/class-form-elements.php',
	//'modules/class-core-helper.php',
	
	'modules/ajax.php',
	'modules/hooks.php',
	'modules/scripts.php',
	
	'modules/meta_box.php',
	'modules/cpt.php',
	'modules/shortcodes.php',
	'modules/functions.php',
	'modules/settings.php',
), dirname(__FILE__).'/' );
 
 





 
?>