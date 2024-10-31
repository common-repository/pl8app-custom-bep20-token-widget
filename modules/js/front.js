jQuery(document).ready( function($){
	$('body').on( 'change', '.currency_unit', function( e ){
        var parent = $(this).parents('.single_widget_row');
        var units = parseFloat( $(this).val() );
 
        var price = parseFloat( $('.number_of_tokens', parent).val() );
     
        var new_total = units * price;

        $('.number_of_tokens ', parent).val( new_total );
    })

	$('body').on( 'change', '.currency_type', function( e ){
        var parent = $(this).parents('.single_widget_row');
		// verify email

        if( $(this).val() == '' ){ return false; }

		var data = {
			currency_type  : $(this).val(),
			security  : wtw_local_data.nonce,
			action : 'get_coin_price'
		}
		jQuery.ajax({url: wtw_local_data.ajaxurl,
				type: 'POST',
				data: data,            
				beforeSend: function(msg){
						jQuery('body').append('<div class="big_loader"></div>');
					},
					success: function(msg){
						
						
						console.log( msg );
						
						jQuery('.big_loader').replaceWith('');
						
						var obj = jQuery.parseJSON( msg );
						
						console.log( obj );
						console.log( obj.success );
						if( obj.result == 'success' ){
                            var value = parseFloat( obj.value );
                            var calc_step_1 = 1 / value;

                            var bnb_price = parseFloat( parent.attr('data-pricebnb') );

                            var calc_step_2 = calc_step_1 / bnb_price;
                            console.log( value );
                            console.log( calc_step_1 );
                            console.log( bnb_price );
                            console.log( calc_step_2 );

                            var user_qty_entry = parseFloat( $('.currency_unit', parent).val() );


                            var calc_step_3 = user_qty_entry * calc_step_2;

                            $('.number_of_tokens', parent).val( calc_step_3 );
					 
						}else{
                            $('.message_placeholder', partent).html('<div class="alert alert-danger mt-2 mb-2">'+obj.message+'</div>');
						}
						 
					} , 
					error:  function(msg) {
                        console.log( msg );		
					}          
			});
	 
	})
	
}) // global end
