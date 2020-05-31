<?php 
// Exit if accessed directly
defined('ABSPATH') OR exit;
class Flow_Controller{	
	function __construct(){

	}

	public static function IfUserCanModifyOrder(){
		if(is_user_logged_in()){
			$intCurrentUserId = get_current_user_id();
			if($intCurrentUserId>0){
				$objUserMeta = get_userdata($intCurrentUserId);
				$arrUserRoles = $objUserMeta->roles;
				if(in_array('order_operator',$arrUserRoles) OR in_array('administrator',$arrUserRoles)){
					return true;
				}
				else{
					return false;
				}
			}else{
				return false;
			}
		}
		else{
			return false;
		}
	} 
}
new Flow_Controller;
?>