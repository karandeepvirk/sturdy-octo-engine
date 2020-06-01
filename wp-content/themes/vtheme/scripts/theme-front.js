jQuery(document).ready(function($){
	var objDosa = {
		ajaxurl:'http://localhost/dosa/wp-admin/admin-ajax.php',
		element_slick:$('.slick-carousel'),
		element_category_image:$('.slick-carousel-image'),
		element_box:$('.inner-box'),
		element_modal:$('.current-modal'),
		element_close:$('.close-modals'),
		element_order:$('#add-to-order'),
		element_order_modal:$('.order-modal'),
		element_order_button:$('.order-button'),
		element_success_message:$('.success-message'),
		order_modal_p_value:$('.order-modal-p-value'),
		element_ajax_cart:$('.ajax-cart'),
		element_order_button_value:$('#total-order-button'),
		element_total_payment_button:$('.total-payment-button'),
		
		/* We are initiating the JS here*/
		initDosa:function(){
			this.inputEvents();
			this.addSlickCarousel();
		},

		/*This function init slick here */
		addSlickCarousel:function(){
			objDosa.element_slick.slick({
		        vertical: true,
		        slidesToShow: 6,
		        slidesToScroll: 2,
		        verticalSwiping: true,
		        infinite:true,
		        prevArrow: $('.slick-top-arrow'),
		  		nextArrow: $('.slick-bottom-arrow')
    		});
		},

		// All input actions can be added here such as click, hover, key
		inputEvents:function(){
			objDosa.element_category_image.click(function(){
				var intTermId 	  = parseInt($(this).attr('data-id'));
				var strChildCheck = $(this).attr('data-child');
				$('.sub-level').hide();
				if(intTermId>0){
					$('.sub-level').show();
					objDosa.showSubMenu(intTermId,strChildCheck);
				}
			});

			objDosa.element_box.click(function(){
				var intProductId;
				var intPrice;
				var strTitle;
				var strDescription; 
				strTitle 		= $(this).find('.item-title').text();
				strDescription  = $(this).find('.inner-box-description').text();
				intPrice 		= parseFloat($(this).find('.price-value').text());
				strStyle 		= $(this).find('.item-image').attr('style');
				intProductId 	= $(this).attr('id');
				objDosa.throwOnModal(strTitle,intPrice,strStyle,intProductId,strDescription);
			});

			objDosa.element_close.click(function(){
				objDosa.resetModal();
			});

	    	$('#plus').bind('click', {increment: 1}, objDosa.incrementValue);
	    	$('#minus').bind('click', {increment: -1}, objDosa.incrementValue);

	    	objDosa.element_order.click(function(){
	    		var intValue = parseInt($('#value').text());
	    		var intPid   = parseInt($(this).attr('data-pid'));
				objDosa.addToOrder(intPid,intValue);
			});
	    	$('.discount').click(function(e){
				e.preventDefault();
				objDosa.showDiscountView();
			});

			objDosa.element_order_button.click(function(e){
				e.preventDefault();
				objDosa.showOrderView();
			});

			$(document).on('change','.order-modal-p-value',function(){
				var intValue = parseInt($(this).val());
				var intProductId 	= parseInt($(this).attr('data-product-id'));
				objDosa.qtyChangedOnOrderModal(intValue,intProductId);
			});

			$(document).on('click','.cancel',function(e){
				objDosa.cancelOrder(e);
			});

			$(document).on('click','.pay',function(e){
				e.preventDefault();
				objDosa.showPaymentView();
			});

			$(document).on('click','.remove-product-by-id',function(e){
				var intProductId = parseInt($(this).attr('id'));
				objDosa.removeProduct(intProductId);
			});

			$(document).on('click','.sub-level-inner',function(e){
				$(this).attr('data-clicked',true);
				var intTermId = parseInt($(this).attr('data-child'));

				if(!$(this).hasClass('show-icon')){
					$(this).addClass('show-icon');
					$('.term-'+intTermId).show();
				}else{
					$('.term-'+intTermId).hide();
					$(this).removeClass('show-icon');
				}
			});
		},
		cancelOrder:function(e){
			e.preventDefault();
			var objData = {
				action: 'destroy_my_session',
			};
			// Get Respone back send send response array to next function
			$.getJSON(objDosa.ajaxurl, objData, function(objResponse){
				if(objResponse.error == false){
					if(objResponse.cart_string.length>0){
						objDosa.updateAppStates(objResponse);
						objDosa.showOrderView();
					}
				}
			});
		},

		removeProduct:function(intProductId){
			var objData = {
				action: 'remove_product',
				id:intProductId
			};
			// Get Respone back send send response array to next function
			$.getJSON(objDosa.ajaxurl, objData, function(objResponse){
				if(objResponse.error == false){
					if(objResponse.cart_string.length>0){
						objDosa.updateAppStates(objResponse);
						objDosa.showOrderView();
					}
				}
			});
		},
		showSubMenu:function(intTermId, strChildCheck){
			objDosa.element_order_modal.hide();
			$('.inner-box').hide();
			$('.sub-level-inner').hide();
			$('[data-parent="'+intTermId+'"]').show();
			if(strChildCheck == 'false'){
				$('.term-'+intTermId).show();
			}
			$('.order-modal').hide();
			$('.payments-container').hide();
			$('.discount-modal').hide();
		},
		throwOnModal:function(strTitle, intPrice, strStyle, intProductId, strDescription){
			objDosa.element_order_modal.hide();
			objDosa.element_success_message.hide();
			objDosa.element_box.hide();
			$('.modal-image').attr('style',strStyle);
			$('.modal-title').text(strTitle);
			$('#add-to-order').attr('data-pid',intProductId);
			$('#modal-price-value').attr('data-original',intPrice);
			$('#modal-price-value').text(0);
			$('#value').text('0');
			$('.modal-description').html(strDescription);
			objDosa.element_modal.show();
			$('.order-modal').hide();
			$('.payments-container').hide();
			$('.discount-modal').hide();
		},
		resetModal:function(){
			objDosa.element_success_message.html();
			objDosa.element_success_message.hide();
			objDosa.element_box.hide();
			$('.order-modal').hide();
			$('.modal-image').attr('style','');
			$('.modal-title').text('');
			$('#add-to-order').removeAttr('data-pid');
			$('#modal-price-value').attr('data-original',0);
			$('#modal-price-value').text(0);
			$('.modal-description').html('');
			objDosa.element_modal.hide();
			objDosa.element_box.show();
			$('.payments-container').hide();
			$('.discount-modal').hide();
		},
		incrementValue:function(e){
			var valueElement = $('#value');
			var newValue = Math.max(parseInt(valueElement.text()) + e.data.increment, 0);
			var intPrice = parseFloat($("#modal-price-value").attr('data-original'));
			// Reference  https://stackoverflow.com/questions/2244862/jquery-plus-minus-incrementer
			valueElement.text(newValue);
			$("#modal-price-value").text(newValue*intPrice);
			return false;
		},
		addToOrder:function(intPid,intValue){
			// Send Action to cart controller 
			objDosa.element_success_message.html('');
			objDosa.element_success_message.hide();
			objDosa.element_order.html('<i class="fas fa-stroopwafel fa-spin"></i>');
	        var objData = {
	            action: 'add_to_order',
	            id: intPid,
	            value: intValue
	        };
	        // Get Respone back send send response array to next function
	        $.getJSON(objDosa.ajaxurl, objData, function(objResponse){
	            if(objResponse.error == false){
	            	if(objResponse.success_message.length>0){
	            		objDosa.element_success_message.show();
	            		objDosa.element_success_message.html(objResponse.success_message);
	            		objDosa.element_order.html('Add To Order');
	            		objDosa.updateAppStates(objResponse);
	            	}
	            }
	        });
		},

		qtyChangedOnOrderModal:function(intValue,intProductId){
			$('#price-col-'+intProductId).html('<i class="fas fa-stroopwafel fa-spin"></i>');
			var objData = {
	            action: 'modal_change_trigger',
	            id: intProductId,
	            value: intValue
	        };
	        // Get Respone back send send response array to next function
	        $.getJSON(objDosa.ajaxurl, objData, function(objResponse){
	        	objDosa.updateAppStates(objResponse);
	    		objDosa.showOrderView();    	    
	        });
		},

		showOrderView:function(){
			objDosa.element_success_message.html('');
			objDosa.element_success_message.hide();
			objDosa.element_modal.hide();
			objDosa.element_box.hide();
			$('.order-modal').show();
			$('.payments-container').hide();
			$('.sub-level').hide();
			$('.discount-modal').hide();
		},
		showDiscountView:function(){
			objDosa.element_success_message.html('');
			objDosa.element_success_message.hide();
			objDosa.element_modal.hide();
			objDosa.element_box.hide();
			$('.order-modal').show();
			$('.discount-modal').hide();
			$('.payments-container').hide();
			$('.sub-level').hide();
		},
		showPaymentView:function(){
			objDosa.element_success_message.html('');
			objDosa.element_success_message.hide();
			objDosa.element_modal.hide();
			objDosa.element_box.hide();
			$('.order-modal').hide();
			$('.payments-container').show();
			$('.sub-level').hide();
			$('.discount-modal').hide();
		},
		updateAppStates(objResponse){
			if(objResponse.cart_string.length>0){
				objDosa.element_ajax_cart.html(objResponse.cart_string);
			}
			
			(objResponse.show_buttons == true) ? $('.top-bar-order-buttons').removeClass('hide-out') : $('.top-bar-order-buttons').addClass('hide-out');
			
			objDosa.element_order_button_value.html(objResponse.total_items);
			
			objDosa.element_total_payment_button.html(objResponse.order_total.toFixed(2));
			
			$('.order-ajax-hook').hide();
			
			$.each(objResponse.products, function() {
				var intProductId = this.id; 
				var intValue 	 = this.value;
				$('#order-ajax-hook-'+intProductId).html('<span class="order-in-cart">'+intValue+' in Order</span>');
				$('#order-ajax-hook-'+intProductId).show();
			});
		}
	};
	objDosa.initDosa();
});
