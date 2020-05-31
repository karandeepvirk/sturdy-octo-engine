<?php 
// Exit if accessed directly
defined('ABSPATH') OR exit;
class Helper_Controller{	
	public static function getPostContent($strString, $maxCount=0, $stripTags = false, $applyFilter = true){
		// If Empty Return False
		if(empty($strString)){
			return false;
		}

		// Get Length Of String
		$intStringLength = strlen($strString);
		
		// If String Tags is True 
		if($stripTags){
			$strString = strip_tags($strString);
			$strString = stripslashes($strString);
		}
		if($applyFilter){
			$strString = apply_filters('the_content',$strString);
		}
		
		if($intStringLength>$maxCount){
			$strAppend = '..';
			$strString = substr($strString, 0, $maxCount);
			$strString = $strString.$strAppend;
		}else{
			$strAppend = '';
		}

		return $strString;
	} 
}
new Helper_Controller;
?>