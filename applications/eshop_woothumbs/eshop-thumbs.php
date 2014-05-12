<?php
/*
Plugin Name: Eshop Thumbs
Description: Display multiple images for each variation of a product.
Author: Boxbeat Technologies Pvt Ltd
Version: 1.0
Author URI: http://theboxbeat.com/
*/

class eshop_woothumbs
{
	private $eshop_woothumbs_options = array(
		'thumb_container' => '.thumbnails',
		'transition_speed' => '150',
		'callback' => '$("a.zoom").prettyPhoto({social_tools:!1,theme:"pp_eshopbox",horizontal_padding:40,opacity:.9});'
	);
  
	function frontend_scripts() {
		/*wp_enqueue_script('eshop_woothumbs', plugins_url('eshop_woothumbs/js/scripts.js'), array('jquery'), '2.0.1', true);
		
		$settings = get_option( 'eshop_woothumbs_options', $this->eshop_woothumbs_options );
		
		$vars = array( 
			'template_url' => get_bloginfo('template_url'), 
			'abspath' => ABSPATH, 
			'url' => get_bloginfo('url'),
			'thumbnails_container' => $settings['thumb_container'],
			'transition' => $settings['transition_speed'],
			'plugin_url' => plugins_url('eshop_woothumbs'),
			'plugin_path' => WP_PLUGIN_DIR.'/eshop_woothumbs'
		);
		wp_localize_script( 'eshop_woothumbs', 'vars', $vars );*/
	}
	
	function admin_scripts() {
		global $post, $pagenow;
		if($post) {
			if(get_post_type( $post->ID ) == "product" && ($pagenow == "post.php" || $pagenow == "post-new.php")) {	
				wp_enqueue_script('eshop_woothumbs', plugins_url('eshop_woothumbs/js/admin-scripts.js'), array('jquery'), '2.0.1', true);
				wp_enqueue_style( 'eshop_woothumbs_admin_css', plugins_url('eshop_woothumbs/css/admin-styles.css'), false, '2.0.1' );
				
				$vars = array(
					'abspath' => ABSPATH,
					'plugin_url' => plugins_url('eshop_woothumbs'),
				);
				wp_localize_script( 'eshop_woothumbs', 'vars', $vars );
			}
		}
	}
	
	function callback() {
            if(is_admin()){
		$settings = get_option( 'eshop_woothumbs_options', $this->eshop_woothumbs_options );
	
		$callback = $settings['callback'];
		
	    echo "
	    <script type='text/javascript'>
		    jQuery(document).ready(function($) {
			    $('form.variations_form').on( 'eshop_woothumbs_callback', function( event ) { " . stripslashes($callback) . " });
			});
	    </script>
	    ";
            }
	}
	
	function save_images($post_id){
	
		if(isset($_POST['variation_image_gallery'])) {
			foreach($_POST['variation_image_gallery'] as $varID => $variation_image_gallery) {
				update_post_meta($varID, 'variation_image_gallery', $variation_image_gallery);	
			}
		}
		
	}
	
/* =============================
   Settimgs Page 
   ============================= */
   
	function eshop_woothumbs_register_settings() {
	    register_setting( 'eshop_woothumbs_settings', 'eshop_woothumbs_options', array( &$this, 'eshop_woothumbs_validate_options' ) );
	}
	
	function eshop_woothumbs_options() {
	    add_submenu_page( 'eshopbox', 'WooThumbs', 'WooThumbs', 'activate_plugins', 'eshop_woothumbs', array( &$this, 'eshop_woothumbs_options_page') );
	}
	
	function eshop_woothumbs_options_page() {
	 
	    if ( ! isset( $_REQUEST['updated'] ) )
	    $_REQUEST['updated'] = false; // This checks whether the form has just been submitted. ?>
	 
	    <div class="wrap">
	 
	    <div id="icon-options-general" class="icon32"><br></div><?php echo "<h2>" . __( ' WooThumbs Options' ) . "</h2>";
	    // This shows the page's name and an icon if one has been provided ?>
	 
	    <?php if ( false !== $_REQUEST['updated'] ) : ?>
	    <div><p><strong><?php _e( 'Options saved' ); ?></strong></p></div>
	    <?php endif; // If the form has just been submitted, this shows the notification ?>
	 
	    <form method="post" action="options.php">
	 
	    <?php $settings = get_option( 'eshop_woothumbs_options', $this->eshop_woothumbs_options ); ?>
	 
	    <?php settings_fields( 'eshop_woothumbs_settings' );
	    /* This function outputs some hidden fields required by the form,
	    including a nonce, a unique number used to ensure the form has been submitted from the admin page
	    and not somewhere else, very important for security */ ?>
	 
	    <table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row">
						<label for="blogname"><strong><?php _e('Thumbnail Container','eshop_woothumbs'); ?></strong></label>
					</th>
					<td>
						<input id="thumb_container" name="eshop_woothumbs_options[thumb_container]" type="text" value="<?php echo esc_attr_e($settings['thumb_container']); ?>" class="regular-text">
						<p class="description"><?php _e('Enter the class or ID of your thumbnail container. By default this is ".thumbnails".','eshop_woothumbs'); ?></p>
						</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="blogname"><strong><?php _e('Transition Speed','eshop_woothumbs'); ?></strong></label>
					</th>
					<td>
						<input id="transition_speed" name="eshop_woothumbs_options[transition_speed]" type="text" value="<?php echo esc_attr_e($settings['transition_speed']); ?>" class="regular-text">
						<p class="description"><?php _e('Enter a transition speed in milliseconds for which the new thumbnails are displayed.','eshop_woothumbs'); ?></p>
						</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="eshop_woothumbs_options[callback]"><strong><?php _e('Callback','eshop_woothumbs'); ?></strong></label>
					</th>
					<td>
						<textarea id="callback" name="eshop_woothumbs_options[callback]" rows="10" cols="50" class="large-text code"><?php echo stripslashes($settings['callback']); ?></textarea>	    
						<p class="description"><?php _e('If your theme displays the thumbnails and main image differently to the default method (a lightbox), then you may need to trigger a custom javascript callback for once the new images have loaded. Enter it above.','eshop_woothumbs'); ?></p>
					</td>
				</tr>
			</tbody>
		</table>
	 
	    <p class="submit"><input type="submit" name="submit" id="submit" class="button-primary" value="Save Changes"></p>
	 
	    </form>
	    
	    <form method="post" action="">
	    
		    <table class="form-table">
				<tbody>
					<tr valign="top">
						<th scope="row">
							<label for="blogname"><strong><?php _e('Transfer Images','eshop_woothumbs'); ?></strong></label>
						</th>
						<td>							
							<?php 
		    
						    if(isset($_POST['transfer'])) {
						    
							    $args = array(
									'post_type' => 'product_variation',
									'posts_per_page' => -1,
									'post_parent' => $prod_id
								);
								$variations = new WP_Query( $args ); 
				
								if($variations->have_posts()) {
									
									while ( $variations->have_posts() ) : $variations->the_post(); ?>
										
										<?php 
										$featuredId = get_post_thumbnail_id();
										$args = array(
											'post_type' => 'attachment',
											'post_mime_type' => 'image',
											'output' => 'ARRAY_N',
											'orderby' => 'menu_order',
											'order' => 'ASC',
											'post_parent' => get_the_id(),
											'post__not_in' => array($featuredId)
										);
										$attachments = get_children( $args );
										
										$variation_image_gallery = get_post_meta(get_the_id(), 'variation_image_gallery', true);
										$imgIds = array_filter(explode(',', $variation_image_gallery));
										
										if(!empty($attachments)) {
										
											foreach($attachments as $id => $attachdata) {
												
												if(!in_array($id, $imgIds)) {
													$imgIds[] = $id;
												}
											
											}
											
											$update = update_post_meta(get_the_id(), 'variation_image_gallery', implode(',', $imgIds));
											
											echo'<div class="updated below-h2"><p>';
											if($update) {
												_e('Variation #'.$id.' images transferred','eshop_woothumbs');
											} else {
												_e('Variation #'.get_the_id().' has no further images to transfer','eshop_woothumbs');
											}
											echo '</p></div>';
										}
										
									endwhile;
								
								} /*End if(posts) */ wp_reset_postdata();
							
							} ?>
							
							<input type="submit" name="transfer" id="transfer" class="button-secondary" value="Transfer Images">
							<p class="description"><?php _e('If you have just upgraded from WooThumbs v1.* to v2.*, it may be worth transferring any existing image attachments to the new method.','eshop_woothumbs'); ?></p>
							
						</td>
					</tr>
				</tbody>
			</table>
		    
		
	    </form>
	 
	    </div>
	 
	    <?php
	}
	
function eshop_woothumbs_validate_options( $input ) {
    $eshop_woothumbs_options = $this->eshop_woothumbs_options;
 
    $settings = get_option( 'eshop_woothumbs_options', $eshop_woothumbs_options );
 
    // We strip all tags from the text field, to avoid vulnerablilties like XSS
    $input['thumb_container'] = wp_filter_nohtml_kses( $input['thumb_container'] );
    
    // We strip all tags from the text field, to avoid vulnerablilties like XSS
    $input['transition_speed'] = wp_filter_nohtml_kses( $input['transition_speed'] );
 
    // We strip all tags from the text field, to avoid vulnerablilties like XSS
    $input['callback'] = wp_filter_post_kses( $input['callback'] );
 
    return $input;
}
  
/* =============================
   PHP 4 Compatible Constructor. 
   ============================= */
	function eshop_woothumbs() {
		$this->__construct();
	}
/* =============================
   PHP 5 Constructor. 
   ============================= */
	function __construct() {
		add_action('wp_enqueue_scripts', array(
			  &$this,
			  'frontend_scripts'
		),50); 
		
		add_action( 'admin_enqueue_scripts', array(
			  &$this,
			  'admin_scripts'
		)); 
		
		add_action( 'eshopbox_process_product_meta', array(
			&$this,
			'save_images'
		));
		 
		add_action('wp_head', array(
			  &$this,
			  'callback'
		));
		// Activate Settings
		add_action( 'admin_init', array(
			  &$this,
			  'eshop_woothumbs_register_settings'
		));
		add_action( 'admin_menu', array(
			  &$this,
			  'eshop_woothumbs_options'
		));
	}
  
} // End eshop_woothumbs Class
$eshop_woothumbs = new eshop_woothumbs; // Start an instance of the plugin class
