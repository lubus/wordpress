<?php
/**
 * @package Admin
 */

if ( !defined('WPSEO_VERSION') ) {
	header('HTTP/1.0 403 Forbidden');
	die;
}

global $wpseo_admin_pages;

$wpseo_admin_pages->admin_header( true, 'yoast_wpseo_internallinks_options', 'wpseo_internallinks' );

$content = $wpseo_admin_pages->checkbox( 'breadcrumbs-enable', __( 'Enable Breadcrumbs', 'boxbeat-seo' ) );
$content .= '<br/>';
$content .= $wpseo_admin_pages->textinput( 'breadcrumbs-sep', __( 'Separator between breadcrumbs', 'boxbeat-seo' ) );
$content .= $wpseo_admin_pages->textinput( 'breadcrumbs-home', __( 'Anchor text for the Homepage', 'boxbeat-seo' ) );
$content .= $wpseo_admin_pages->textinput( 'breadcrumbs-prefix', __( 'Prefix for the breadcrumb path', 'boxbeat-seo' ) );
$content .= $wpseo_admin_pages->textinput( 'breadcrumbs-archiveprefix', __( 'Prefix for Archive breadcrumbs', 'boxbeat-seo' ) );
$content .= $wpseo_admin_pages->textinput( 'breadcrumbs-searchprefix', __( 'Prefix for Search Page breadcrumbs', 'boxbeat-seo' ) );
$content .= $wpseo_admin_pages->textinput( 'breadcrumbs-404crumb', __( 'Breadcrumb for 404 Page', 'boxbeat-seo' ) );
$content .= $wpseo_admin_pages->checkbox( 'breadcrumbs-blog-remove', __( 'Remove Blog page from Breadcrumbs', 'boxbeat-seo' ) );
$content .= '<br/><br/>';
$content .= '<strong>' . __( 'Taxonomy to show in breadcrumbs for:', 'boxbeat-seo' ) . '</strong><br/>';
foreach ( get_post_types( array( 'public' => true ), 'objects' ) as $pt ) {
	$taxonomies = get_object_taxonomies( $pt->name, 'objects' );
	if ( count( $taxonomies ) > 0 ) {
		$values = array( 0 => __( 'None', 'boxbeat-seo' ) );
		foreach ( $taxonomies as $tax ) {
			$values[ $tax->name ] = $tax->labels->singular_name;
		}
		$content .= $wpseo_admin_pages->select( 'post_types-' . $pt->name . '-maintax', $pt->labels->name, $values );
	}
}
$content .= '<br/>';

$content .= '<strong>' . __( 'Post type archive to show in breadcrumbs for:', 'boxbeat-seo' ) . '</strong><br/>';
foreach ( get_taxonomies( array( 'public'=> true, '_builtin' => false ), 'objects' ) as $tax ) {
	$values = array( '' => __( 'None', 'boxbeat-seo' ) );
	if ( get_option( 'show_on_front' ) == 'page' )
		$values[ 'post' ] = __( 'Blog', 'boxbeat-seo' );

	foreach ( get_post_types( array( 'public' => true ), 'objects' ) as $pt ) {
		if ( $pt->has_archive )
			$values[ $pt->name ] = $pt->labels->name;
	}
	$content .= $wpseo_admin_pages->select( 'taxonomy-' . $tax->name . '-ptparent', $tax->labels->singular_name, $values );
}

$content .= $wpseo_admin_pages->checkbox( 'breadcrumbs-boldlast', __( 'Bold the last page in the breadcrumb', 'boxbeat-seo' ) );

$content .= '<br class="clear"/>';
$content .= '<h4>' . __( 'How to insert breadcrumbs in your theme', 'boxbeat-seo' ) . '</h4>';
$content .= '<p>' . __( 'Usage of this breadcrumbs feature is explained <a href="http://yoast.com/boxbeat/breadcrumbs/">here</a>. For the more code savvy, insert this in your theme:', 'boxbeat-seo' ) . '</p>';
$content .= '<pre>&lt;?php if ( function_exists(&#x27;yoast_breadcrumb&#x27;) ) {
yoast_breadcrumb(&#x27;&lt;p id=&quot;breadcrumbs&quot;&gt;&#x27;,&#x27;&lt;/p&gt;&#x27;);
} ?&gt;</pre>';
$wpseo_admin_pages->postbox( 'internallinks', __( 'Breadcrumbs Settings', 'boxbeat-seo' ), $content );

$wpseo_admin_pages->admin_footer();