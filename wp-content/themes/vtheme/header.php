<!DOCTYPE html>
<html>
	<?php 
		// echo '<pre>';
		// var_dump($_SESSION);
		// echo '</pre>';
	?>
	<head>
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
			<a href="" class="top-button global">Global Orders</a>
			<a href="" class="top-button order-button">Order (<span id="total-order-button"><?php echo Order_Controller::getTotalProductsInOrder();?></span>)</a>
			<a href="" class="top-button pay">Pay ($<span class="total-payment-button"><?php echo Order_Controller::getOrderTotal()['total'];?></span>)</a>
			<a href="" class="top-button cancel">Cancel Order</a>
		</div>
	</div>
<body>
