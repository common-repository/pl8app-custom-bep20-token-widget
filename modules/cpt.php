<?php
 
	class wtwp_vooCPTV2{
		
		var $parameters;
		var $post_type;
		
		function __construct( $in_parameters, $post_type ){
			$this->parameters = $in_parameters;
			$this->post_type = $post_type;
		 
			add_action( 'init', array( $this, 'add_post_type' ), 1 );
			register_activation_hook( __FILE__, array( $this, 'add_post_type' ) );	 
			register_activation_hook( __FILE__, 'flush_rewrite_rules' );
		}
		function add_post_type(){
			register_post_type( $this->post_type, $this->parameters );
			}
 
	}
 


 

$labels = array(
    'name' => __('Contract Address', $this->locale),
    'singular_name' => __('Contract Address', $this->locale),
    'add_new' => __('Add New', $this->locale),
    'add_new_item' => __('Add New Contract Address', $this->locale),
    'edit_item' => __('Edit Contract Address', $this->locale),
    'new_item' => __('New Contract Address', $this->locale),
    'all_items' => __('All Contract Addresses', $this->locale),
    'view_item' => __('View Contract Address', $this->locale),
    'search_items' => __('Search Contract Address', $this->locale),
    'not_found' =>  __('No Contract Addresses found', $this->locale),
    'not_found_in_trash' => __('No Contract Addresses found in Trash', $this->locale), 
    'parent_item_colon' => '',
    'menu_name' => __('Contract Addresses', $this->locale)

  );
  $args = array(
    'labels' => $labels,
    'public' => false,
    'publicly_queryable' => false,
    'show_ui' => true, 
    'show_in_menu' => true, 
    'query_var' => true,
    'rewrite' => true,
    'capability_type' => 'post',
    'has_archive' => true, 
    'hierarchical' => false,
    'menu_position' => null,
    'menu_icon' => plugins_url('/images/logo.png', __FILE__ ),
    'supports' => array( 'title', 'thumbnail', /*'custom-fields' 'editor' , 'thumbnail', 'excerpt', 'custom-fields'   'custom-fields' 'custom-fields'  'editor', 'thumbnail', 'custom-fields'  'author', , 'custom-fields', 'editor'  */)
  ); 

 
$new_pt = new wtwp_vooCPTV2( $args, 'contract_address' );


 
 


?>