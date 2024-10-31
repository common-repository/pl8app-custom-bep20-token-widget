<?php 
 
	class wtwp_vooMetaBox1{
		/* V1.0.1 */
		private $metabox_parameters = null;
		private $fields_parameters = null;
		private $data_html = null;
		
		function __construct( $metabox_parameters , $fields_parameters){
			$this->metabox_parameters = $metabox_parameters;
			$this->fields_parameters = $fields_parameters;
 
			add_action( 'add_meta_boxes', array( $this, 'add_custom_box' ) );
			add_action( 'save_post', array( $this, 'save_postdata' ) );
		}
		
		function add_custom_box(){
			add_meta_box( 
				'custom_meta_editor_'.rand( 100, 999 ),
				$this->metabox_parameters['title'],
				array( $this, 'custom_meta_editor' ),
				$this->metabox_parameters['post_type'] , 
				$this->metabox_parameters['position'], 
				$this->metabox_parameters['place']
			);
		}
		function custom_meta_editor(){
			global $post;
			
			$out = '

			<div class="tw-bs4">
				<div class="form-horizontal ">';
			
			foreach( $this->fields_parameters as $single_field){
			 
				$interface_element = new wtwp_formElementsClass( $single_field['type'], $single_field, esc_html( get_post_meta( $post->ID, $single_field['name'], true ) ) );
				$out .= $interface_element->get_code();
			  
			}		
			
					
					
			$out .= '
					</div>	
				</div>
				';	
			$this->data_html = $out;
			 
			$this->echo_data();
		}
		
		function echo_data(){
			echo  $this->data_html;
		}
		
		function save_postdata( $post_id ) {
			global $current_user; 
			 if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
				  return;

				if( isset( $_POST['post_type'] ) ){
					if ( $_POST['post_type'] == 'page' ) 
					{
					  if ( !current_user_can( 'edit_page', $post_id ) )
						  return;
					}
					else
					{
					  if ( !current_user_can( 'edit_post', $post_id ) )
						  return;
					}
				}
			  
			  /// User editotions

				if( get_post_type($post_id) == $this->metabox_parameters['post_type'] ){
					foreach( $this->fields_parameters as $single_parameter ){
						if( isset( $_POST[$single_parameter['name']] ) ){
							update_post_meta( $post_id, $single_parameter['name'], sanitize_text_field( $_POST[$single_parameter['name']] ) );
						}
						
					}
					
				}
				
			}
	}


 
 
add_Action('admin_init',  function (){
	 
	 

	 
	 $meta_box = array(
		'title' => 'Contract Address',
		'post_type' => 'contract_address',
		'position' => 'advanced',
		'place' => 'high'
	);
	$fields_parameters = array(
 
		array(
			'type' => 'text',
			'title' => 'Contract Address',
			'name' => 'contract_address',
		),
		array(
			'type' => 'text',
			'title' => 'Token Name',
			'name' => 'token_name',
		),
		 
	 
	);		
	$new_metabox = new wtwp_vooMetaBox1( $meta_box, $fields_parameters); 
	 
 } );
 

?>