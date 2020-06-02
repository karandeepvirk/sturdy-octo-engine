<?php 
// Exit if accessed directly
defined('ABSPATH') OR exit;
class Order_Controller{	
	
	function __construct(){
	    add_action('wp_ajax_add_to_order', array(__CLASS__, 'addToOrder'));
	    add_action('wp_ajax_nopriv_add_to_order', array(__CLASS__, 'addToOrder'));	
	   	add_action('wp_ajax_modal_change_trigger', array(__CLASS__, 'modalChangeTrigger'));
	    add_action('wp_ajax_nopriv_modal_change_trigger', array(__CLASS__, 'modalChangeTrigger'));
	    add_action('wp_ajax_destroy_my_session', array(__CLASS__, 'destroyMySession'));
	    add_action('wp_ajax_nopriv_destroy_my_session', array(__CLASS__, 'destroyMySession'));
	    add_action('wp_ajax_get_ajax_cart', array(__CLASS__, 'getAjaxCart'));
	    add_action('wp_ajax_nopriv_get_ajax_cart', array(__CLASS__, 'getAjaxCart'));
	    add_action('wp_ajax_remove_product', array(__CLASS__, 'removeProduct'));
	    add_action('wp_ajax_nopriv_remove_product', array(__CLASS__, 'removeProduct'));	
	}

	public static function getInitializedArray(){
		$arrReturn = array(
			'error' => false,
			'error_message' => '',
			'success_message' => '',
			'cart_string' => '',
			'order_total' => 0,
			'total_products' => 0,
			'show_buttons' => false,
			'total_items' =>0
		);
		return $arrReturn;
	}

	/**
	* Add To Order
	*
	*/
	public static function addToOrder(){

		$arrReturn = self::getInitializedArray();
		$intProduct = isset($_GET['id']) 	? (int) $_GET['id'] : 0;
		$intValue 	= isset($_GET['value']) ? (int) $_GET['value'] : 0;
		
		if($intProduct == 0){
			$arrReturn['error'] = true;
			$arrReturn['error_message'] .= '<p class="error-message">Please Select  Number of items to buy.</p>';
			echo json_encode($arrReturn);
			die;
		}

		if($intValue == 0){
			$arrReturn['error'] = true;
			$arrReturn['error_message'] .= '<p class="error-message">Please Select  Product.</p>';
			echo json_encode($arrReturn);
			die;
		}

		if($intProduct>0 AND $intValue>0){
			$intReturn = self::updateOrder($intProduct, $intValue);
			if($intReturn>0){
				$arrReturn = self::getUpdatedState();
				$arrReturn['success_message'] .= 'You have total '.$intReturn.' item(s) of this product in your order now.';
			}
		}
		echo json_encode($arrReturn);
		die;
	}
	
	/**
	* Update Order 
	* 
	*/
	public static function updateOrder($intProduct, $intValue, $strEvent = NULL){
		if($intProduct>0 AND $intValue>0){
			if(array_key_exists($intProduct, $_SESSION['order'])){
				$intExisting = (int)$_SESSION['order'][$intProduct][0]['value'];
				if($strEvent == NULL){
					$intNew = $intExisting+$intValue;
				}else{
					$intNew = $intValue;
				}
				$_SESSION['order'][$intProduct][0]['value'] = $intNew;
			}else{
				$_SESSION['order'][$intProduct][] = array(
					'value' => $intValue
				);
			}
		}
		if(self::checkIfProductInOrder($intProduct)){
			return self::getTotalByIdInOrder($intProduct);
		}
	}

	/**
	* Check if Product is in Order by its Id and get total value
	* 
	*/
	public static function getTotalByIdInOrder($intProduct){
		$intReturn = 0;
		if($intProduct>0){
			if(array_key_exists($intProduct, $_SESSION['order'])){
				$intReturn = $_SESSION['order'][$intProduct][0]['value'];
			}
		}

		return $intReturn;
	}

	/**
	* Check Product is in Order
	* 
	*/
	public static function checkIfProductInOrder($intProduct){
		if($intProduct>0){
			if(!empty($_SESSION['order']) AND is_array($_SESSION['order'])){
				if(array_key_exists($intProduct, $_SESSION['order'])){
					return true;
				}
			}
		}
		return false;
	}
	
	/**
	* get All Products is in Order
	* 
	*/
	public static function getProductsInOrder(){
		return $_SESSION['order'];
	}

	/**
	* Get total number of products in order
	* 
	*/
	public static function getTotalProductsInOrder(){
		$intReturn = 0;
		if(!empty($_SESSION['order']) AND is_array($_SESSION['order'])){
			$intReturn = count($_SESSION['order']);
		}
		return $intReturn;
	}

	/**
	* Modal Change Trigger
	* 
	*/
	public static function modalChangeTrigger(){
		$arrReturn = self::getInitializedArray();
		$intProduct = isset($_GET['id']) 	? (int) $_GET['id'] : 0;
		$intValue 	= isset($_GET['value']) ? (int) $_GET['value'] : 0;
		
		if($intProduct == 0){
			$arrReturn['error'] = true;
			$arrReturn['error_message'] .= '<p class="error-message">Please Select  Number of items to buy.</p>';
			echo json_encode($arrReturn);
			die;
		}

		if($intValue == 0){
			$arrReturn['error'] = true;
			echo json_encode($arrReturn);
			die;
		}

		if($intProduct>0 AND $intValue>0){
			$intReturn = self::updateOrder($intProduct, $intValue, 'Modal');
			if($intReturn>0){
				$arrReturn = self::getUpdatedState();
			}
		}

		echo json_encode($arrReturn);
		die;
	}
	/**
	*
	* Get Cart String 
	*/
	public static function cartString(){
		$strHTML = '';
		ob_start();?>
		<!-- GET HTML OBJECT -->
		<div class="order-container">
		<h2>ORDER</h2>
		<div class="order-modal">
			<div class="grid-body">
				<?php  
				$arrOrders = array();
				$arrOrders = Order_Controller::getProductsInOrder();
				if(!empty($arrOrders)){
					foreach ($arrOrders as $key => $objOrders){
						$intProduct 	= $key;
						$strProduct 	= get_the_title($intProduct);
						$intValue 		= $objOrders[0]['value'];
						$intPrice 		= get_post_meta($intProduct, 'item_price',true);
						$intTotal		= $intPrice*$intValue;
						$orderTotal		+= $intTotal;
						?>
						<div class="body-row main-col">
							<span><?php echo $strProduct;?></span>
						</div>
						<div class="body-row second-col">
							&nbsp; <i class="fas fa-times"></i> &nbsp;<input data-product-id="<?php echo $intProduct;?>" class="order-modal-p-value" type="number" value="<?php echo (int) $intValue;?>">
						</div>
						<div class="body-row price-col" id="price-col-<?php echo $intProduct;?>">
							$<?php echo showPrice($intTotal);?>
						</div>
						<div class="body-row remove-product" id="<?php echo $intProduct;?>">
							<button class="remove-product-by-id" id="<?php echo $intProduct;?>"><i class="fas fa-times-circle"></i> </button>
						</div>
					<?php }?>
				<?php } else{?>
					<p class="no-items"><i class="far fa-surprise"></i> You have 0 items in your order.</p>
				<?php }?>
			</div>
			<h3>Order Total : $<?php echo showPrice($orderTotal);?></h3>
		</div>
		<?php 
		if(Flow_Controller::IfUserCanModifyOrder()){?>
			<div class="discount-modal">
				<div class="discount-modal-top">
					<h2 class="discount-modal-title">DISCOUNT</h2>
					<span><i class="fas fa-chevron-up"></i></span>
				</div>

				<div class="discount-modal-inner">
					<input type="number" placeholder="Type Number" name="order-discount" class="discount-modal-input">
					<button class="discount-modal-input">ADD</button>
				</div>
			</div>
		<?php }?>

				<?php 
		if(Flow_Controller::IfUserCanModifyOrder()){?>
			<div class="table-modal">
				<div class="table-modal-top">
					<h2 class="table-modal-title">TABLE</h2>
					<span><i class="fas fa-chevron-up"></i></span>
				</div>

				<div class="table-modal-inner">
					<span value="1">1</span>
					<span value="2">2</span>
					<span value="3">3</span>
					<span class="active" value="4">4</span>
					<span value="5">5</span>
					<span value="6">6</span>
					<span value="7">7</span>
					<span value="8">8</span>
					<span value="9">9</span>
					<span value="10">10</span>
				</div>
			</div>
		<?php }?>
	</div>
	<?php 
	$strHTML = ob_get_clean();
	return $strHTML;
	}
	/*
	* Destroy Order
	*
	*/
	public static function destroyMySession(){

		$arrReturn = self::getInitializedArray();
		$_SESSION['order'] = array();
		if(empty($_SESSION['order'])){
			$arrReturn = self::getUpdatedState();
		}
		echo json_encode($arrReturn);
		die;
	}

	/**
	* Remove Product
	*
	*/
	public static function removeProduct(){

		$arrReturn = self::getInitializedArray();
		$intProduct = isset($_GET['id']) ? (int) $_GET['id'] : 0;
		
		if($intProduct>0){
			if(self::checkIfProductInOrder($intProduct)){
				if(self::removeProductById($intProduct)){
					$arrReturn = self::getUpdatedState();
				}
			}
		}
		echo json_encode($arrReturn);
		die;
	}

	/**
	* Remove Product By Id
	*
	*/
	public static function removeProductById($intProduct){
		if(!empty($_SESSION['order']) AND is_array($_SESSION['order'])){
			if(array_key_exists($intProduct, $_SESSION['order'])){
				unset($_SESSION['order'][$intProduct]);
				return true;
			}
		}
	}

	/**
	* Get Total Order Payment
	*
	*/

	public static function getOrderTotal(){
		$arrReturn = array(
			'total' => 0,
			'products' => array()
		);
		$arrProducts = array();

		if(!empty($_SESSION['order']) AND is_array($_SESSION['order'])){
			$arrOrders = Order_Controller::getProductsInOrder();
			if(!empty($arrOrders)){
				foreach($arrOrders as $key => $objOrders){
					$intProduct 	= $key;
					$intValue 		= $objOrders[0]['value'];
					$intPrice 		= get_post_meta($intProduct, 'item_price',true);
					$intTotal		= $intPrice*$intValue;
					$intItems 		+= $objOrders[0]['value'];
					$orderTotal		+= $intTotal;
					$arrProducts[]	= array(
						'id' => $intProduct,
						'value' => $intValue
					);
				}
				$arrReturn['total'] 	  = (double) $orderTotal;
				$arrReturn['products'] 	  = $arrProducts;
				$arrReturn['total_items'] = $intItems;
			}
		}
		return $arrReturn;
	}

	/*
	* Get Updated State
	*
	*/

	public static function getUpdatedState(){
		$arrReturn = array();
		$arrReturn['error'] 		 = false;
		$arrReturn['cart_string'] 	 = self::cartString();
		$arrReturn['order_total'] 	 = self::getOrderTotal()['total'];
		$arrReturn['products'] 	 	 = self::getOrderTotal()['products'];
		$arrReturn['total_items'] 	 = self::getOrderTotal()['total_items'];
		$arrReturn['total_products'] = self::getTotalProductsInOrder();
		if(self::getTotalProductsInOrder()>0){
			$arrReturn['show_buttons'] = true;
		}
		return $arrReturn;
	}
}
new Order_Controller;
?>