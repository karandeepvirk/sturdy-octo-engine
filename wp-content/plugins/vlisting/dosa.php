<?php
defined('ABSPATH') OR exit;
/**
 * Plugin Name: Dosa Crepe Cafe
 * Plugin URI: http://vcupcode.ca
 * Description: A plugin to manage listings
 * Version: 1.3
 * Author: Karandeep Singh Virk
 * Author URI: http://vcupcode.ca
*/
?>
<?php
	include 'helper_controller.php';
	include 'menu_controller.php';
	include 'menu_model.php';
	include 'order_controller.php';
	include 'flow_controller.php';
	wp_enqueue_script('admin-scripts', plugin_dir_url(__FILE__).'/admin-scripts.js');
	wp_enqueue_style('style', plugin_dir_url(__FILE__).'/style.css');
	register_activation_hook(__FILE__, 'add_operator_role');
	
	/**
	* Helper Function
	*
	*/
	function showPrice($intMeta, $strDollar = false){
		if($strDollar == true){
			$strString = '$';
		}
		$intMeta = floatval($intMeta);
		$intMeta = number_format($intMeta, 3);
		$intMeta = $strString.$intMeta;
		return $intMeta;
	}
	
	/**
	* Helper Function
	*
	*/
	function add_operator_role() {
       add_role('order_operator', 'Order Operator', get_role('administrator')->capabilities);
   }
?>