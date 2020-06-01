<?php
defined('ABSPATH') OR exit;
abstract class Menu_Model{
	
	/**
	* Add Meta Box
	*
	*/
	public static function addMetaBox(){
        add_meta_box(
            'meta_fields',
            'Dosa Crepe Cafe Item Details',
            array(__CLASS__,'getFields'),
            'menu'                   
        );
	}
	
	/**
	* Get Data Fields
	*
	*/
	public static function getDataFields($post){
		if(empty($post)){
			return;
		}
		// Get Post Meta
		$arrReturn['item_price'] 		= get_post_meta($post->ID,'item_price',true);
		$arrReturn['item_disable'] 		= get_post_meta($post->ID,'item_disable',true);
		$arrReturn['show_in_addon'] 	= get_post_meta($post->ID,'show_in_addon',true);
		$arrReturn['alcohol_in_item'] 	= get_post_meta($post->ID,'alcohol_in_item',true);
		$arrReturn['item_has_spice'] 	= get_post_meta($post->ID,'item_has_spice',true);
		$arrReturn['item_meat'] 	    = get_post_meta($post->ID,'item_meat',true);

		return $arrReturn;
	}

	public static function checkChilds($intParentTermId){
		$strReturn = 'false';
		$arrChildrenCheck = get_term_children($intParentTermId,'menu_category');
		if(!empty($arrChildrenCheck)){
			$strReturn = 'true';
		}
		return $strReturn;
	}

	/**
	* Get Basic Fields
	*
	*/
	public static function getFields($post){

		$arrFields = self::getDataFields($post);
		if(isset($arrFields['item_disable'])){
			if($arrFields['item_disable'] == true){
				$strCheckDisable = 'checked';
			}
		}

		if(isset($arrFields['item_has_spice'])){
			if($arrFields['item_has_spice'] == true){
				$strCheckSpice = 'checked';
			}
		}
		
		if(isset($arrFields['show_in_addon'])){
			if($arrFields['show_in_addon'] == true){
				$strCheckAddon = 'checked';
			}
		}
	
		if(isset($arrFields['alcohol_in_item'])){
			if($arrFields['alcohol_in_item'] == true){
				$strCheckAlcohol = 'checked';
			}
		}
		?>

		<!--Item Price-->
		<div class="admin-meta-fields">
			<div class="input-holder">
				<label>Item Price</label>
				<input type="number" name="item_price" class="admin-input" value="<?php echo $arrFields['item_price']?>">
			</div>
		</div>

		<!--Veg Or Non-VEG-->
		<div class="admin-meta-fields">
			<div class="input-holder select-holder">
				<label>Select Meat Type: </label>
				<select name="item_meat">
					<option <?php echo $arrFields['item_meat'] == 'vegetarian' ? 'selected' : '';?> value="vegetarian">Vegetarian</option>
					<option <?php echo $arrFields['item_meat'] == 'chicken' ? 'selected' : '';?> value="chicken">Chicken</option>
					<option <?php echo $arrFields['item_meat'] == 'bacon' ? 'selected' : '';?> value="bacon">Bacon</option>
					<option <?php echo $arrFields['item_meat'] == 'beef' ? 'selected' : '';?> value="beef">Beef</option>
				</select>
			</div>
		</div>
		<!--Has Spice Levels-->
		<div class="admin-meta-fields">
			<div class="input-holder">
				<input type="checkbox" name="item_has_spice" class="admin-checkbox" value="true" <?php echo $strCheckSpice;?>> 
				<label>Has Spice Levels?</label>
			</div>
		</div>
		<!--Show In Add On Screen-->
		<div class="admin-meta-fields">
			<div class="input-holder">
				<input type="checkbox" name="show_in_addon" class="admin-checkbox" value="true" <?php echo $strCheckAddon;?>> 
				<label>Show In Addon Screen?</label>
			</div>
		</div>
		
		<!--Alcohol In Item-->
		<div class="admin-meta-fields">
			<div class="input-holder">
				<input type="checkbox" name="alcohol_in_item" class="admin-checkbox" value="true" <?php echo $strCheckAlcohol;?>> 
				<label>Alcoholic?</label>
			</div>
		</div>

		<!--Disable Item-->
		<div class="admin-meta-fields">
			<div class="input-holder">
				<input type="checkbox" name="item_disable" class="admin-checkbox" value="true" <?php echo $strCheckDisable;?>> 
				<label>Disable This Item?</label>
			</div>
		</div>
	<?php }

	/**
	* Save Fields
	*
	*/
	public static function saveFields($post_id){
		// Update Post Meta
		update_post_meta($post_id,'item_price',$_POST['item_price']);
		update_post_meta($post_id,'item_disable',$_POST['item_disable']);
		update_post_meta($post_id,'show_in_addon',$_POST['show_in_addon']);
		update_post_meta($post_id,'alcohol_in_item',$_POST['alcohol_in_item']);
		update_post_meta($post_id,'item_has_spice',$_POST['item_has_spice']);
		update_post_meta($post_id,'item_meat',$_POST['item_meat']);
	}
}
?>