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
 * @subpackage PINQCKIK
 * @since PINQCKIK
 */
/**
 * Sets up the content width value based on the theme's design and stylesheet.
 */
if (!isset($content_width))
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
 * @since PINQCKIK
 */
function PINQCKIK_setup() {
	/*
	 * Makes Twenty Twelve available for translation.
	 *
	 * Translations can be added to the /languages/ directory.
	 * If you're building a theme based on Twenty Twelve, use a find and replace
	 * to change 'PINQCKIK' to the name of your theme in all the template files.
	 */
	load_theme_textdomain('PINQCKIK', get_template_directory() . '/languages');

	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();

	// Adds RSS feed links to <head> for posts and comments.
	add_theme_support('automatic-feed-links');

	// This theme supports a variety of post formats.
	add_theme_support('post-formats', array('aside', 'image', 'link', 'quote', 'status'));

	// This theme uses wp_nav_menu() in one location.
	register_nav_menu('primary', __('Primary Menu', 'PINQCKIK'));

	/*
	 * This theme supports custom background color and image, and here
	 * we also set up the default background color.
	 */
	add_theme_support('custom-background', array(
		'default-color' => 'e6e6e6',
			));

	// This theme uses a custom image size for featured images, displayed on "standard" posts.
	add_theme_support('post-thumbnails');
	set_post_thumbnail_size(624, 9999); // Unlimited height, soft crop
}

add_action('after_setup_theme', 'PINQCKIK_setup');

/**
 * Adds support for a custom header image.
 */
require( get_template_directory() . '/inc/custom-header.php' );

/**
 * Enqueues scripts and styles for front-end.
 *
 * @since PINQCKIK
 */
function PINQCKIK_scripts_styles() {
	global $wp_styles;

	/*
	 * Adds JavaScript to pages with the comment form to support
	 * sites with threaded comments (when in use).
	 */
	if (is_singular() && comments_open() && get_option('thread_comments'))
		wp_enqueue_script('comment-reply');

	/*
	 * Adds JavaScript for handling the navigation menu hide-and-show behavior.
	 */
	//wp_enqueue_script( 'PINQCKIK-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '1.0', true );

	/*
	 * Loads our special font CSS file.
	 *
	 * The use of Open Sans by default is localized. For languages that use
	 * characters not supported by the font, the font can be disabled.
	 *
	 * To disable in a child theme, use wp_dequeue_style()
	 * function mytheme_dequeue_fonts() {
	 *     wp_dequeue_style( 'PINQCKIK-fonts' );
	 * }
	 * add_action( 'wp_enqueue_scripts', 'mytheme_dequeue_fonts', 11 );
	 */

	/* translators: If there are characters in your language that are not supported
	  by Open Sans, translate this to 'off'. Do not translate into your own language. */
	if ('off' !== _x('on', 'Open Sans font: on or off', 'PINQCKIK')) {
		$subsets = 'latin,latin-ext';

		/* translators: To add an additional Open Sans character subset specific to your language, translate
		  this to 'greek', 'cyrillic' or 'vietnamese'. Do not translate into your own language. */
		$subset = _x('no-subset', 'Open Sans font: add new subset (greek, cyrillic, vietnamese)', 'PINQCKIK');

		if ('cyrillic' == $subset)
			$subsets .= ',cyrillic,cyrillic-ext';
		elseif ('greek' == $subset)
			$subsets .= ',greek,greek-ext';
		elseif ('vietnamese' == $subset)
			$subsets .= ',vietnamese';

		$protocol = is_ssl() ? 'https' : 'http';
		$query_args = array(
			'family' => 'Open+Sans:400italic,700italic,400,700',
			'subset' => $subsets,
		);
		wp_enqueue_style('PINQCKIK-fonts', add_query_arg($query_args, "$protocol://fonts.googleapis.com/css"), array(), null);
	}

	/*
	 * Loads our main stylesheet.
	 */
	wp_enqueue_style('PINQCKIK-style', get_stylesheet_uri());

	/*
	 * Loads the Internet Explorer specific stylesheet.
	 */
	wp_enqueue_style('PINQCKIK-ie', get_template_directory_uri() . '/css/ie.css', array('PINQCKIK-style'), '20121010');
	$wp_styles->add_data('PINQCKIK-ie', 'conditional', 'lt IE 9');
}

add_action('wp_enqueue_scripts', 'PINQCKIK_scripts_styles');

/**
 * Creates a nicely formatted and more specific title element text
 * for output in head of document, based on current view.
 *
 * @since PINQCKIK
 *
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 * @return string Filtered title.
 */
function PINQCKIK_wp_title($title, $sep) {
	global $paged, $page;

	if (is_feed ())
		return $title;

	// Add the site name.
	$title .= get_bloginfo('name');

	// Add the site description for the home/front page.
	$site_description = get_bloginfo('description', 'display');
	if ($site_description && ( is_home() || is_front_page() ))
		$title = "$title $sep $site_description";

	// Add a page number if necessary.
	if ($paged >= 2 || $page >= 2)
		$title = "$title $sep " . sprintf(__('Page %s', 'PINQCKIK'), max($paged, $page));

	return $title;
}

add_filter('wp_title', 'PINQCKIK_wp_title', 10, 2);

/**
 * Makes our wp_nav_menu() fallback -- wp_page_menu() -- show a home link.
 *
 * @since PINQCKIK
 */
function PINQCKIK_page_menu_args($args) {
	if (!isset($args['show_home']))
		$args['show_home'] = true;
	return $args;
}

add_filter('wp_page_menu_args', 'PINQCKIK_page_menu_args');

/**
 * Registers our main widget area and the front page widget areas.
 *
 * @since PINQCKIK
 */
function PINQCKIK_widgets_init() {
	register_sidebar(array(
		'name' => __('Main Sidebar', 'PINQCKIK'),
		'id' => 'sidebar-1',
		'description' => __('Appears on posts and pages except the optional Front Page template, which has its own widgets', 'PINQCKIK'),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
			));

	register_sidebar(array(
		'name' => __('First Front Page Widget Area', 'PINQCKIK'),
		'id' => 'sidebar-2',
		'description' => __('Appears when using the optional Front Page template with a page set as Static Front Page', 'PINQCKIK'),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
			));

	register_sidebar(array(
		'name' => __('Second Front Page Widget Area', 'PINQCKIK'),
		'id' => 'sidebar-3',
		'description' => __('Appears when using the optional Front Page template with a page set as Static Front Page', 'PINQCKIK'),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
			));
}

add_action('widgets_init', 'PINQCKIK_widgets_init');

if (!function_exists('PINQCKIK_content_nav')) :

	/**
	 * Displays navigation to next/previous pages when applicable.
	 *
	 * @since PINQCKIK
	 */
	function PINQCKIK_content_nav($html_id) {
		global $wp_query;

		$html_id = esc_attr($html_id);

		if ($wp_query->max_num_pages > 1) :
?>
			<nav id="<?php echo $html_id; ?>" class="navigation" role="navigation">
				<h3 class="assistive-text"><?php _e('Post navigation', 'PINQCKIK'); ?></h3>
				<div class="nav-previous alignleft"><?php next_posts_link(__('<span class="meta-nav">&larr;</span> Older posts', 'PINQCKIK')); ?></div>
				<div class="nav-next alignright"><?php previous_posts_link(__('Newer posts <span class="meta-nav">&rarr;</span>', 'PINQCKIK')); ?></div>
			</nav><!-- #<?php echo $html_id; ?> .navigation -->
<?php
			endif;
		}

	endif;

	if (!function_exists('PINQCKIK_comment')) :

		/**
		 * Template for comments and pingbacks.
		 *
		 * To override this walker in a child theme without modifying the comments template
		 * simply create your own PINQCKIK_comment(), and that function will be used instead.
		 *
		 * Used as a callback by wp_list_comments() for displaying the comments.
		 *
		 * @since PINQCKIK
		 */
		function PINQCKIK_comment($comment, $args, $depth) {
			$GLOBALS['comment'] = $comment;
			switch ($comment->comment_type) :
				case 'pingback' :
				case 'trackback' :
					// Display trackbacks differently than normal comments.
?>
				<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
					<p><?php _e('Pingback:', 'PINQCKIK'); ?> <?php comment_author_link(); ?> <?php edit_comment_link(__('(Edit)', 'PINQCKIK'), '<span class="edit-link">', '</span>'); ?></p>
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
					echo get_avatar($comment, 44);
					printf('<cite class="fn">%1$s %2$s</cite>',
							get_comment_author_link(),
							// If current post author is also comment author, make it known visually.
							( $comment->user_id === $post->post_author ) ? '<span> ' . __('Post author', 'PINQCKIK') . '</span>' : ''
					);
					printf('<a href="%1$s"><time datetime="%2$s">%3$s</time></a>',
							esc_url(get_comment_link($comment->comment_ID)),
							get_comment_time('c'),
							/* translators: 1: date, 2: time */
							sprintf(__('%1$s at %2$s', 'PINQCKIK'), get_comment_date(), get_comment_time())
					);
		?>
				</header><!-- .comment-meta -->

<?php if ('0' == $comment->comment_approved) : ?>
					<p class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.', 'PINQCKIK'); ?></p>
<?php endif; ?>

					<section class="comment-content comment">
<?php comment_text(); ?>
<?php edit_comment_link(__('Edit', 'PINQCKIK'), '<p class="edit-link">', '</p>'); ?>
							</section><!-- .comment-content -->

							<div class="reply">
<?php comment_reply_link(array_merge($args, array('reply_text' => __('Reply', 'PINQCKIK'), 'after' => ' <span>&darr;</span>', 'depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
							</div><!-- .reply -->
						</article><!-- #comment-## -->
<?php
						break;
				endswitch; // end comment_type check
			}

		endif;

		if (!function_exists('PINQCKIK_entry_meta')) :

			/**
			 * Prints HTML with meta information for current post: categories, tags, permalink, author, and date.
			 *
			 * Create your own PINQCKIK_entry_meta() to override in a child theme.
			 *
			 * @since PINQCKIK
			 */
			function PINQCKIK_entry_meta() {
				// Translators: used between list items, there is a space after the comma.
				$categories_list = get_the_category_list(__(', ', 'PINQCKIK'));

				// Translators: used between list items, there is a space after the comma.
				$tag_list = get_the_tag_list('', __(', ', 'PINQCKIK'));

				$date = sprintf('<a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a>',
								esc_url(get_permalink()),
								esc_attr(get_the_time()),
								esc_attr(get_the_date('c')),
								esc_html(get_the_date())
				);

				$author = sprintf('<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span>',
								esc_url(get_author_posts_url(get_the_author_meta('ID'))),
								esc_attr(sprintf(__('View all posts by %s', 'PINQCKIK'), get_the_author())),
								get_the_author()
				);

				// Translators: 1 is category, 2 is tag, 3 is the date and 4 is the author's name.
				if ($tag_list) {
					$utility_text = __('This entry was posted in %1$s and tagged %2$s on %3$s<span class="by-author"> by %4$s</span>.', 'PINQCKIK');
				} elseif ($categories_list) {
					$utility_text = __('This entry was posted in %1$s on %3$s<span class="by-author"> by %4$s</span>.', 'PINQCKIK');
				} else {
					$utility_text = __('This entry was posted on %3$s<span class="by-author"> by %4$s</span>.', 'PINQCKIK');
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
		 * @since PINQCKIK
		 *
		 * @param array Existing class values.
		 * @return array Filtered class values.
		 */
		function PINQCKIK_body_class($classes) {
			$background_color = get_background_color();

			if (!is_active_sidebar('sidebar-1') || is_page_template('page-templates/full-width.php'))
				$classes[] = 'full-width';

			if (is_page_template('page-templates/front-page.php')) {
				$classes[] = 'template-front-page';
				if (has_post_thumbnail ())
					$classes[] = 'has-post-thumbnail';
				if (is_active_sidebar('sidebar-2') && is_active_sidebar('sidebar-3'))
					$classes[] = 'two-sidebars';
			}

			if (empty($background_color))
				$classes[] = 'custom-background-empty';
			elseif (in_array($background_color, array('fff', 'ffffff')))
				$classes[] = 'custom-background-white';

			// Enable custom font class only if the font CSS is queued to load.
			if (wp_style_is('PINQCKIK-fonts', 'queue'))
				$classes[] = 'custom-font-enabled';

			if (!is_multi_author())
				$classes[] = 'single-author';

			return $classes;
		}

		add_filter('body_class', 'PINQCKIK_body_class');

		/**
		 * Adjusts content_width value for full-width and single image attachment
		 * templates, and when there are no active widgets in the sidebar.
		 *
		 * @since PINQCKIK
		 */
		function PINQCKIK_content_width() {
			if (is_page_template('page-templates/full-width.php') || is_attachment() || !is_active_sidebar('sidebar-1')) {
				global $content_width;
				$content_width = 960;
			}
		}

		add_action('template_redirect', 'PINQCKIK_content_width');

		/**
		 * Add postMessage support for site title and description for the Theme Customizer.
		 *
		 * @since PINQCKIK
		 *
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 * @return void
		 */
		function PINQCKIK_customize_register($wp_customize) {
			$wp_customize->get_setting('blogname')->transport = 'postMessage';
			$wp_customize->get_setting('blogdescription')->transport = 'postMessage';
		}

		add_action('customize_register', 'PINQCKIK_customize_register');

		/**
		 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
		 *
		 * @since PINQCKIK
		 */
		function PINQCKIK_customize_preview_js() {
			wp_enqueue_script('PINQCKIK-customizer', get_template_directory_uri() . '/js/theme-customizer.js', array('customize-preview'), '20120827', true);
		}

		add_action('customize_preview_init', 'PINQCKIK_customize_preview_js');

// my custom files
		require_once(get_template_directory() . '/includes/enque.php');
		require_once(get_template_directory() . '/includes/widgets.php');
		require_once(get_template_directory() . '/includes/newcode.php');
		require_once get_template_directory() . '/inc/custom-woospecs.php'; // eshopbox custom tabs
		require_once get_template_directory() . '/inc/custom-woodownloads.php'; // eshopbox custom tab 2
// function to return post name through product id
		
register_sidebar( array(

		'name' => __( 'Colorfilterblock', 'twentytwelve' ),

		'id' => 'colorfilterblock',

		'description' => __( 'Appears on posts and pages except the optional Front Page template, which has its own widgets', 'twentytwelve' ),

		'before_widget' => '',

		'after_widget' => '',

		'before_title' => '<h3 class="widget-title">',

		'after_title' => '</h3>',

	) );


register_sidebar( array(

		'name' => __( 'Homepagesliderwidget', 'twentytwelve' ),

		'id' => 'homepagesliderwidget',

		'description' => __( 'Appears on posts and pages except the optional Front Page template, which has its own widgets', 'twentytwelve' ),

		'before_widget' => '',

		'after_widget' => '',

		'before_title' => '',

		'after_title' => '',

	) );

register_sidebar( array(

		'name' => __( 'Sizefilterblock', 'twentytwelve' ),

		'id' => 'sizefilterblock',

		'description' => __( 'Appears on posts and pages except the optional Front Page template, which has its own widgets', 'twentytwelve' ),

		'before_widget' => '',

		'after_widget' => '',

		'before_title' => '<h3 class="widget-title">',

		'after_title' => '</h3>',

	) );

register_sidebar( array(

		'name' => __( 'Pricefilterblock', 'twentytwelve' ),

		'id' => 'pricefilterblock',

		'description' => __( 'Appears on posts and pages except the optional Front Page template, which has its own widgets', 'twentytwelve' ),

		'before_widget' => '',

		'after_widget' => '',

		'before_title' => '<h3 class="widget-title">',

		'after_title' => '</h3>',

	) );
	

function eshop_get_post_name($product_id) {
			global $wpdb;
			$qrypost = mysql_query("SELECT * FROM $wpdb->posts where ID='$product_id'");
			$fetchpost = mysql_fetch_array($qrypost);
			$post_name = $fetchpost['post_name'];
			return $post_name;
		}


//-- Function goes for get product thumbnail id against filter id ----
function get_post_thumbnail_id_arr($term_id,$product_id, $taxonomy='pa_color'){
	global $wpdb;	$postImgData = array();
	$term_arr = get_term( $term_id, $taxonomy);

	$querystr = "
    SELECT $wpdb->posts.ID, pm2.meta_key, pm2.meta_value
	FROM $wpdb->postmeta,$wpdb->posts
    INNER JOIN $wpdb->postmeta as pm2 on pm2.post_id = $wpdb->posts.ID
    WHERE $wpdb->posts.ID = $wpdb->postmeta.post_id
	AND pm2.meta_key = '_thumbnail_id'
	AND $wpdb->posts.post_parent = $product_id
    AND $wpdb->postmeta.meta_key = 'attribute_pa_color'
    AND $wpdb->postmeta.meta_value = '".$term_arr->slug."'
    AND $wpdb->posts.post_type = 'product_variation' group by pm2.meta_key
	";
	$postImgData = $wpdb->get_row($querystr, ARRAY_A);
	return $postImgData;
}

// function to fetch image on listing page according to filter color
function eshop_get_filter_image($filter_id,$product_id){
	global $wpdb; $imagepathData = array();
	$array_rev = array_reverse(explode(',',$filter_id));
	$filter_id = $array_rev[0];	
	if(!empty($array_rev))
	{
		foreach($array_rev as $value)
		{
			$thumbnail_arr = get_post_thumbnail_id_arr($value, $product_id, 'pa_color');
			if(!empty($thumbnail_arr)){
				$imagepathData = wp_get_attachment_image_src($thumbnail_arr['meta_value'],array(300,300));
				break;
			}
		}
	}
	return $imagepathData['0'];

	//$postImgData = $wpdb->get_row($querystr, ARRAY_A);
	//echo '<pre>'; 	print_r($postImgData); exit;
	//meta_value postmeta.meta_key='_thumbnail_id'
	//print_r($postImgData);
	//	if(!empty($postImgData)){
			//echo $postImgData['meta_value'];
	//		$imagepath = wp_get_attachment_image_src($postImgData['meta_value'],array(300,300));
	//	}

		/*
		$thumbnailid = getpostmetadetail_custom($term_arr->slug,$attachmentss);		
		foreach($thumbnailid as $arr){
			$imagepath[] = wp_get_attachment_image_src($arr[0],array(300,300));
		}		
		$imagepath = wp_get_attachment_image_src($thumbnailid[0][0],array(300,300));
		echo $imagepath; print_r($imagepath); exit;*/

	/*
	//$post_name = eshop_get_post_name($product_id);
	$color_slug_qry = mysql_query("select slug from pinqckik_terms where term_id = '".$filter_id."'");
	$color_slug = mysql_fetch_row($color_slug_qry);
	$color_slug[0];
	$post_meta_detail = getpostmetadetail($color_slug[0]);
	$variation_id = eshop_fetch_product_varioation_id($product_id);

	foreach($variation_id as $key=> $att){
		foreach($post_meta_detail as $key =>$value){
			if($value[1]==$att){
			$imagearray=wp_get_attachment_image_src($value[0],array(300,300));
			//$imagearray = wp_get_attachment_image_src(get_post_thumbnail_id($value[0],'shop_catalog'));

			//print_r($imagearray);

			$single_var=$att;

			}

		}

	}

	//echo "<pre>testing";print_r($imagearray);

	if(!empty($imagearray)){
		$filter_img = array_values(array_unique($imagearray));
		$filter_img = $filter_img[0];
	}

	return $filter_img;
	*/
}

function getpostmetadetail($slug){
	$returnvalue=array();
	$query=mysql_query('select post_id from pinqckik_postmeta where meta_key="attribute_pa_color" and meta_value="'.$slug.'"');
	$i=0;

	while($result=mysql_fetch_row($query)){
		$querydata=mysql_query('select meta_value from pinqckik_postmeta where meta_key="_thumbnail_id" and post_id="'.$result[0].'"');
		$resultdata=mysql_fetch_row($querydata);
		if($resultdata[0]>0){
			$returnvalue[$i][0]=$resultdata[0];
		//$returnvalue[$i][0]=array_values(array_unique($returnvalue[$i]));
		$returnvalue[$i][1]=$result[0];
		$i++;
		}

	}
	return $returnvalue;
}


function getswatchimage($productid){

	$query=mysql_query('select meta_value from pinqckik_postmeta where meta_key="_swatch_type_options" and post_id="'.$productid.'"');

	$resultdata=mysql_fetch_row($query);

	$data=unserialize($resultdata[0]);

    $upload_dir = wp_upload_dir();

	//print_r($upload_dir);

	if(!empty($data)){

	foreach($data as $key=>$value){

            if($key=='pa_color'){

                $i=0;

                foreach($value['attributes'] as $keyattr=>$valueattr){

                   $postimageid=$valueattr['image'];

                   $imagedetail= get_post_meta($postimageid,'_wp_attachment_metadata');

		   $swatchimage[$i]=$upload_dir['url'].'/'.$imagedetail[0]['sizes']['swatches_image_size']['file'];

		   if($imagedetail == NULL){ //echo "m in ifff";

			$imagedetail=get_post_meta($postimageid,'_wp_attached_file');

			 $swatchimage[$i]=$upload_dir['url'].'/'.$imagedetail[0];

		    }

                   $i++;

                }

            }

        }

	}

        return $swatchimage;
}

// functio to give variation ids of a product
function eshop_fetch_product_varioation_id($product_id){

	$variation_id_arr = array();

	$attachmentss = get_posts( array(

				'post_type' 	=> 'product_variation',

				'post_parent' 	=> $product_id,

				'orderby'	=> 'menu_order',

				'order' 	=> 'ASC',

				'posts_per_page'  => 50,

				'numberposts'     => 50,

			) );

	foreach($attachmentss as $key=> $att){

		$variation_id_arr[] = $att->ID;

	}

	return $variation_id_arr;

}

function is_swatch_image_exists($product_id){
	 $swatch=getswatchimage($product_id);
	 //print_r($swatch);
	 if(empty($swatch) || sizeof($swatch) <= 1){
		 return 0;
	 }else{
		 return 1;
	 }
}

function current_page_url() {
	$pageURL = 'http';
	if( isset($_SERVER["HTTPS"]) ) {
		if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
	}
	$pageURL .= "://";
	if ($_SERVER["SERVER_PORT"] != "80") {
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	} else {
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}
	return $pageURL;
}

function eshop_get_swatch_images_list($product){
	$product_id = $product->id;
	$default_swatch = get_post_meta($product_id,'_default_attributes');
 	$default_swatch_c = $default_swatch[0]['pa_color'];
	$color_default = mysql_query("select term_id from pinqckik_terms where slug= '".$default_swatch_c."'");

	$color_default_id = mysql_fetch_row($color_default);
	$color_defaultid = $color_default_id[0];
	$post_name = eshop_get_post_name($product_id);
	$tt_color = wp_get_post_terms($product_id,'pa_color');

	$available_variations = $product->get_available_variations();
	$color_variations = array();
	foreach($available_variations as $variations_arr){
		$color_variations[$variations_arr['attributes']['attribute_pa_color']] = $variations_arr['variation_id'] ;
	}

	$cc= array();
	$img_arr = array();
	if($tt_color != NULL){
		$k = 0;
		$i = 0;
		$img_arr = array();
		$color_name =array_values(eshopbox_get_product_terms( $product_id, 'pa_color'));
        $swatch=getswatchimage($product_id);
		//echo '<pre>'; print_r($swatch); 	echo '</pre>';
		$attachmentss = get_posts( array(
			'post_type' 	=> 'product_variation',
			'post_parent' 	=> $product_id,
			'orderby'	=> 'menu_order',
			'order' 	=> 'ASC',
			'posts_per_page'  => 50,
			'numberposts'     => 50,
		));

		foreach($color_name as $keycolor=>$colorname){
			$color = array();
			$variant_size = array();
			$termids = $colorname->term_id;//echo "";
			$termids1[] = $colorname->term_id;
			$color_title[] = $colorname->slug;
			$thumbnailid=getpostmetadetail($colorname->slug);
			$c = get_eshopbox_term_meta($termids,'pa_color_swatches_id_photo');

			foreach($attachmentss as $key=> $att){
					foreach($thumbnailid as $key =>$value){
						if($value[1]==$att->ID){
							$postmetadetail=get_post_meta($att->ID);//echo "<pre>";print_r($postmetadetail);
							$variation_stock = $postmetadetail['_stock'][0];
							if($variation_stock > 0){
								$variant_size[] = $postmetadetail['attribute_pa_size'][0];//echo "<br/>";
							}
							$imagearray=wp_get_attachment_image_src($value[0],array(300,300));
							//echo '<pre>'; print_r($imagearray);
							$allimagearray[]=$imagearray[0];
							if(!in_array($att->ID,$color) && count($color)==0){
								$allvar[]=$att->ID;
								$color[0]='';
							}
						}
					}
			}
			$imageall = array();
			$allvar= @array_values(array_unique($allvar));
		if(!empty($allimagearray) && count($allimagearray)>0){
		$imageall=array_unique($allimagearray);
		$uniqueimage = array_values($imageall);
		$img_new[] = $uniqueimage[$keycolor].'==='.$product_id."@@@";
		$array_rev = array_reverse(explode(',',$_GET['filter_color']));
		$filter_id = $array_rev[0];
		if($default_swatch_c == $colorname->slug &&($_GET['filter_color']=='')){
			$classselected = "class=selected";
		}
		elseif($filter_id == $termids1[$keycolor]){
			$classselected = "class=selected";
		}else{ $classselected='';}
		$count_swatch = count($swatch);
			if($count_swatch>1){ ?>
			<li class="select-option swatch-wrapper product_<?php echo $product_id;?>" data-value="<?php echo $key; ?>" rel="<?php echo $img_new[$keycolor]; ?>***<?php echo $product_id; ?>***<?php echo $product_id; ?>***<?php echo $post_name; ?>*=*<?php echo $color[0]; ?>===<?php echo implode(',',$variant_size); ?>">
			<a title="<?php echo $color_title[$keycolor]; ?>" rev="<?php echo $color_variations[$colorname->slug]; ?>"
			   href="<?php echo get_permalink().'?vid='.$color_variations[$colorname->slug]; ?>" <?php echo $classselected; ?> rel="<?php echo $valuee;?>">
				<?php echo '<img class="wp-post-image swatch-photopa_color_swatches_id" width="25" height="15" alt="Thumbnail" src="'.$swatch[$keycolor].'" data-o_src="'.$swatch[$keycolor].'">'; ?>
			</a>
			</li><?php
			}
		}
		}
	}
}
add_filter('show_admin_bar', '__return_false'); //-- Hide admin menu bar

// function to get post id through post name
function eshop_get_post_id($post_name){
	$qrypost = mysql_query("SELECT * FROM `pinqckik_posts` where post_name='$post_name'");
  	$fetchpost = mysql_fetch_array($qrypost);
 	$post_parent_id = $fetchpost['ID'];
return $post_parent_id;
}

// function for swatch join query used for page class-wc-swatch-term.php
function eshop_swatch_join($post_parent_id){
	$qry = "SELECT * FROM `pinqckik_posts`,pinqckik_postmeta WHERE pinqckik_posts.ID = pinqckik_postmeta.post_id and pinqckik_postmeta.meta_key='attribute_pa_color' and pinqckik_posts.post_parent='$post_parent_id'";	
	return $result = mysql_query($qry);
}
function getquantity($productid){
    $args = array(
	'posts_per_page'  => 500,
	'numberposts'     => 500,
	'offset'          => 0,
	'category'        => '',
	'orderby'         => 'post_date',
	'order'           => 'DESC',
	'include'         => '',
	'exclude'         => '',
	'meta_key'        => '',
	'meta_value'      => '',
	'post_type'       => 'product_variation',
	'post_mime_type'  => '',
	'post_parent'     => $productid,
	'post_status'     => 'publish',
	'suppress_filters' => true ); 
    $value=get_posts($args);
    
    foreach($value as $key=>$postid){
        $postmetadetail=get_post_meta($postid->ID);
        //echo "<pre>";print_r($postmetadetail);echo "</pre>";
        $data.=$postmetadetail['attribute_pa_size'][0]."==".$postmetadetail['attribute_pa_color'][0]."==".$postmetadetail['_stock'][0]."***";
    }
    return $data;
}

//do_action('register_form');
add_action('register_form','register_extra_fields');
function register_extra_fields(){ 
?>
<p class="form-row form-row-wide">
   <label for="reg_dispname"><?php _e('Full Name') ?><span class="required">*</span></label>
   <?php
   if(is_page('checkout')){
   ?>
   <input id="reg_dispname"  class="input-text" placeholder="Full Name" type="text" size="25" value="<?php echo $_POST['reg_dispname']; ?>" name="reg_dispname"/>
   <?php } else { ?>
   <input id="reg_dispname"  class="input-text" type="text" size="25" value="<?php echo $_POST['reg_dispname']; ?>" name="reg_dispname"/>
   <?php } ?>
</p>
<!--<p><input type="hidden" name="status" value="0" /></p>-->
<?php
}

add_action('user_register', 'register_post_fields');
function register_post_fields($user_id, $password='', $meta=array())  {
    $userdata = array();
    $userdata['ID'] = $user_id;
    //$userdata['first_name'] = $_POST['first_name'];
    wp_update_user($userdata);
    wp_update_user(array ( 'ID' => $user_id, 'display_name' => $_POST['reg_dispname'] ));
    //update_usermeta( $user_id, 'phone', $_POST['phone'] );
    //update_usermeta( $user_id, 'name_l', $_POST['name_l'] );
   
}
function eshop_get_product_category(){
	$terms = get_the_terms( $post->ID, 'product_cat' );
	$product_post_array = $product;
	$cat_arr = array();
	foreach ($terms as $key=>$term){
		$product_cat_id = $term->term_id;
		$product_cat_name = $term->name;
		$product_cat_slug[] = $term->slug;
		}
return $product_cat_slug[0];
}


//filter to add store specific constants to the general setting tab of boxbeat
add_filter('admin_init', 'my_general_settings_register_fields');
function my_general_settings_register_fields()
{
    //support email-the support email address of the store
    register_setting('general', 'support_email', 'esc_attr');
    add_settings_field('support_email', '<label for="support_email">'.__('Store Support Email' , 'support_email' ).'</label>' , 'my_general_settings_fields_html_support_email', 'general');

    //support phone- the support contact no of the store
    register_setting('general', 'support_phone', 'esc_attr');
    add_settings_field('support_phone', '<label for="support_phone">'.__('Store Support Contact No' , 'support_phone' ).'</label>' , 'my_general_settings_fields_html_support_phone', 'general');

     //support team- the support team name  of the store
    register_setting('general', 'support_team', 'esc_attr');
    add_settings_field('support_team', '<label for="support_team">'.__('Store Support Team Name' , 'support_team' ).'</label>' , 'my_general_settings_fields_html_store_team', 'general');

    //Store facebook url
    register_setting('general', 'store_fb', 'esc_attr');
    add_settings_field('store_fb', '<label for="store_fb">'.__('Store Facebook Url' , 'store_fb' ).'</label>' , 'my_general_settings_fields_html_store_fb', 'general');

    //Store twitter url
    register_setting('general', 'store_twitter', 'esc_attr');
    add_settings_field('store_twitter', '<label for="store_twitter">'.__('Store Twitter url' , 'store_twitter' ).'</label>' , 'my_general_settings_fields_html_store_twitter', 'general');

    //Store pininterest url
    register_setting('general', 'store_pinterest', 'esc_attr');
    add_settings_field('store_pinterest', '<label for="store_pinterest">'.__('Store Pinterest Url' , 'store_pinterest' ).'</label>' , 'my_general_settings_fields_html_store_pinterest', 'general');
}

function my_general_settings_fields_html_support_email()
{
    $support_email = get_option( 'support_email', '' );
    echo '<input type="text" id="support_email" name="support_email" value="' . $support_email . '" />';

}
function my_general_settings_fields_html_support_phone()
{
     $support_phone = get_option( 'support_phone', '' );
    echo '<input type="text" id="support_phone" name="support_phone" value="' . $support_phone . '" />';
}
function my_general_settings_fields_html_store_team()
{
    $support_team = get_option( 'support_team', '' );
    echo '<input type="text" id="support_team" name="support_team" value="' . $support_team . '" />';
}

function my_general_settings_fields_html_store_fb()
{
    $store_fb = get_option( 'store_fb', '' );
    echo '<input type="text" id="store_fb" name="store_fb" value="' . $store_fb . '" />';
}

function my_general_settings_fields_html_store_twitter()
{
     $store_twitter = get_option( 'store_twitter', '' );
    echo '<input type="text" id="store_twitter" name="store_twitter" value="' . $store_twitter . '" />';
}
function my_general_settings_fields_html_store_pinterest()
{
    $store_pinterest = get_option( 'store_pinterest', '' );
    echo '<input type="text" id="store_pinterest" name="store_pinterest" value="' . $store_pinterest . '" />';
}

function get_variation_array($product){
	$color_variations = array();
	$available_variations = $product->get_available_variations();
	foreach($available_variations as $variations_arr){
		$color_variations[$variations_arr['attributes']['attribute_pa_color']] = $variations_arr['variation_id'] ;
	}

	return $color_variations;
}

#######--------------------Custom Funtions defined here -------------------------##############
##																							##
##############################################################################################
//---- function to fetch image on listing page according to filter color
function eshop_get_filter_image_custom($filter_id,$product_id){
	global $wpdb;
	$array_rev = array_reverse(explode(',',$filter_id));
	$filter_id = $array_rev[0];
	$post_name = eshop_get_post_name($product_id);
	$color_slug_qry = mysql_query("select slug from $wpdb->terms where term_id = '".$filter_id."'");
	$color_slug = mysql_fetch_row($color_slug_qry);
	$color_slug[0];
	$post_meta_detail = getpostmetadetail($color_slug[0]);
	$variation_id = eshop_fetch_product_varioation_id($product_id);
	foreach($variation_id as $key=> $att){
		foreach($post_meta_detail as $key =>$value){
			if($value[1]==$att){
			$imagearray=wp_get_attachment_image_src($value[0],'shop_catalog');
			$single_var=$att;
			}
		} 
	}
	$filter_img = array_values(array_unique($imagearray));
	$filter_img = $filter_img[0];
	//return $filter_img.'***'.$product_id.'***'.$product_id.'***'.$post_name.'*=*'.$single_var;
	return $filter_img;

	/*$array_rev = array_reverse(explode(',',$filter_id));
	$filter_id = $array_rev[0];
	$post_name = eshop_get_post_name($product_id);
	$color_slug_qry = mysql_query("select slug from $wpdb->terms where term_id = '".$filter_id."'");
	$color_slug = mysql_fetch_row($color_slug_qry);
	$color_slug[0];
	$post_meta_detail = getpostmetadetail($color_slug[0]);
	$variation_id = eshop_fetch_product_varioation_id($product_id);
	foreach($variation_id as $key=> $att)
	{
		foreach($post_meta_detail as $key =>$value)
		{
			if($value[1]==$att)
			{
				$imagearray=wp_get_attachment_image_src($value[0],array(300,300));
				$single_var=$att;
			}

		}

	}
	if(!empty($imagearray)){
		$filter_img = array_values(array_unique($imagearray));
		$filter_img = $filter_img[0];
	}
	return $filter_img;*/
}


//---- Function goes for fetch product swatch images -------
function getswatchimage_custom($productid,$count='0'){
$get_image_assoc = get_post_meta($productid,'_swatch_type_options',true);
if(!empty($get_image_assoc))
{
if($count == '1'){
	$swatchimage = sizeof($get_image_assoc['pa_color']['attributes'])-1;
}else{
			$upload_dir = wp_upload_dir();
			foreach($get_image_assoc['pa_color']['attributes'] as $key=>$value)
			{
			  $i=0;
					foreach($get_image_assoc['pa_color']['attributes'] as $keyattr=>$valueattr){
							$postimageid=$valueattr['image'];
							$imagedetail= get_post_meta($postimageid,'_wp_attachment_metadata');
							$swatchimage[$i]=$upload_dir['url'].'/'.$imagedetail[0]['sizes']['swatches_image_size']['file'];
									if($imagedetail == NULL){
											$imagedetail=get_post_meta($postimageid,'_wp_attached_file');
											$swatchimage[$i]=$upload_dir['url'].'/'.$imagedetail[0];
									}
							$i++;
					}
			}

}
}
return $swatchimage;
}

//--- Function fetch post meta details -----

function getpostmetadetail_custom($slug,$id='')
 {
	global $wpdb;
	if($id!='')
	{
		foreach($id as $allid){
			$idall.=$allid->ID.',';
		}
	  $idall=substr($idall,0,-1);
	  $querystr = "
		SELECT distinct post_id FROM $wpdb->postmeta WHERE $wpdb->postmeta.meta_key = 'attribute_pa_color' AND $wpdb->postmeta.meta_value = '".$slug."' AND post_id IN($idall)";
		//$query=mysql_query('select post_id from pinqckik_postmeta where meta_key="attribute_pa_color" and meta_value="'.$slug.'" and post_id IN('.$idall.')');
	}else{
	  $querystr = "
		SELECT distinct post_id FROM $wpdb->postmeta WHERE $wpdb->postmeta.meta_key = 'attribute_pa_color' AND $wpdb->postmeta.meta_value = '".$slug."'";
		//$query=mysql_query('select post_id from pinqckik_postmeta where meta_key="attribute_pa_color" and meta_value="'.$slug.'"');
	}
	$returnvalue=array();
	$i=0;
	$result = $wpdb->get_results($querystr, OBJECT);
	  if ($result):
	  global $post;

      foreach ($result as $result_data):
		$innerq_str = "
			SELECT distinct meta_value FROM $wpdb->postmeta WHERE $wpdb->postmeta.meta_key='_thumbnail_id' AND $wpdb->postmeta.post_id = '".$result_data->post_id."'";

		$resultdata = $wpdb->get_row($innerq_str, OBJECT);
		if ($resultdata):
			    $returnvalue[$i][0]=$resultdata->meta_value;
				$returnvalue[$i][1]=$result[0];
				$i++;
		endif;
		//$querydata=mysql_query('select meta_value from pinqckik_postmeta where meta_key="_thumbnail_id" and post_id="'.$result_data->post_id.'"');
		/*
		$resultdata=mysql_fetch_row($querydata);
		if($resultdata[0]>0){
				$returnvalue[$i][0]=$resultdata[0];
				$returnvalue[$i][1]=$result[0];
				$i++;
		}*/
	  endforeach;
	  endif;
	/*
	while($result=mysql_fetch_row($query)){
			$querydata=mysql_query('select meta_value from pinqckik_postmeta where meta_key="_thumbnail_id" and post_id="'.$result[0].'"');
			$resultdata=mysql_fetch_row($querydata);
			if($resultdata[0]>0){
					$returnvalue[$i][0]=$resultdata[0];
					$returnvalue[$i][1]=$result[0];
					$i++;
			}
	}*/
	return $returnvalue;
}
//--- Function goes for fetch single product swatch image ------
function get_single_product_swatch_image($productid, $term_arr) {
			
			//echo '<pre>'.$productid.'--'.print_r($term_arr);
			
			$attachmentss = get_posts( array(
				'post_type' 	=> 'product_variation',
				'post_parent' 	=> $productid,
				'orderby'	=> 'menu_order',
				'order' 	=> 'ASC',
				'posts_per_page'  => 50,
				'numberposts'     => 50,
			) );

			$thumbnailid = getpostmetadetail_custom($term_arr->slug,$attachmentss);
			foreach($thumbnailid as $arr){
				$imagepath[] = wp_get_attachment_image_src($arr[0],array(300,300));
			}
			$imagepath = wp_get_attachment_image_src($thumbnailid[0][0],array(300,300));
			//-- Return image path array ----
			return $imagepath;
		}

/*function eshop_get_product_size($product_id){
	$frt = array();
	$tt = wp_get_post_terms($product_id,'pa_size');//echo "<pre>";print_r($tt);
	if($tt != NULL){
	$size_name = eshopbox_get_product_terms( $product_id, 'pa_size');
	foreach($size_name as $key=>$sizename)
	{
	  $frt[] = $size_name[$key]->name;
	}
	   }
	echo $str = implode(", ",$frt);
}*/
function eshop_get_product_size($product_id){
	$frt = array();
	$tt = wp_get_post_terms($product_id,'pa_size');//echo "<pre>";print_r($tt);
	if($tt != NULL){
                $valsize=get_term_by( 'slug', $_product->variation_data['attribute_pa_size'], 'pa_size');
                //echo "<pre>";print_r($valsize);echo "</pre>";
	        $size_name = eshopbox_get_product_terms( $product_id, 'pa_size');
                //exit;
	        foreach($size_name as $key=>$valsize)
	        {
	          $frt[] = $size_name[$key]->name;
	        }

	        }
	echo $str = implode(", ",$frt);
}
//---- function for color swatch on product listing page
function eshop_get_swatchs_list($product, $single_col_array, $type='images'){
	$product_id = $product->id;
    $cc= array();
	$img_arr = array();
	$color_variations = array();
	$default_swatch_c = $single_col_array->slug;
	$post_name = $product->post->post_name;
	//$tt_color = wp_get_post_terms($product_id,'pa_color'); //-- Same as get_variation_array
	//$color_names_arr = eshopbox_get_product_terms( $product_id, 'pa_color');
	$color_names_arr =array_values(eshopbox_get_product_terms( $product_id, 'pa_color'));
	//-- Get all product variations---
	$available_variations = $product->get_available_variations();
	//-- Assign product color's variation id
	foreach($available_variations as $variations_arr){
		$color_variations[$variations_arr['attributes']['attribute_pa_color']] = $variations_arr['variation_id'] ;
	}

	if($color_names_arr != NULL)
	{
		$k = 0;
		$i = 0;
		$img_arr = array();
		$variant_size = array();
		//--array_values() returns all the values from the input array and indexes the array numerically.
		//$color_names_arr =array_values(eshopbox_get_product_terms( $product_id, 'pa_color'));
	//-- Get all prdocut color swatch images in array---
        $swatch=getswatchimage_custom($product_id);

		$attachmentss = get_posts( array(
			'post_type' 	=> 'product_variation',
			'post_parent' 	=> $product_id,
			'orderby'		=> 'menu_order',
			'order'			=> 'ASC',
			'posts_per_page'  => 50,
			'numberposts'     => 50,
		));

		foreach($color_names_arr as $keycolor=>$colorname)
		{
			$variant_size = array();
			$termids[] = $colorname->term_id;
			$thumbnailid=getpostmetadetail_custom($colorname->slug,$attachmentss);

			$imagepath = wp_get_attachment_image_src($thumbnailid[0][0],array(300,300));
		//--- Code fetch color size array
			foreach($thumbnailid as $key =>$value)
			{
				$postmetadetail=get_post_meta($value[1]);
				/*
				$variation_stock = $postmetadetail['_stock'][0];
				if($variation_stock > 0){
					$variant_size[] = $postmetadetail['attribute_pa_size'][0];//echo "<br/>";
				}*/
			}
			/*
			if(!empty($variant_size)){
				sort($variant_size);
				$variant_size = array_unique($variant_size);
			}*/
		//------
			$count_swatch = count($swatch);
			$img_new = $imagepath[0].'==='.$product_id."@@@";
			$postmetadetail=get_post_meta($thumbnailid[0][1]);
		//--- Insert selected class for default product color-------
			if($default_swatch_c == $colorname->slug &&($_GET['filter_color']=='')){
				$classselected = "class=selected";
			}elseif($filter_id == $termids[$keycolor]){
				$classselected = "class=selected";
			}else{
				$classselected='';
			}
		//-------------------------
		##-- Case goes for list product color variation images----
			if($type == 'images')
			{
				if($count_swatch>1){  ?>
				<li class="select-option swatch-wrapper product_<?php echo $product_id;?>" data-value="<?php //echo $key; ?>" rel="<?php echo $img_new; ?>***<?php echo $product_id; ?>***<?php echo $product_id; ?>***<?php echo $post_name; ?>*=*<?php echo $color[0]; ?>===<?php echo implode(',',$variant_size); ?>">
				<a href="<?php echo get_permalink($product_id).'?imageid='.$color_variations[$colorname->slug]; ?>" rev="<?php echo $single_col_array->slug; ?>" title="<?php echo $colorname->slug; ?>"<?php echo $classselected; ?> rel="<?php echo $color_variations[$colorname->slug]; ?>">
					<?php echo '<img class="wp-post-image swatch-photopa_color_swatches_id" width="25" height="15" alt="Thumbnail" src="'.$swatch[$keycolor].'" data-o_src="'.$swatch[$keycolor].'">'; ?>
				</a>
				</li>
				<?php
				}
			}
		}
	}
	
}

//if(!is_admin())
  //  include(get_template_directory().'/fileinclusion.php');
#####-------------------------------------------------------------------------------------------#####

function eshop_cutom_eshopbox_process_registration()
{
    global $eshopbox, $current_user;

	if ( ! empty( $_POST['register'] ) ) {

		$eshopbox->verify_nonce( 'register' );


		// Get fields
                $user_name = isset( $_POST['reg_dispname'] ) ? trim( $_POST['reg_dispname'] ) : '';
		$user_email = isset( $_POST['email'] ) ? trim( $_POST['email'] ) : '';
		$password   = isset( $_POST['password'] ) ? trim( $_POST['password'] ) : '';
		//$password2  = isset( $_POST['password2'] ) ? trim( $_POST['password2'] ) : '';
		//$password2 = $password;
		$user_email = apply_filters( 'user_registration_email', $user_email );

		if ( get_option( 'eshopbox_registration_email_for_username' ) == 'no' ) {

			$username 				= isset( $_POST['username'] ) ? trim( $_POST['username'] ) : '';
			$sanitized_user_login 	= sanitize_user( $username );

			// Check the username
			if ( $sanitized_user_login == '' ) {
				$eshopbox->add_error( '<strong>' . __( 'ERROR', 'eshopbox' ) . '</strong>: ' . __( 'Please enter a username.', 'eshopbox' ) );
			} elseif ( ! validate_username( $username ) ) {
				$eshopbox->add_error( '<strong>' . __( 'ERROR', 'eshopbox' ) . '</strong>: ' . __( 'This username is invalid because it uses illegal characters. Please enter a valid username.', 'eshopbox' ) );
				$sanitized_user_login = '';
			} elseif ( username_exists( $sanitized_user_login ) ) {
				$eshopbox->add_error( '<strong>' . __( 'ERROR', 'eshopbox' ) . '</strong>: ' . __( 'This Email Address is already registered, please choose another one.', 'eshopbox' ) );
			}

		} else {

			$username 				= $user_email;
			$sanitized_user_login 	= sanitize_user( $username );

		}
                // Check the user-fullname
                if($user_name =='')
                    {
                    $eshopbox->add_error(  __( 'Fullname is required.', 'eshopbox' ) );

                    }
		// Check the e-mail address
		if ( $user_email == '' ) {
			$eshopbox->add_error(  __( 'Please type your e-mail address.', 'eshopbox' ) );
		} elseif ( ! is_email( $user_email ) ) {
			$eshopbox->add_error( '<strong>' . __( 'ERROR', 'eshopbox' ) . '</strong>: ' . __( 'The email address isn&#8217;t correct.', 'eshopbox' ) );
			$user_email = '';
		} elseif ( email_exists( $user_email ) ) {
			$eshopbox->add_error( '<strong>' . __( 'ERROR', 'eshopbox' ) . '</strong>: ' . __( 'This email is already registered, please choose another one.', 'eshopbox' ) );
		}

		// Password
		if ( ! $password ) $eshopbox->add_error( __( 'Password is required.', 'eshopbox' ) );
		//if ( ! $password2 ) $eshopbox->add_error( __( 'Re-enter your password.', 'eshopbox' ) );
		//if ( $password != $password2 ) $eshopbox->add_error( __( 'Passwords do not match.', 'eshopbox' ) );

		// Spam trap
		if ( ! empty( $_POST['email_2'] ) )
			$eshopbox->add_error( __( 'Anti-spam field was filled in.', 'eshopbox' ) );

		// More error checking
		$reg_errors = new WP_Error();
		do_action( 'register_post', $sanitized_user_login, $user_email, $reg_errors );
		$reg_errors = apply_filters( 'registration_errors', $reg_errors, $sanitized_user_login, $user_email );

		if ( $reg_errors->get_error_code() ) {
			$eshopbox->add_error( $reg_errors->get_error_message() );
			return;
		}

		if ( $eshopbox->error_count() == 0 ) {

            $new_customer_data = array(
            	'user_login' => $sanitized_user_login,
            	'user_pass'  => $password,
            	'user_email' => $user_email,
            	'role'       => 'customer'
            );

            $user_id = wp_insert_user( apply_filters( 'eshopbox_new_customer_data', $new_customer_data ) );

            if ( is_wp_error($user_id) ) {
            	$eshopbox->add_error( '<strong>' . __( 'ERROR', 'eshopbox' ) . '</strong>: ' . __( 'Couldn&#8217;t register you&hellip; please contact us if you continue to have problems.', 'eshopbox' ) );
                return;
            }

            // Get user
            $current_user = get_user_by( 'id', $user_id );

            // Action
            do_action( 'eshopbox_created_customer', $user_id );

			// send the user a confirmation and their login details
			$mailer = $eshopbox->mailer();
			$mailer->customer_new_account( $user_id, $password );

            // set the WP login cookie
            $secure_cookie = is_ssl() ? true : false;
            wp_set_auth_cookie($user_id, true, $secure_cookie);

            // Redirect
            if ( wp_get_referer() ) {
				wp_safe_redirect( wp_get_referer() );
				exit;
			} else {
				wp_redirect(get_permalink(eshopbox_get_page_id('myaccount')));
				exit;
			}

		}

	}

}
remove_action( 'init', 'eshopbox_process_registration' );
add_action( 'init', 'eshop_cutom_eshopbox_process_registration' );

?>
