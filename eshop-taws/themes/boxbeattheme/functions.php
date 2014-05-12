<?php
/**
 * Twenty Twelve functions and definitions.
 *
 * Sets up the theme and provides some helper functions, which are used
 * in the theme as custom template tags. Others are attached to action and
 * filter hooks in BoxBeat to change core functionality.
 *
 * When using a child theme (see http://codex.boxbeat.org/Theme_Development and
 * http://codex.boxbeat.org/Child_Themes), you can override certain functions
 * (those wrapped in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before the parent
 * theme's file, so the child theme functions would be used.
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are instead attached
 * to a filter or action hook.
 *
 * For more information on hooks, actions, and filters, see http://codex.boxbeat.org/Plugin_API.
 *
 * @package BoxBeat
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

/**
 * Sets up the content width value based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) )
	$content_width = 625;

/**
 * Sets up theme defaults and registers the various BoxBeat features that
 * Twenty Twelve supports.
 *
 * @uses load_theme_textdomain() For translation/localization support.
 * @uses add_editor_style() To add a Visual Editor stylesheet.
 * @uses add_theme_support() To add support for post thumbnails, automatic feed links,
 * 	custom background, and post formats.
 * @uses register_nav_menu() To add support for navigation menus.
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
 *
 * @since Twenty Twelve 1.0
 */
function twentytwelve_setup() {
	/*
	 * Makes Twenty Twelve available for translation.
	 *
	 * Translations can be added to the /languages/ directory.
	 * If you're building a theme based on Twenty Twelve, use a find and replace
	 * to change 'twentytwelve' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'twentytwelve', get_template_directory() . '/languages' );

	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();

	// Adds RSS feed links to <head> for posts and comments.
	add_theme_support( 'automatic-feed-links' );

	// This theme supports a variety of post formats.
	add_theme_support( 'post-formats', array( 'aside', 'image', 'link', 'quote', 'status' ) );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menu( 'primary', __( 'Primary Menu', 'twentytwelve' ) );

	/*
	 * This theme supports custom background color and image, and here
	 * we also set up the default background color.
	 */
	add_theme_support( 'custom-background', array(
		'default-color' => 'e6e6e6',
	) );

	// This theme uses a custom image size for featured images, displayed on "standard" posts.
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 624, 9999 ); // Unlimited height, soft crop
}
add_action( 'after_setup_theme', 'twentytwelve_setup' );

/**
 * Adds support for a custom header image.
 */
require( get_template_directory() . '/inc/custom-header.php' );

/**
 * Enqueues scripts and styles for front-end.
 *
 * @since Twenty Twelve 1.0
 */
function twentytwelve_scripts_styles() {
	global $wp_styles;

	/*
	 * Adds JavaScript to pages with the comment form to support
	 * sites with threaded comments (when in use).
	 */
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	/*
	 * Adds JavaScript for handling the navigation menu hide-and-show behavior.
	 */
	wp_enqueue_script( 'twentytwelve-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '1.0', true );

	/*
	 * Loads our special font CSS file.
	 *
	 * The use of Open Sans by default is localized. For languages that use
	 * characters not supported by the font, the font can be disabled.
	 *
	 * To disable in a child theme, use wp_dequeue_style()
	 * function mytheme_dequeue_fonts() {
	 *     wp_dequeue_style( 'twentytwelve-fonts' );
	 * }
	 * add_action( 'wp_enqueue_scripts', 'mytheme_dequeue_fonts', 11 );
	 */

	/* translators: If there are characters in your language that are not supported
	   by Open Sans, translate this to 'off'. Do not translate into your own language. */
	if ( 'off' !== _x( 'on', 'Open Sans font: on or off', 'twentytwelve' ) ) {
		$subsets = 'latin,latin-ext';

		/* translators: To add an additional Open Sans character subset specific to your language, translate
		   this to 'greek', 'cyrillic' or 'vietnamese'. Do not translate into your own language. */
		$subset = _x( 'no-subset', 'Open Sans font: add new subset (greek, cyrillic, vietnamese)', 'twentytwelve' );

		if ( 'cyrillic' == $subset )
			$subsets .= ',cyrillic,cyrillic-ext';
		elseif ( 'greek' == $subset )
			$subsets .= ',greek,greek-ext';
		elseif ( 'vietnamese' == $subset )
			$subsets .= ',vietnamese';

		$protocol = is_ssl() ? 'https' : 'http';
		$query_args = array(
			'family' => 'Open+Sans:400italic,700italic,400,700',
			'subset' => $subsets,
		);
		wp_enqueue_style( 'twentytwelve-fonts', add_query_arg( $query_args, "$protocol://fonts.googleapis.com/css" ), array(), null );
	}

	/*
	 * Loads our main stylesheet.
	 */
	wp_enqueue_style( 'twentytwelve-style', get_stylesheet_uri() );

	/*
	 * Loads the Internet Explorer specific stylesheet.
	 */
	wp_enqueue_style( 'twentytwelve-ie', get_template_directory_uri() . '/css/ie.css', array( 'twentytwelve-style' ), '20121010' );
	$wp_styles->add_data( 'twentytwelve-ie', 'conditional', 'lt IE 9' );
}
add_action( 'wp_enqueue_scripts', 'twentytwelve_scripts_styles' );

/**
 * Creates a nicely formatted and more specific title element text
 * for output in head of document, based on current view.
 *
 * @since Twenty Twelve 1.0
 *
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 * @return string Filtered title.
 */
function twentytwelve_wp_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() )
		return $title;

	// Add the site name.
	$title .= get_bloginfo( 'name' );

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title = "$title $sep $site_description";

	// Add a page number if necessary.
	if ( $paged >= 2 || $page >= 2 )
		$title = "$title $sep " . sprintf( __( 'Page %s', 'twentytwelve' ), max( $paged, $page ) );

	return $title;
}
add_filter( 'wp_title', 'twentytwelve_wp_title', 10, 2 );

/**
 * Makes our wp_nav_menu() fallback -- wp_page_menu() -- show a home link.
 *
 * @since Twenty Twelve 1.0
 */
function twentytwelve_page_menu_args( $args ) {
	if ( ! isset( $args['show_home'] ) )
		$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'twentytwelve_page_menu_args' );

/**
 * Registers our main widget area and the front page widget areas.
 *
 * @since Twenty Twelve 1.0
 */
function twentytwelve_widgets_init() {
	register_sidebar( array(
		'name' => __( 'Main Sidebar', 'twentytwelve' ),
		'id' => 'sidebar-1',
		'description' => __( 'Appears on posts and pages except the optional Front Page template, which has its own widgets', 'twentytwelve' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'First Front Page Widget Area', 'twentytwelve' ),
		'id' => 'sidebar-2',
		'description' => __( 'Appears when using the optional Front Page template with a page set as Static Front Page', 'twentytwelve' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'Second Front Page Widget Area', 'twentytwelve' ),
		'id' => 'sidebar-3',
		'description' => __( 'Appears when using the optional Front Page template with a page set as Static Front Page', 'twentytwelve' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	
	register_sidebar( array(
		'name' => __( 'Color Filter Widget Area', 'twentytwelve' ),
		'id' => 'colorfilterwidget',
		'description' => __( 'Appears when using the optional Front Page template with a page set as Static Front Page', 'twentytwelve' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
        register_sidebar( array(
		'name' => __( 'Minicart', 'twentytwelve' ),
		'id' => 'minicart',
		'description' => __( 'Appears on posts and pages which displays minicart', 'twentytwelve' ), 
		'before_widget' => ' ',
		'after_widget' => '',
		'before_title' => '',
		'after_title' => '',
	) );
        register_sidebar( array(
		'name' => __( 'Subscriber', 'twentytwelve' ),
		'id' => 'subscriber',
		'description' => __( 'Appears on posts and pages which displays minicart', 'twentytwelve' ), 
		'before_widget' => ' ',
		'after_widget' => '',
		'before_title' => '',
		'after_title' => '',
	) );
        register_sidebar( array(
		'name' => __( 'Brand', 'twentytwelve' ),
		'id' => 'brand',
		'description' => __( 'Appears on posts and pages which displays minicart', 'twentytwelve' ), 
		'before_widget' => ' ',
		'after_widget' => '',
		'before_title' => '',
		'after_title' => '',
	) );
        register_sidebar( array(
		'name' => __( 'LookBook', 'twentytwelve' ),
		'id' => 'lookbook',
		'description' => __( 'Appears on posts and pages which displays minicart', 'twentytwelve' ), 
		'before_widget' => ' ',
		'after_widget' => '',
		'before_title' => '',
		'after_title' => '',
	) );
}
add_action( 'widgets_init', 'twentytwelve_widgets_init' );

if ( ! function_exists( 'twentytwelve_content_nav' ) ) :
/**
 * Displays navigation to next/previous pages when applicable.
 *
 * @since Twenty Twelve 1.0
 */
function twentytwelve_content_nav( $html_id ) {
	global $wp_query;

	$html_id = esc_attr( $html_id );

	if ( $wp_query->max_num_pages > 1 ) : ?>
		<nav id="<?php echo $html_id; ?>" class="navigation" role="navigation">
			<h3 class="assistive-text"><?php _e( 'Post navigation', 'twentytwelve' ); ?></h3>
			<div class="nav-previous alignleft"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'twentytwelve' ) ); ?></div>
			<div class="nav-next alignright"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'twentytwelve' ) ); ?></div>
		</nav><!-- #<?php echo $html_id; ?> .navigation -->
	<?php endif;
}
endif;

if ( ! function_exists( 'twentytwelve_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own twentytwelve_comment(), and that function will be used instead.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since Twenty Twelve 1.0
 */
function twentytwelve_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
		// Display trackbacks differently than normal comments.
	?>
	<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
		<p><?php _e( 'Pingback:', 'twentytwelve' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( '(Edit)', 'twentytwelve' ), '<span class="edit-link">', '</span>' ); ?></p>
	<?php
			break;
		default :
		// Proceed with normal comments.
		global $post;
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<header class="comment-meta comment-author vcard">
				<?php
					echo get_avatar( $comment, 44 );
					printf( '<cite class="fn">%1$s %2$s</cite>',
						get_comment_author_link(),
						// If current post author is also comment author, make it known visually.
						( $comment->user_id === $post->post_author ) ? '<span> ' . __( 'Post author', 'twentytwelve' ) . '</span>' : ''
					);
					printf( '<a href="%1$s"><time datetime="%2$s">%3$s</time></a>',
						esc_url( get_comment_link( $comment->comment_ID ) ),
						get_comment_time( 'c' ),
						/* translators: 1: date, 2: time */
						sprintf( __( '%1$s at %2$s', 'twentytwelve' ), get_comment_date(), get_comment_time() )
					);
				?>
			</header><!-- .comment-meta -->

			<?php if ( '0' == $comment->comment_approved ) : ?>
				<p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'twentytwelve' ); ?></p>
			<?php endif; ?>

			<section class="comment-content comment">
				<?php comment_text(); ?>
				<?php edit_comment_link( __( 'Edit', 'twentytwelve' ), '<p class="edit-link">', '</p>' ); ?>
			</section><!-- .comment-content -->

			<div class="reply">
				<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply', 'twentytwelve' ), 'after' => ' <span>&darr;</span>', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</div><!-- .reply -->
		</article><!-- #comment-## -->
	<?php
		break;
	endswitch; // end comment_type check
}
endif;

if ( ! function_exists( 'twentytwelve_entry_meta' ) ) :
/**
 * Prints HTML with meta information for current post: categories, tags, permalink, author, and date.
 *
 * Create your own twentytwelve_entry_meta() to override in a child theme.
 *
 * @since Twenty Twelve 1.0
 */
function twentytwelve_entry_meta() {
	// Translators: used between list items, there is a space after the comma.
	$categories_list = get_the_category_list( __( ', ', 'twentytwelve' ) );

	// Translators: used between list items, there is a space after the comma.
	$tag_list = get_the_tag_list( '', __( ', ', 'twentytwelve' ) );

	$date = sprintf( '<a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a>',
		esc_url( get_permalink() ),
		esc_attr( get_the_time() ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() )
	);

	$author = sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span>',
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_attr( sprintf( __( 'View all posts by %s', 'twentytwelve' ), get_the_author() ) ),
		get_the_author()
	);

	// Translators: 1 is category, 2 is tag, 3 is the date and 4 is the author's name.
	if ( $tag_list ) {
		$utility_text = __( 'This entry was posted in %1$s and tagged %2$s on %3$s<span class="by-author"> by %4$s</span>.', 'twentytwelve' );
	} elseif ( $categories_list ) {
		$utility_text = __( 'This entry was posted in %1$s on %3$s<span class="by-author"> by %4$s</span>.', 'twentytwelve' );
	} else {
		$utility_text = __( 'This entry was posted on %3$s<span class="by-author"> by %4$s</span>.', 'twentytwelve' );
	}

	printf(
		$utility_text,
		$categories_list,
		$tag_list,
		$date,
		$author
	);
}
endif;

/**
 * Extends the default BoxBeat body class to denote:
 * 1. Using a full-width layout, when no active widgets in the sidebar
 *    or full-width template.
 * 2. Front Page template: thumbnail in use and number of sidebars for
 *    widget areas.
 * 3. White or empty background color to change the layout and spacing.
 * 4. Custom fonts enabled.
 * 5. Single or multiple authors.
 *
 * @since Twenty Twelve 1.0
 *
 * @param array Existing class values.
 * @return array Filtered class values.
 */
function twentytwelve_body_class( $classes ) {
	$background_color = get_background_color();

	if ( ! is_active_sidebar( 'sidebar-1' ) || is_page_template( 'page-templates/full-width.php' ) )
		$classes[] = 'full-width';

	if ( is_page_template( 'page-templates/front-page.php' ) ) {
		$classes[] = 'template-front-page';
		if ( has_post_thumbnail() )
			$classes[] = 'has-post-thumbnail';
		if ( is_active_sidebar( 'sidebar-2' ) && is_active_sidebar( 'sidebar-3' ) )
			$classes[] = 'two-sidebars';
	}

	if ( empty( $background_color ) )
		$classes[] = 'custom-background-empty';
	elseif ( in_array( $background_color, array( 'fff', 'ffffff' ) ) )
		$classes[] = 'custom-background-white';

	// Enable custom font class only if the font CSS is queued to load.
	if ( wp_style_is( 'twentytwelve-fonts', 'queue' ) )
		$classes[] = 'custom-font-enabled';

	if ( ! is_multi_author() )
		$classes[] = 'single-author';

	return $classes;
}
add_filter( 'body_class', 'twentytwelve_body_class' );

/**
 * Adjusts content_width value for full-width and single image attachment
 * templates, and when there are no active widgets in the sidebar.
 *
 * @since Twenty Twelve 1.0
 */
function twentytwelve_content_width() {
	if ( is_page_template( 'page-templates/full-width.php' ) || is_attachment() || ! is_active_sidebar( 'sidebar-1' ) ) {
		global $content_width;
		$content_width = 960;
	}
}
add_action( 'template_redirect', 'twentytwelve_content_width' );

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @since Twenty Twelve 1.0
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 * @return void
 */
function twentytwelve_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';
}
add_action( 'customize_register', 'twentytwelve_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 *
 * @since Twenty Twelve 1.0
 */
function twentytwelve_customize_preview_js() {
	wp_enqueue_script( 'twentytwelve-customizer', get_template_directory_uri() . '/js/theme-customizer.js', array( 'customize-preview' ), '20120827', true );
}
add_action( 'customize_preview_init', 'twentytwelve_customize_preview_js' );

require( get_template_directory() . '/includes/enque.php' );

require( get_template_directory() . '/includes/widgets.php' );

require( get_template_directory() . '/includes/amit_code.php' );


remove_action( 'eshopbox_before_main_content', 'eshopbox_output_content_wrapper', 10);
remove_action( 'eshopbox_after_main_content', 'eshopbox_output_content_wrapper_end', 10);

add_action('eshopbox_before_main_content', 'my_theme_wrapper_start', 10);
add_action('eshopbox_after_main_content', 'my_theme_wrapper_end', 10);

add_theme_support( 'eshopbox' );


function my_theme_wrapper_start() {
  echo '<section id="main">';
}

function my_theme_wrapper_end() {
  echo '</section>';
}


//function for color swatch on product listing page
function eshop_get_swatch_images_list($product_id){
    global $product;
	$default_swatch = get_post_meta($product_id,'_default_attributes');//echo "<pre>";print_r($default_swatch);echo "</pre>";exit;
	$post_price = get_post_meta($product_id,'_price');
 	$default_swatch_c = $default_swatch[0]['pa_color'];
 	$get_term_by = get_term_by( 'slug', $default_swatch_c, 'pa_color');//echo "<pre>";print_r($get_term_by);
	$color_defaultid = $get_term_by->term_id;
	$post_name = $product->post->post_name;
	$post_title = $product->post->post_title;
	$tt_color = wp_get_post_terms($product_id,'pa_color');//echo "<pre>";print_r($tt_color);
	$cc= array();
	$img_arr = array();
	if($tt_color != NULL){
		$k = 0;
		$img_arr = array();
		$color_name =array_values(eshopbox_get_product_terms( $product_id, 'pa_color'));  		      
		$i = 0;	
		$attachmentss = get_posts( array(
				'post_type' 	=> 'product_variation',
				'post_parent' 	=> $product_id,
				'orderby'	=> 'menu_order',
				'order' 	=> 'ASC',
				'posts_per_page'  => 50,
				'numberposts'     => 50,
			) ); 
		$color=array();
		$k=0;
		foreach($color_name as $keycolor=>$colorname){
			$color = array();
			$variant_size = array();
			$termids = $colorname->term_id;//echo "";
			$termids1[] = $colorname->term_id;
			$color_title[] = $colorname->slug;
			$thumbnailid=getpostmetadetail($colorname->slug,$attachmentss); //echo $colorname->slug."====<pre>";print_r($thumbnailid);
			$c[] = get_eshopbox_term_meta($termids,'pa_color_swatches_id_color');//echo "<pre>";print_r($c);
			$imagepath = wp_get_attachment_image_src($thumbnailid[0]['meta_value'],'shop_catalog');
			//$imagepath = wp_get_attachment_image_src($thumbnailid[0]['meta_value'],'shop_catalog');			

			foreach($attachmentss as $key=> $att){
				foreach($thumbnailid as $key =>$value){
					if($value['color_post_id']==$att->ID){
						$postmetadetail=get_post_meta($att->ID);//echo "<pre>";print_r($postmetadetail);
						$variation_stock = $postmetadetail['_stock'][0];
						if($variation_stock > 0){
							$variant_size[] = $postmetadetail['attribute_pa_size'][0];//echo "<br/>";
						}
						$imagearray=wp_get_attachment_image_src($value[0]);//echo "<pre>jivi";print_r($imagearray);echo "</pre>";
						$allimagearray[]=$imagearray[0];
						if(!in_array($att->ID,$color) && count($color)==0){
							$allvar[]=$att->ID;
							$color[0]=$att->ID;							
						}
					}
				}
			}
			$classselected='';			
			$allvar=@array_values(array_unique($allimagearray));			
			$imageall=@array_unique($allimagearray);
			$uniqueimage = @array_values($imageall);
			$img_new[] = $imagepath[0].'==='.$product_id."@@@";
			$array_rev = array_reverse(explode(',',$_GET['filter_colour']));
			$filter_id = $array_rev[0];
			if(($termids1[$keycolor] == $color_defaultid) && $_GET['filter_colour'] == ''){
			$classselected = "class=selected_swatch";
			}
			elseif($filter_id == $termids1[$keycolor]){ 			        		
				$classselected = "class=selected_swatch"; 
				}else{ $classselected='';}			
if(count($color_name) > 1){
?>
<div class="select-option swatch-wrapper product_<?php echo $product_id;?>"
	data-value="<?php echo $key; ?>" style="float:none;" 
	rel="<?php echo $img_new[$keycolor]; ?>***<?php echo $product_id; ?>***<?php echo $product_id; ?>***<?php echo $post_name; ?>*=*<?php echo $color[0]; ?>====<?php echo $post_title; ?>==<?php echo $post_price[0]; ?>^^<?php echo implode(', ',$variant_size); ?>">
<a title="<?php echo $color_title[$keycolor]; ?>" <?php echo $classselected; ?> rel="<?php echo $valuee;?>" style="background-color:<?php echo $c[$keycolor]; ?>;"><?php echo $key; ?></a>
</div><?php 
}
			
		}
	}

}
function getpostmetadetail($slug,$id='')
 {
	global $wpdb;
	$returnvalue=array();
	if($id!=''){
		foreach($id as $allid){
		$idall.=$allid->ID.',';
	}
	$idall=substr($idall,0,-1);
	$thumb_arr = $wpdb->get_results('select pm2.meta_value,pm1.post_id as color_post_id from wp_postmeta pm1
		inner join wp_postmeta pm2 on pm2.post_id = pm1.post_id where pm1.meta_key="attribute_pa_color"
		AND pm1.meta_value="'.$slug.'" and pm1.post_id IN('.$idall.') AND pm2.meta_key="_thumbnail_id"',ARRAY_A);
	}else{	
		
		$thumb_arr = $wpdb->get_results('select pm2.meta_value,pm1.post_id as color_post_id from wp_postmeta pm1
		inner join wp_postmeta pm2 on pm2.post_id = pm1.post_id where pm1.meta_key="attribute_pa_color"
		AND pm1.meta_value="'.$slug.'" AND pm2.meta_key="_thumbnail_id" and pm2.post_id="729"',ARRAY_A);
	}
	return $thumb_arr;
}

/*function eshop_get_post_id($post_name){
        global $wpdb;
	$qrypost = mysql_query("SELECT * FROM `$wpdb->posts` where post_name='$post_name'");
  	$fetchpost = mysql_fetch_array($qrypost);
 	$post_parent_id = $fetchpost['ID'];
return $post_parent_id;
}

// function for swatch join query used for page class-wc-swatch-term.php
function eshop_swatch_join($post_parent_id){
        global $wpdb;
	$qry = "SELECT * FROM `wp_posts`,wp_postmeta WHERE wp_posts.ID = wp_postmeta.post_id and wp_postmeta.meta_key='attribute_pa_color' and wp_posts.post_parent='$post_parent_id'";	
	return $result = mysql_query($qry);
}*/


function next_post_link_product($format='%link &raquo;', $link='%title', $in_same_cat = false, $excluded_categories = '') {
    adjacent_post_link_product($format, $link, $in_same_cat, $excluded_categories, false);
}

function previous_post_link_product($format='&laquo; %link', $link='%title', $in_same_cat = false, $excluded_categories = '') {
    adjacent_post_link_product($format, $link, $in_same_cat, $excluded_categories, true);
}

function adjacent_post_link_product( $format, $link, $in_same_cat = false, $excluded_categories = '', $previous = true ) {
    if ( $previous && is_attachment() ){
        $post = get_post( get_post()->post_parent );
    }else{
        $post = get_adjacent_post_product( $in_same_cat, $excluded_categories, $previous );
    }
    if ( ! $post ) {
        $output = '';
    } else {
        $title = $post->post_title;

        if ( empty( $post->post_title ) )
            $title = $previous ? __( 'Previous Post' ) : __( 'Next Post' );

        $title = apply_filters( 'the_title', $title, $post->ID );
        $date = mysql2date( get_option( 'date_format' ), $post->post_date );
	$linknext=$linkprev='';
        $rel = $previous ? 'prev' : 'next';
	$linknext=$link=='NEXT'?' ':'';
	$linkprev=$link=='NEXT'?'':' ';
        $string = '<a href="' . get_permalink( $post ) . '" rel="'.$rel.'" title="'.$link.'">';
        $inlink = str_replace( '%title', $title, $link );
        $inlink = str_replace( '%date', $date, $inlink );
        $inlink = $string . $linkprev.$inlink .$linknext. '</a>';

        $output = str_replace( '%link', $inlink, $format );
    }

    $adjacent = $previous ? 'previous' : 'next';

    echo apply_filters( "{$adjacent}_post_link", $output, $format, $link, $post );
}

function get_adjacent_post_product( $in_same_cat = false, $excluded_categories = '', $previous = true ) {
    global $wpdb;

    if ( ! $post = get_post() )
        return null;

    $current_post_date = $post->post_date;

    $join = '';
    $posts_in_ex_cats_sql = '';
    if ( $in_same_cat || ! empty( $excluded_categories ) ) {
        $join = " INNER JOIN $wpdb->term_relationships AS tr ON p.ID = tr.object_id INNER JOIN $wpdb->term_taxonomy tt ON tr.term_taxonomy_id = tt.term_taxonomy_id";

        if ( $in_same_cat ) {
            if ( ! is_object_in_taxonomy( $post->post_type, 'product_cat' ) )
                return '';
            $cat_array = wp_get_object_terms($post->ID, 'product_cat', array('fields' => 'ids'));
            if ( ! $cat_array || is_wp_error( $cat_array ) )
                return '';
            $join .= " AND tt.taxonomy = 'product_cat' AND tt.term_id IN (" . implode(',', $cat_array) . ")";
        }

        $posts_in_ex_cats_sql = "AND tt.taxonomy = 'product_cat'";
        if ( ! empty( $excluded_categories ) ) {
            if ( ! is_array( $excluded_categories ) ) {
                // back-compat, $excluded_categories used to be IDs separated by " and "
                if ( strpos( $excluded_categories, ' and ' ) !== false ) {
                    _deprecated_argument( __FUNCTION__, '3.3', sprintf( __( 'Use commas instead of %s to separate excluded categories.' ), "'and'" ) );
                    $excluded_categories = explode( ' and ', $excluded_categories );
                } else {
                    $excluded_categories = explode( ',', $excluded_categories );
                }
            }

            $excluded_categories = array_map( 'intval', $excluded_categories );

            if ( ! empty( $cat_array ) ) {
                $excluded_categories = array_diff($excluded_categories, $cat_array);
                $posts_in_ex_cats_sql = '';
            }

            if ( !empty($excluded_categories) ) {
                $posts_in_ex_cats_sql = " AND tt.taxonomy = 'product_cat' AND tt.term_id NOT IN (" . implode($excluded_categories, ',') . ')';
            }
        }
    }

    $adjacent = $previous ? 'previous' : 'next';
    $op = $previous ? '<' : '>';
    $order = $previous ? 'DESC' : 'ASC';

    $join  = apply_filters( "get_{$adjacent}_post_join", $join, $in_same_cat, $excluded_categories );
    $where = apply_filters( "get_{$adjacent}_post_where", $wpdb->prepare("WHERE p.post_date $op %s AND p.post_type = %s AND p.post_status = 'publish' $posts_in_ex_cats_sql", $current_post_date, $post->post_type), $in_same_cat, $excluded_categories );
    $sort  = apply_filters( "get_{$adjacent}_post_sort", "ORDER BY p.post_date $order LIMIT 1" );

    $query = "SELECT p.id FROM $wpdb->posts AS p $join $where $sort";
    $query_key = 'adjacent_post_' . md5($query);
    $result = wp_cache_get($query_key, 'counts');
    if ( false !== $result ) {
        if ( $result )
            $result = get_post( $result );
        return $result;
    }

    $result = $wpdb->get_var( $query );
    if ( null === $result ){
        $result = '';
        $where = apply_filters( "get_{$adjacent}_post_where", $wpdb->prepare("WHERE p.post_type = %s AND p.post_status = 'publish' $posts_in_ex_cats_sql", $post->post_type), $in_same_cat, $excluded_categories );
        $query = "SELECT p.id FROM $wpdb->posts AS p $join $where $sort";
        $query_key = 'adjacent_post_' . md5($query);
        $result = wp_cache_get($query_key, 'counts');
        if ( false !== $result ) {
            if ( $result )
                $result = get_post( $result );
            return $result;
        }

        $result = $wpdb->get_var( $query );
    }
    wp_cache_set($query_key, $result, 'counts');

    if ( $result )
        $result = get_post( $result );

    return $result;
}

function checkvalidpincode($zipcode){

	global $wpdb;
//		$checkara =  stripslashes(get_option( 'woocommerce_cod_aramex_ena' ));
//		if($checkara=='Yes'){
//			$aramex = $wpdb->get_var("SELECT count(*) as  aramexcount FROM ".$wpdb->prefix."cod_aramex WHERE pincode = ".$zipcode);//echo "</br>";
//		}
//
//		$checkdtdc =  stripslashes(get_option( 'woocommerce_cod_dtdc_ena' ));
//		if($checkdtdc=='Yes'){
//			$dtdc = $wpdb->get_var("SELECT count(*) as dtdccount FROM ".$wpdb->prefix."cod_dtdc WHERE pincode = '".$zipcode."'");//echo "</br>";
//		}
//		$checkqua =  stripslashes(get_option( 'woocommerce_cod_quantium_ena' ));
//		if($checkqua=='Yes'){
//			$quantium = $wpdb->get_var("SELECT count(*) as quantiumcount FROM  ".$wpdb->prefix."cod_quantium WHERE pincode = ".$zipcode);
//		}
		$delhivery = $wpdb->get_var("SELECT count(*) as delhi FROM  ".$wpdb->prefix."delhivery WHERE pin = ".$zipcode);

        if($aramex < 1 && $dtdc < 1 && $quantium < 1 && $bluedart < 1 && $delhivery < 1){ 
			return false;
	}else{
		return true;
	}
}
   //function to register a new taxonomy on woo init
function woocommerce_custom_suborder_status_taxonomy()
{
     register_taxonomy( 'shop_suborder_status',
	        apply_filters( 'woocommerce_taxonomy_objects_shop_order_status', array('shop_order') )
	    );
}
 add_action( 'woocommerce_register_taxonomy','woocommerce_custom_suborder_status_taxonomy' );


 function eshop_update_order_item_status($order_id,$suborder_id,$status)
{

     global $wpdb;
     if($suborder_id=='' || $status==''){
         return;
     }
     $args =array(
        'fields'        => 'all',
       'slug'          => $status,
    );

     $taxonomies=get_terms('shop_suborder_status',$args);

     $taxonomy_id=$taxonomies[0]->term_taxonomy_id;
     $search_sql="SELECT meta_value FROM ".$wpdb->prefix."woocommerce_order_itemmeta WHERE  order_item_id=".$suborder_id." AND  meta_key='suborder_status_key'";
     $searchres=$wpdb->get_results($search_sql);

     $suborder_status_key=$searchres[0]->meta_value;
     if(empty($searchres))
     {
        $add_sql="INSERT INTO ".$wpdb->prefix."woocommerce_order_itemmeta (order_item_id,meta_key,meta_value) values($suborder_id,'suborder_status_key',$taxonomy_id)";
     }
     else
     {  if($suborder_status_key !=$taxonomy_id)
        $add_sql="UPDATE ".$wpdb->prefix."woocommerce_order_itemmeta SET meta_value=".$taxonomy_id." WHERE order_item_id=".$suborder_id." AND meta_key='suborder_status_key'";
     }
     $wpdb->query($add_sql);
      woocommerce_custom_update_order_status($order_id);
}
//order status to sub order status mapping functions
 function assign_sub_order_status_processing($order_id)
 {
      $order = new WC_Order( $order_id );
       foreach($order->get_items() as $key=>$itemarray)
       {
            eshop_update_order_item_status($key,'item_processing');
       }

 }
 add_action( 'woocommerce_order_status_processing','assign_sub_order_status_processing');
 function assign_sub_order_status_cancelled($order_id)
 {
      $order = new WC_Order( $order_id );
       foreach($order->get_items() as $key=>$itemarray)
       {
            eshop_update_order_item_status($key,'item_cancelled');
       }
 }
  add_action( 'woocommerce_order_status_cancelled', 'assign_sub_order_status_cancelled');
  function woocommerce_custom_update_order_status($order_id)
{
    global $wpdb;
    $order=new WC_Order($order_id);
    $order_status=$order->status;
//included taxonomy ids for item_shipped,item_completed,item_refunded,item_returned_to_origin
    $shipped_order_status=array(218,215,216,219);
    $temp_var=1;
    foreach($order->get_items() as $key=>$itemarray)
    {
        $suborder_status_key="SELECT meta_value FROM ".$wpdb->prefix."woocommerce_order_itemmeta WHERE  order_item_id=".$key." AND  meta_key='suborder_status_key'";
        $suborder_status_key=$wpdb->get_results($suborder_status_key);
        $suborder_status_key=$suborder_status_key[0]->meta_value;
        //
       //if()
       if(!in_array($suborder_status_key,$shipped_order_status))
       {
           $temp_var++;
       }

           $item_status_array[]=$suborder_status_key;
    }
    //order status update when suborder status falls in shipped array

    if(count(array_unique($item_status_array)) == 1 && ($item_status_array[0]==218 || $item_status_array[0]==215 ||
             $item_status_array[0]==216 ||  $item_status_array[0]==219 ) )
    {
       switch($item_status_array[0]){
           case 215:            //for delivered or completed
                if($order_status!='completed')
               $order->update_status('completed');
               break;
           case 219:            // for returned to origin
                 if($order_status!='rto')
               $order->update_status('rto');
               break;
           case 216:            // for returned or refunded
                 if($order_status!='refunded')
               $order->update_status('refunded');
               break;
            case 218:            // for returned or refunded
              if($order_status!='shipped')
            $order->update_status('shipped');
            break;
       }
    }else{
        if($temp_var == 1){
              if($order_status!='shipped')
                $order->update_status('shipped');
        }
    }
}

##########################################################################################################
##			WOOCOMMERCE COUNTRY / CURRENCY RELATED FUNCTIONS GOES HERE--								##
##########################################################################################################

//-- Function for set user country on change ----
function changeMyCountry($post){
	session_start();
	global $eshopbox;
	if (!empty($post) && $post['countryn']!='') {
		$_SESSION['country_detail_code']=$post['countryn'];
		//$USER_COUNTRY = $_SESSION['country_detail_code'];
	}elseif(empty($_SESSION['country_detail_code'])){
		$_SESSION['country_detail_code']= 'IN';
	}
	$site_url = get_bloginfo('siteurl').$_SERVER['REQUEST_URI'];
	//-- Update shipping country code as per the country name selected by the user -----
	$eshopbox->customer->set_shipping_location( $_SESSION['country_detail_code'], '', '', '');
	echo "<script language=\"JavaScript\">{ location.href=\"$site_url\"; }</script>";
}

//add_action('template_redirect', 'hooker');
function hooker(){
    global $eshopbox;
	session_start();
	if(isset($_SESSION['country_detail_code']) == '' || !isset($_SESSION['country_detail_code'])){
		$_SESSION['country_detail_code'] = 'IN';
	}

	$d_country = $eshopbox->customer->get_shipping_country();
	if($d_country == ''){
		$eshopbox->customer->set_shipping_location( $_SESSION['country_detail_code'], '', '', '');
	}elseif($d_country != COUNTRY_IN_SESSION){
		$eshopbox->customer->set_shipping_location( $_SESSION['country_detail_code'], '', '', '');
	}
	return;
}

//-- Function returns user country details array---
function get_my_country_array($c_code){
	global $wpdb;
	$countryArray = $wpdb->get_row("SELECT * from wp_countrylist where country_code ='".$c_code."'");
	return $countryArray;
}

function eshop_country_currency_pop(){
	global $wpdb;
	$countryArray = $wpdb->get_results("SELECT * from wp_countrylist");
	return $countryArray;
}
//-- Function returns currency rate by passed country code --
function getCurrencyPriceByCountry($country_code){
	session_start();
	global $wpdb;
	$currency_rate = $wpdb->get_var("SELECT currency_value from wp_countrylist where country_code='".$country_code."'");
	return $currency_rate;
}

//-- Function returns currency symbol by passed country code --
function getCurrencySymbolByCountry($country_code){
	session_start();
	global $wpdb;
	$country_currency = $wpdb->get_var("SELECT country_currency from wp_countrylist where country_code='".$country_code."'");
	return $country_currency;
}

// function to calculate currency rate
function getFormatedCurrencyRate($iPrice,$szLocale){
	global $wpdb;
	$country_val = array();
	$countryArray = $wpdb->get_row("SELECT * FROM wp_countrylist WHERE country_code='".$szLocale."' ");
	$country_val= $countryArray->currency_value;
	$country_currency= $countryArray->country_currency;

	$iTotal = $iPrice * $country_val;
	if(round($iTotal,0) == 0){
		return;
	}else{
		$iTotal = number_format((float)$iTotal);
		return $country_currency." ".$iTotal;
	}
}

//-- Hook function for overwrite user currency amt ---
//add_filter('woocommerce_get_price', 'return_custom_price', $product, 2);
function return_custom_price($price, $product) {
	session_start();
    global $post, $eshopbox;
    $post_id = $product->id;
    $user_country = $_SESSION['country_detail_code'];
    // If the IP detection is enabled look for the correct price
    if($user_country!=''){
		$new_price = getCurrencyPriceByCountry($user_country);
        $new_price = intval($price*$new_price);
		//die($new_price);
        if($new_price==''){
            $new_price = $price;
        }
    }else{
		$new_price = $price;
	}
    return $new_price;
}

//--- Hook function for add dynamic currency symbol as per the counry selected by the user---
//add_filter('woocommerce_currency_symbol', 'add_custom_symbol', 10, 2);
function add_custom_symbol() {
		session_start();
		$symbol = getCurrencySymbolByCountry($_SESSION['country_detail_code']);
		return $symbol.'&nbsp;';
}

//--- Filter for remove any payment gateway as per the country selected by the user--
add_filter('eshopbox_available_payment_gateways','filter_gateways',1);
function filter_gateways($gateways){
	global $eshopbox;
	session_start();
	$d_country = $eshopbox->customer->get_shipping_country();
	//--$payent_settings = array('paypal','bacs','cheque','payu_in');
	if($d_country !='IN'){
		unset($gateways['payu_in']);
		unset($gateways['cod']);
	}else{
		unset($gateways['paypal']);
	}
 return $gateways;
}

function get_country_currency($currency){
	//--- $from = 'INR'; $to = 'USD';
	if(empty($currency)) return;
	$url = 'http://finance.yahoo.com/d/quotes.csv?f=l1d1t1&s=INR'.$currency.'=X';
	$handle = fopen($url, 'r');

	if ($handle) {
		$result = fgetcsv($handle);
		fclose($handle);
	}
	return $result[0];
	//echo '1 '.$from.' is worth '.$result[0].' '.$to.' Based on data on '.$result[1].' '.$result[2];
}


//add_action( 'wp', 'update_country_currency_price' );
//--- CUSTOM FUNCTION GOES FOR UPDATE COUNTRY CURRENCY PRICE AGAINST RUPEE ---
function update_country_currency_price(){
	global $wpdb;
	$country_currency = $wpdb->get_results("SELECT * from wp_countrylist");
	//echo "<pre>"; print_r($country_currency); echo "</pre>";
	if(!empty($country_currency)){
		foreach($country_currency as $country_data){
			//echo $country_data->country_currency;
			if($country_data->country_currency == '$'){
				$country_data->country_currency = 'USD';
			}
		    $converted_price = get_country_currency($country_data->country_currency);
			//echo "<br>Price of ".$country_data->country_currency." is ".$converted_price;
			if($converted_price > 0){
				$wpdb->query("UPDATE wp_countrylist SET wp_countrylist.currency_value = '".$converted_price."' WHERE wp_countrylist.country_code = '".$country_data->country_code."'");
			}
		}
	}
}
##########################################################################################################
?>
