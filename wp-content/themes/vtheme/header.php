<!DOCTYPE html>
<html>
	<head>
	<?php 
		// echo '<pre>';
		// var_dump($_SESSION);
		// echo '</pre>';
		// Flow_Controller::IfUserCanModifyOrder();
	?>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link href="<?php echo get_template_directory_uri()?>/style.css" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.css">
		<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick-theme.min.css">
		<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
		<script src="https://kit.fontawesome.com/6837e55fb8.js" crossorigin="anonymous"></script>
		<script type="text/javascript" src="https://js.squareupsandbox.com/v2/paymentform"></script>
	</head>
	<div class="main-top-bar">
		<div class="logo">
			<img src="<?php echo get_template_directory_uri()?>/images/logo.png">
		</div>
		<div class="top-bar-buttons">
			<?php 
			if(!is_user_logged_in()){?>
				<a href="<?php echo wp_login_url();?>" class="top-button clock-in">LOGIN</a>
				<?php }else{?>
				<a href="<?php echo wp_logout_url();?>" class="top-button clock-out">LOGOUT</a>
			<?php }?>
			<a href="" class="top-button global">GLOBAL ORDERS</a>
			<?php 
				$intHideOut = (Order_Controller::getTotalProductsInOrder() == 0) ? 'hide-out' : '';
				if(Flow_Controller::IfUserCanModifyOrder()){?>
					<a href="" class="top-bar-order-buttons <?php echo $intHideOut;?> top-button discount">ADD DISCOUNT</a>
				<?php }
			?>
				<a href="" class="top-bar-order-buttons <?php echo $intHideOut;?> top-button order-button">Order (<span id="total-order-button"><?php echo Order_Controller::getOrderTotal()['total_items'];?></span>)</a>
				<a href="" class="top-bar-order-buttons <?php echo $intHideOut;?> top-button pay">Pay ($<span class="total-payment-button"><?php echo Order_Controller::getOrderTotal()['total'];?></span>)</a>
				<a href="" class="top-bar-order-buttons <?php echo $intHideOut;?> top-button cancel">CANCEL ORDER</a>
		</div>
	</div>
<body>
