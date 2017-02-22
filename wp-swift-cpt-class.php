<?php
/*
Plugin Name: WP Swift: CPT Class
Plugin URI: 
Description: A custom post type for class
Version: 1.0.0
Author: Gary Swift
Author URI: https://github.com/GarySwift
Text Domain: wp-swift-cpt-class
Domain Path: 
*/

class WP_Swift_CPT_Class {
    /*
     * Initialize the plugin.
     */
    public function __construct() {
        add_action( 'init', array($this, 'register_cpt_class') );
        add_action( 'init', array($this, 'add_field_group') );
            	# A shortcode for rendering the login form.
    	add_shortcode( 'get-class-info', array( $this, 'render_class_info' ) ); 
  //       add_action( 'admin_menu', array($this, 'wp_swift_cpt_class_add_admin_menu') );
		// add_action( 'admin_init', array($this, 'wp_swift_cpt_class_settings_init') );
    }

    /**
     * A shortcode for rendering the login form.
     *
     * @param  array   $attributes  Shortcode attributes.
     * @param  string  $content     The text content for shortcode. Not used.
     *
     * @return string  The shortcode output
     */
    public function render_class_info( $attributes, $content = null ) {
        // Parse shortcode attributes
        $default_attributes = array( 'show_title' => false);//524 //, 'redirect' => '524' 
        $attributes = shortcode_atts( $default_attributes, $attributes );
        $show_title = $attributes['show_title'];

        $teacher='';
        // if( get_field('teacher') ) {
        //     echo get_field('teacher');
        // }

        ob_start();
     // echo "<pre>";var_dump($attributes);echo "</pre>";
     // echo "<pre>";var_dump($content);echo "</pre>";
        $id = get_the_id();
        //echo "<pre>id: ";var_dump($id);echo "</pre>";
        $image =  get_featured_image($post_id=false);
        // echo "<pre>";var_dump($image);echo "</pre>";
        ?>
        <?php if ($image): ?>
        	<div class="text-center"><img src="<?php echo $image["sizes"]["thumbnail"] ?>" alt=""></div>
        <?php endif ?>
        	<?php if ( get_field('comment') ) : ?>
        		<div class="entry-content"><?php echo get_field('comment'); ?></div>
        	<?php else: ?>
        		<?php the_excerpt(); ?>
        	<?php endif; ?>
			<?php if ( get_field('teacher') ) : ?>
				<h5 class="teacher"><span class="label">Teacher</span> <?php echo get_field('teacher'); ?></h5>
			<?php endif; ?>
			<?php if ( get_field('door') ) : ?>
				<h6><span class="label secondary">Door</span> <?php echo get_field('door'); ?></h6>
			<?php endif; ?>
        <?php 
     
        $html = ob_get_contents();
        ob_end_clean();
     
        return $html;
     
     //    if ( is_user_logged_in() ) {
     //        return __( 'You are already signed in.', 'personalize-login' );
     //    }
         
     //    // Error messages
     //    $errors = array();
     //    if ( isset( $_REQUEST['login'] ) ) {
     //        $error_codes = explode( ',', $_REQUEST['login'] );
         
     //        foreach ( $error_codes as $code ) {
     //            $errors []= $this->get_error_message( $code );
     //        }
     //    }
     //    $attributes['errors'] = $errors;   

    	// // Check if user just logged out
    	// $attributes['logged_out'] = isset( $_REQUEST['logged_out'] ) && $_REQUEST['logged_out'] == true;

     //    // Check if the user just registered
     //    $attributes['registered'] = isset( $_REQUEST['registered'] );

     //    // Check if the user just requested a new password 
     //    $attributes['lost_password_sent'] = isset( $_REQUEST['checkemail'] ) && $_REQUEST['checkemail'] == 'confirm';

     //    // Check if user just updated password
     //    $attributes['password_updated'] = isset( $_REQUEST['password'] ) && $_REQUEST['password'] == 'changed';

     //    /*
     //     * Pass the redirect parameter to the WordPress login functionality: by default,
     //     * don't specify a redirect, but if a valid redirect URL has been passed as request parameter, use it.
     //     */
     //    $attributes['redirect'] = '';//site_url();

     //    if ( isset( $_REQUEST['redirect_to'] ) ) {
     //        $attributes['redirect'] = wp_validate_redirect( $_REQUEST['redirect_to'], $attributes['redirect'] );
     //    }
        
        // Render the login form using an external template
        // return $this->get_template_html( 'login_form', $attributes, $this->form_builder );

    }

	function register_cpt_class() {

		$labels = array(
			'name' => __( 'Classes', 'class' ),
			'singular_name' => __( 'Class', 'class' ),
			'add_new' => __( 'Add New Class', 'class' ),
			'add_new_item' => __( 'Add New Class', 'class' ),
			'edit_item' => __( 'Edit Class', 'class' ),
			'new_item' => __( 'New Class', 'class' ),
			'view_item' => __( 'View Class', 'class' ),
			'search_items' => __( 'Search Classes', 'class' ),
			'not_found' => __( 'No classes found', 'class' ),
			'not_found_in_trash' => __( 'No classes found in Trash', 'class' ),
			'parent_item_colon' => __( 'Parent Class:', 'class' ),
			'menu_name' => __( 'Classes', 'class' ),
		);

		$args = array(
			'labels' => $labels,
			'hierarchical' => false,
			'supports' => array( 'title', 'editor', 'thumbnail' ),
			'public' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'menu_position' => 5,
			'menu_icon' => 'dashicons-welcome-learn-more',
			'show_in_nav_menus' => true,
			'publicly_queryable' => true,
			'exclude_from_search' => true,
			'has_archive' => false,
			'query_var' => true,
			'can_export' => true,
			'rewrite' => true,
			'capability_type' => 'post'
		);

		register_post_type( 'class', $args );
	}

	function wp_swift_cpt_class_add_admin_menu(  ) { 

		add_submenu_page( 'tools.php', 'WP Swift: CPT Class', 'WP Swift: CPT Class', 'manage_options', 'wp_swift:_cpt_class', 'wp_swift_cpt_class_options_page' );

	}


	function wp_swift_cpt_class_settings_init(  ) { 

		register_setting( 'pluginPage', 'wp_swift_cpt_class_settings' );

		add_settings_section(
			'wp_swift_cpt_class_pluginPage_section', 
			__( 'Your section description', 'wp-swift-cpt-class' ), 
			'wp_swift_cpt_class_settings_section_callback', 
			'pluginPage'
		);

		add_settings_field( 
			'wp_swift_cpt_class_checkbox_field_0', 
			__( 'Settings field description', 'wp-swift-cpt-class' ), 
			'wp_swift_cpt_class_checkbox_field_0_render', 
			'pluginPage', 
			'wp_swift_cpt_class_pluginPage_section' 
		);


	}


	function wp_swift_cpt_class_checkbox_field_0_render(  ) { 

		$options = get_option( 'wp_swift_cpt_class_settings' );
		?>
		<input type='checkbox' name='wp_swift_cpt_class_settings[wp_swift_cpt_class_checkbox_field_0]' <?php checked( $options['wp_swift_cpt_class_checkbox_field_0'], 1 ); ?> value='1'>
		<?php

	}


	function wp_swift_cpt_class_settings_section_callback(  ) { 

		echo __( 'This section description', 'wp-swift-cpt-class' );

	}


	function wp_swift_cpt_class_options_page(  ) { 

		?>
		<form action='options.php' method='post'>

			<h2>WP Swift: CPT Class</h2>

			<?php
			settings_fields( 'pluginPage' );
			do_settings_sections( 'pluginPage' );
			submit_button();
			?>

		</form>
		<?php

	}
	public function add_field_group() {
		if( function_exists('acf_add_local_field_group') ):

		acf_add_local_field_group(array (
			'key' => 'group_58adab41baf9f',
			'title' => 'Class Additional Fields',
			'fields' => array (
				array (
					'key' => 'field_58adab51ae3a6',
					'label' => 'Teacher',
					'name' => 'teacher',
					'type' => 'text',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array (
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
				array (
					'key' => 'field_58adab5dae3a7',
					'label' => 'Door',
					'name' => 'door',
					'type' => 'text',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array (
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
				array (
					'key' => 'field_58adb4cc1e10d',
					'label' => 'Comment',
					'name' => 'comment',
					'type' => 'textarea',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array (
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'maxlength' => 140,
					'rows' => 6,
					'new_lines' => '',
				),
			),
			'location' => array (
				array (
					array (
						'param' => 'post_type',
						'operator' => '==',
						'value' => 'class',
					),
				),
			),
			'menu_order' => 0,
			'position' => 'normal',
			'style' => 'default',
			'label_placement' => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen' => '',
			'active' => 1,
			'description' => '',
		));

		endif;		
	}	
}

/*
 * Get the featured image of post and return as ACF image object
 *
 * @param - $post, $sizes
 * 
 * @return - $image (array)
 */
function get_featured_image($post_id=false) {
	// global $post;
	// echo '1 id:'.$post_id.'<br>';
	if(!$post_id) {
		global $post;
		$post_thumbnail_id = get_post_thumbnail_id( $post );
		// echo '>>>global<br>';
	}
	else {
		$post=get_post($post_id);
		$post_thumbnail_id = $post->ID;
		// echo '>>>from id<br>';
	}
	// echo '2 id:'.$post->ID.'<br><br>';
	$sizes = array('medium_large', 'medium_crop', 'fp-small', 'fp-medium', 'fp-large', 'letterbox');// 'letterbox-medium', 
	
	$image=false;
	if ( has_post_thumbnail() ) :
 		$image = array(); 
		$post_thumbnail_id = get_post_thumbnail_id( $post );
		$thumb = get_post( $post_thumbnail_id );
	    $image['title'] = $thumb->post_title;
	    // echo $image['title'].'<br>';
	    // echo 'thumbid '.$thumb->ID.'<br>';
	    $image['alt'] = get_post_meta( $thumb->ID, '_wp_attachment_image_alt', true ); //alt text
	    $image['caption'] = $thumb->post_excerpt;
	    $image['description'] = $thumb->post_content;
    	$thumb_url_array = wp_get_attachment_image_src($post_thumbnail_id, 'thumbnail', true);
    	$image['sizes']['thumbnail'] = $thumb_url_array[0]; 
    	$medium_url_array = wp_get_attachment_image_src($post_thumbnail_id, 'medium', true);
    	$image['sizes']['medium'] = $medium_url_array[0]; 
    	$large_url_array = wp_get_attachment_image_src($post_thumbnail_id, 'large', true);
    	$image['sizes']['large'] = $large_url_array[0];
    	   	$large_width=$large_url_array[1]; 
    	$large_height=$large_url_array[2]; 
    	if($large_height>$large_width) {
    		$image['orientation']='portrait'; 
    	}
    	else {
    		$image['orientation']='landscape'; 
    	}
    	if($large_height>1000) {
    		$image['fullsize']=' fullsize'; 
    	}
    	else {
    		$image['fullsize']=''; 
    	} 
    	$image['url'] = $thumb->guid;
    	foreach ($sizes as $size) {
			$large_url_array = wp_get_attachment_image_src($post_thumbnail_id, $size, true);
			$image['sizes'][$size] = $large_url_array[0]; 
    	}
	endif;
	return $image;
}

/*
 	Example Usage of get_featured_image()

	$sizes = array('letterbox', 'medium_large', 'thumbnail_large');
	$image =  get_featured_image($post, $sizes); 
	<img class=""  data-interchange="[<?php echo $image['sizes']['thumbnail_large']; ?>, small], [<?php echo $image['sizes']['medium_large']; ?>, medium], [<?php echo $image['sizes']['letterbox']; ?>, large]" alt="<?php echo ($image['alt'] ? $image['alt']  : 'Image'); ?>" title="<?php echo ($image['title'] ? $image['title']  : 'defaultImgTitle' ); ?>">
*/


function the_image($single_post=true, $display_size='large', $image_class='thumbnail') {
	global $post;

	// $sizes = array('medium_large', 'fp-small', 'fp-medium', 'fp-large', 'icon', 'letterbox', 'letterbox-medium' );

	// if ( !$single_post && ( (get_post_format( $post_id ) != 'gallery') && (get_post_format( $post_id ) != 'video') ) ):
		if( !$single_post && get_field('letterbox_image')) {
	        $image = get_field('letterbox_image');
	    	$image_small = $image['sizes']['medium_large'];
	        $image_large = $image['url'];
	        $image_link = $image['original_image']['sizes']['large'];		
		}
		else {
			$image =  get_featured_image(); //$post->ID
			if($image) {
				$image_small = $image['sizes']['medium_large'];
				$image_large = $image['sizes'][$display_size];
				$image_link = $image['sizes']['large'];	
			}	
		}

		if($image): 
			?>
			<div class="text-center">
				<a href="<?php echo $image_link ?>" class="image-popup-vertical-fit" title="<?php the_title() ?><?php echo ($image['caption'] ? ' &vert; '.$image['caption']  : '' ) ?>">
					<img class="<?php echo $image_class ?>"  data-interchange="[<?php echo $image_small ?>, small], [<?php echo $image_large; ?>, medium], [<?php echo $image_large; ?>, large]" alt="<?php echo ($image['alt'] ? $image['alt']  : 'Image'); ?>" title="<?php echo ($image['title'] ? $image['title']  : 'defaultImgTitle' ); ?>">
				</a>
			</div>
			<?php 
		endif;
	// endif; 
}




// Initialize the plugin
$wp_swift_cpt_class = new WP_Swift_CPT_Class();


