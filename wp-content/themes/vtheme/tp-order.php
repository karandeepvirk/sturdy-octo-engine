<?php 
/**
* Template Name: Product
*
*/
get_header();

if(class_exists('Menu_Model')){
	$arrCategories = get_terms(
		array(
			'taxonomy' => 'menu_category',
			'hide_empty' => true
		)
	);
}

$arrParents = array();
$arrChildrens = array();

foreach($arrCategories as $key => $objCategory){
	if($objCategory->parent == 0){
		$arrParents[] = $objCategory;
	}
	if($objCategory->parent != 0){
		$arrChildrens[] = $objCategory;
	}
}

$argsProducts = array(
	'post_type' =>'menu',
	'posts_per_page' => -1,
	'numberposts' => -1
);

$arrPosts = get_posts($argsProducts);

?>
<div class="main-page-container">
	<!-- <div class="window-bar">
		<h2>Categories <i class="fas fa-chevron-circle-right"></i></h2>
	</div> -->
	<div class="category-inner">
		<div class="inner-carousel">
			<button type="button" class="slick-custom-arrow slick-top-arrow">
				<i class="fas fa-chevron-up fa-size"></i>
			</button>
			<div class="slick-carousel">
				<?php
				if(!empty($arrParents)){
					foreach($arrParents as $key => $objParents){
						$arrChildrenCheck = array();
						$strChildrenCheck = false;
						$strImage = get_term_meta($objParents->term_id,'term_image',true);
						$bolChildCheck = Menu_Model::checkChilds($objParents->term_id);
						if(!empty($strImage)){?>
							<div data-child="<?php echo $bolChildCheck;?>" data-id ="<?php echo $objParents->term_id;?>" class="slick-carousel-image" style="background-image:url(<?php echo $strImage;?>);">
								<h2 class="carousel-title"><?php echo $objParents->name;?></h2>
							</div>
						<?php }
					}
				}
				?>
			</div>
			<button type="button" class="slick-custom-arrow slick-bottom-arrow"><i class="fas fa-chevron-down fa-size"></i></button>
		</div>
	</div>

	<div class="sub-level">
		<?php
		if(!empty($arrChildrens)){
			foreach($arrChildrens as $key => $objChildrens){
				$strImage = get_term_meta($objChildrens->term_id,'term_image',true);
				if(!empty($strImage)){?>
					<div class="sub-level-inner" data-parent = "<?php echo $objChildrens->parent;?>" data-child = "<?php echo $objChildrens->term_id;?>">
						<div style="background-image: url(<?php echo $strImage;?>);" class="sub-level-image">
							<i class="fas fa-check fa-3x"></i>
						</div>
						<h2><?php echo $objChildrens->name;?></h2>
					</div>
				<?php }
			}
		}
		?>
	</div>

	<div class="all-items">
		<?php 
		foreach($arrPosts as $key => $objPosts){
			$arrTermsId = array();
			$intAttachment 	= get_post_thumbnail_id($objPosts->ID);
			$strImageUrl 	= wp_get_attachment_image_src($intAttachment,'full')[0];
			$arrTerms 		= get_the_terms($objPosts->ID,'menu_category');
			$strDescription = Helper_Controller::getPostContent($objPosts->post_content,200, true, false);
			$strPrice 		= get_post_meta($objPosts->ID,'item_price',true);
			$strItemMeat  	= get_post_meta($objPosts->ID,'item_meat',true);
			if(!empty($arrTerms)){
				foreach ($arrTerms as $key => $objTerms) {
					$arrTermsId[] 	= 'term-'.$objTerms->term_id;
				}
			}
			$strTermsId	= implode(' ', $arrTermsId);
			?>
			<div class="inner-box <?php echo $strTermsId;?>" id="<?php echo $objPosts->ID;?>" data-terms='<?php echo $strTermsId;?>'>
				<div class="item-image" style="background-image: url(<?php echo $strImageUrl;?>);">
					<?php 
						if($strItemMeat == 'vegetarian'){?>
							<img title="Vegetarian Product" class="vegan-image" src="<?php echo get_template_directory_uri()?>/images/veg.svg.png;?>">
						<?php }else{?>
							<img title ="Meat In Product" class="vegan-image" src="<?php echo get_template_directory_uri()?>/images/meat.png;?>">
						<?php }
					?>
					<h1 class="item-title"><?php echo ucwords(strtoupper($objPosts->post_title));?></h1>
					<span class="price-circle">$
						<span class="price-value"><?php echo $strPrice; ?></span>
					</span>
					<div class="inner-box-description" style="display: none;"><?php echo $strDescription;?></div>
					<div class="order-ajax-hook" id="order-ajax-hook-<?php echo $objPosts->ID;?>">
					<?php 
						if(Order_Controller::checkIfProductInOrder($objPosts->ID)){?>
							<span class="order-in-cart">
								<?php echo Order_Controller::getTotalByIdInOrder($objPosts->ID).' in Order';?>
							</span>
						<?php }
					?>
					</div>
				</div>
			</div>
		<?php }?>
		<div class="current-modal">
			<div class="modal-top">
				<span class="close-modals">
					<i class="fas fa-times"></i>
				</span>
				<h2 class="modal-title"><?php echo $objPosts->post_title;?></h2>
			</div>

			<div class="modal-inner">
				<div class="modal-left">
					<div class="modal-image"></div>
				</div>
				<div class="modal-right">
					<div class="modal-price">$<span id="modal-price-value" data-original="<?php echo $strPrice;?>"><?php echo $strPrice;?></span></div>
					<div class="modal-description"><?php echo $strDescription;?></div>
					<div class="bottom-part">
						<div class="incrementor">
							<a id="minus" href="#"><i class="fas fa-minus-circle"></i></a>
								<span id="value">1</span>
							<a id="plus" href="#"><i class="fas fa-plus-circle"></i></a>
						</div>
						<div class="bottom-button">
							<button class="primary-button" id="add-to-order">Add To Order</button>
						</div>
					</div>
					<div class="success-message"></div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="discount-modal">
	<div class="modal-top">
		<span class="close-modals">
			<i class="fas fa-times"></i>
		</span>
		<h2 class="modal-title">DISCOUNT</h2>
	</div>

	<div class="modal-inner">
		<div class="modal-left">

		</div>
		<div class="modal-right">
			
		</div>
	</div>
</div>
<div class="ajax-cart"><!---->  		
	<?php echo Order_Controller::cartString();?>
</div>

<div class="payments-container">
	<div class="payments-accrodian">
		<div class="paymets-header">
			<h2>Payments</h2>
			<span><i class="fas fa-chevron-down"></i></span>
		</div>
		<div class="payments-body">
			<div class="payment-modal">
				<div class="modal-top">	
					<h2 class="modal-title-payment">Online Payment</h2>
					<span class="payment-form-button">
						<i class="fas fa-minus"></i>
					</span>
				</div>
				<div id="form-container">
					<img class="square-image" src="<?php echo get_template_directory_uri()?>/images/square-logo.png">
					<div id="sq-card-number"></div>
					<div class="third" id="sq-expiration-date"></div>
					<div class="third" id="sq-cvv"></div>
					<div class="third" id="sq-postal-code"></div>
					<button id="sq-creditcard" onclick="onGetCardNonce(event)">Pay $<span class="total-payment-button"><?php echo showPrice(Order_Controller::getOrderTotal());?></span></button><br>
				</div> 
			</div>

			<div class="payment-modal">
				<div class="modal-top">	
					<h2 class="modal-title-payment">Cash Payment</h2>
					<span class="payment-form-button">
						<i class="fas fa-minus"></i>
					</span>
				</div>
			</div>

			<div class="payment-modal">
				<div class="modal-top">	
					<h2 class="modal-title-payment">Cheque Payment</h2>
					<span class="payment-form-button">
						<i class="fas fa-minus"></i>
					</span>
				</div>
			</div>

			<div class="payment-modal">
				<div class="modal-top">	
					<h2 class="modal-title-payment">Terminal Payment</h2>
					<span class="payment-form-button">
						<i class="fas fa-minus"></i>
					</span>
				</div>
			</div>
		</div>
	</div>
</div>
<?php 
	get_footer();
?>
<script type="text/javascript">
     // Create and initialize a payment form object
     const paymentForm = new SqPaymentForm({
       // Initialize the payment form elements
       
       //TODO: Replace with your sandbox application ID
       applicationId: "sandbox-sq0idb-cJcEMWHvjocQOYAcv5hI7Q",
       inputClass: 'sq-input',
       autoBuild: true,
       // Customize the CSS for SqPaymentForm iframe elements
       inputStyles: [{
           fontSize: '16px',
           lineHeight: '24px',
           padding: '16px',
           placeholderColor: '#a0a0a0',
           backgroundColor: 'transparent',
       }],
       // Initialize the credit card placeholders
       cardNumber: {
           elementId: 'sq-card-number',
           placeholder: 'Card Number'
       },
       cvv: {
           elementId: 'sq-cvv',
           placeholder: 'CVV'
       },
       expirationDate: {
           elementId: 'sq-expiration-date',
           placeholder: 'MM/YY'
       },
       postalCode: {
           elementId: 'sq-postal-code',
           placeholder: 'Postal'
       },
       // SqPaymentForm callback functions
       callbacks: {
           /*
           * callback function: cardNonceResponseReceived
           * Triggered when: SqPaymentForm completes a card nonce request
           */
           cardNonceResponseReceived: function (errors, nonce, cardData) {
           if (errors) {
               // Log errors from nonce generation to the browser developer console.
               console.error('Encountered errors:');
               errors.forEach(function (error) {
                   console.error('  ' + error.message);
               });
               alert('Encountered errors, check browser developer console for more details');
               return;
           }
              alert(`The generated nonce is:\n${nonce}`);
              //TODO: Replace alert with code in step 2.1
           }
       }
     });
    function onGetCardNonce(event) {
       paymentForm.requestCardNonce();
    }
     //TODO: paste code from step 1.1.4
</script>
