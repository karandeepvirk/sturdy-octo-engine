<?php 
// Exit if accessed directly
defined('ABSPATH') OR exit;
class Menu_Controller{
	
	protected static $strPostTypeSlug 		= 'menu';
	protected static $strPostTypeSingle 	= 'Dosa Cafe Menu';
	protected static $strPostTypePlural 	= 'Dosa Cafe Menu';
	protected static $strMenuCategories 	= 'menu_category';
	// Contruct Class
	function __construct(){
		add_action('init', array(__CLASS__,'createPost'));
		add_action('init', array(__CLASS__,'createTaxonomies'));
		add_action('add_meta_boxes', array('Menu_Model','addMetaBox'));
		add_action('save_post', array('Menu_Model','saveFields'));
		add_action(static::$strMenuCategories.'_add_form_fields', array(__CLASS__,'add_term_image'), 10, 2);
		add_action('created_'.static::$strMenuCategories, array(__CLASS__,'save_term_image'), 10, 2);
		add_action(static::$strMenuCategories.'_edit_form_fields', array(__CLASS__,'edit_image_upload'), 10, 2);
		add_action('edited_'.static::$strMenuCategories, array(__CLASS__,'update_image_upload'), 10, 2);
        add_filter('manage_'.static::$strPostTypeSlug.'_posts_columns', array(__CLASS__,'setColumns'));
        add_action('manage_'.static::$strPostTypeSlug.'_posts_custom_column' ,array(__CLASS__,'setColumnsContent'), 10, 2);
	}

	/**
	* Create Post Type
	*
	*/
	function createPost(){

		register_post_type(static::$strPostTypeSlug,
			// CPT Options
		    array(
		        'labels' => array(
		            'name' => __(static::$strPostTypePlural),
		            'singular_name' => __(static::$strPostTypeSingle)
		        ),
		        'public' => true,
		        'has_archive' => true,
		        'rewrite' => array('slug' => static::$strPostTypeSlug),
		        'show_in_rest' => true,
		        'menu_icon' => 'dashicons-tide',
            	'supports' => array(
                	'title',
                	'editor',
                	'thumbnail'
            	),
		    )
		);
	}

	/**
	* Create Taxonomies
	*
	*/
	function createTaxonomies(){

		$labels = array(
			'name'                       => _x('Menu Categories','Taxonomy General Name', 'text_domain'),
			'singular_name'              => _x('Menu Category', 'Taxonomy Singular Name', 'text_domain'),
			'menu_name'                  => __('Menu Categories', 'text_domain'),
			'all_items'                  => __('All Items', 'text_domain'),
			'parent_item'                => __('Parent Category', 'text_domain'),
			'parent_item_colon'          => __('Parent Category:', 'text_domain'),
			'new_item_name'              => __('New Category Name', 'text_domain'),
			'add_new_item'               => __('Add New Category', 'text_domain'),
			'edit_item'                  => __('Edit Category', 'text_domain'),
			'update_item'                => __('Update Category', 'text_domain'),
			'view_item'                  => __('View Category', 'text_domain'),
			'separate_items_with_commas' => __('Separate items with commas', 'text_domain'),
			'add_or_remove_items'        => __('Add or remove items', 'text_domain'),
			'choose_from_most_used'      => __('Choose from the most used', 'text_domain'),
			'popular_items'              => __('Popular Items', 'text_domain'),
			'search_items'               => __('Search Items', 'text_domain'),
			'not_found'                  => __('Not Found', 'text_domain'),
			'no_terms'                   => __('No items', 'text_domain'),
			'items_list'                 => __('Items list', 'text_domain'),
			'items_list_navigation'      => __('Items list navigation', 'text_domain'),
		);
		$args = array(
			'labels'                     => $labels,
			'hierarchical'               => true,
			'public'                     => true,
			'show_ui'                    => true,
			'show_admin_column'          => true,
			'show_in_nav_menus'          => true,
			'show_tagcloud'              => true,
			'show_in_rest' 				 => true,
		);
		register_taxonomy(static::$strMenuCategories,array('menu'),$args);
	}

	/**
	* Add Image
	*
	*/
	public static function add_term_image($taxonomy){?>
	    <div class="form-field term-group">
	        <input type="hidden" name="txt_upload_image" id="txt_upload_image" value="">
	        <input type="button" id="upload_image_btn" class="button" value="Upload an Image"/>
	        <p><img src="" id="img_upload_image"></p>
	    </div>
    <?php
	}

	/**
	* Save Image
	*
	*/
	public static function save_term_image($term_id) {
    	if(isset($_POST['txt_upload_image']) && '' !== $_POST['txt_upload_image']){
        	$group = $_POST['txt_upload_image'];
        	add_term_meta($term_id, 'term_image', $group, true);
    	}
	}

	/**
	* Update Image
	*
	*/
	public static function edit_image_upload($term, $taxonomy){
    	$txt_upload_image = get_term_meta($term->term_id, 'term_image', true);
		?>
    	<div class="form-field term-group edit-menu-image">
        	<input type="hidden" name="txt_upload_image" id="txt_upload_image" value="<?php echo $txt_upload_image ?>">
        	<input type="button" id="upload_image_btn" class="button" value="Upload an Image" />
        	<p><img id="img_upload_image" src="<?php echo $txt_upload_image ?>"></p>
    	</div>
	<?php
	}

	/**
	* Save Updated Image
	*
	*/
	public static function update_image_upload($term_id){
    	if(isset($_POST['txt_upload_image']) && '' !== $_POST['txt_upload_image']){
        	$group = $_POST['txt_upload_image'];
        	update_term_meta($term_id, 'term_image', $group);
    	}
	}

	/**
    * Define Columns
    *
    */
    public static function setColumns($arrColumns){

        // Presenter Role
        unset($arrColumns['date']);
        $arrColumns['item_price'] 		= __('Item Price', 'your_text_domain');
        $arrColumns['thumbnail'] 		= __('Image', 'your_text_domain');
        $arrColumns['alcohol_in_item'] 	= __('More', 'your_text_domain');
        $arrColumns['date'] 			= __('Created On', 'your_text_domain');
        return $arrColumns;
    }

    /**
    * Define Columns Content
    *
    */
    public static function setColumnsContent ($strColumn, $intPostId){
        // If Colmumn is Start Date
        if($strColumn == 'item_price'){
            $strMeta = get_post_meta($intPostId, 'item_price',true);
            echo  showPrice($strMeta,true);
        }
        
        if($strColumn == 'alcohol_in_item'){
            $bolMeta = get_post_meta($intPostId, 'alcohol_in_item',true);
            if($bolMeta){
            	echo '<span class="admin-html-icon" title="Alcohol in item">&#127866;</span>';
        	}

        	$bolMeta = get_post_meta($intPostId, 'item_disable',true);
            if($bolMeta){
            	echo '<span class="admin-html-icon" title="Disabled">&#x26D4;</span>';
        	}

        	$bolMeta = get_post_meta($intPostId, 'show_in_addon',true);
            if($bolMeta){
            	echo '<span class="admin-html-icon" title="Add on item">&#x26A1;</span>';
        	}

        	$bolMeta = get_post_meta($intPostId, 'item_has_spice',true);
            if($bolMeta){
            	echo '<span class="admin-html-icon spice" title="Item has spice levels">&#127798;</span>';
        	}
        }
        if($strColumn == 'thumbnail'){
        	echo '<div class="post-thumbnal-size">';
        	echo get_the_post_thumbnail($intPostId,'thumbnail');
        	echo '</div>';
        }
    }
    /**
	* Get Sub Menu String
	*
	*/
    public static function getSubMenuString($arrChildren){
    	foreach($arrChildren as $key => $objChildren) {
    		
    	}
    }

    public static function getChildren($intTermId){
    	if(empty($intTermId)){
    		return;
    	}

    	$arrReturn = array();
    	$arrChildren = get_terms(
			array(
				'taxonomy' => 'menu_category',
				'parent' => $intTermId,
				'hide_empty' => false
			)
		);
		$arrReturn = $arrChildren;
		return $arrReturn;
    }
}
new Menu_Controller;
?>