<?php
/**
 * @package Admin
 */

if ( !defined('WPSEO_VERSION') ) {
	header('HTTP/1.0 403 Forbidden');
	die;
}

global $wpseo_admin_pages;

$wpseo_admin_pages->admin_header( true, 'yoast_wpseo_permalinks_options', 'wpseo_permalinks' );
$content = $wpseo_admin_pages->checkbox( 'stripcategorybase', __( 'Strip the category base (usually <code>/category/</code>) from the category URL.', 'boxbeat-seo' ) );
$content .= $wpseo_admin_pages->checkbox( 'trailingslash', __( 'Enforce a trailing slash on all category and tag URL\'s', 'boxbeat-seo' ) );
$content .= '<p class="desc">' . __( 'If you choose a permalink for your posts with <code>.html</code>, or anything else but a / on the end, this will force BoxBeat to add a trailing slash to non-post pages nonetheless.', 'boxbeat-seo' ) . '</p>';

$content .= $wpseo_admin_pages->checkbox( 'cleanslugs', __( 'Remove stop words from slugs.', 'boxbeat-seo' ) );
$content .= '<p class="desc">' . __( 'This helps you to create cleaner URLs by automatically removing the stopwords from them.', 'boxbeat-seo' ) . '</p>';

$content .= $wpseo_admin_pages->checkbox( 'redirectattachment', __( 'Redirect attachment URL\'s to parent post URL.', 'boxbeat-seo' ) );
$content .= '<p class="desc">' . __( 'Attachments to posts are stored in the database as posts, this means they\'re accessible under their own URL\'s if you do not redirect them, enabling this will redirect them to the post they were attached to.', 'boxbeat-seo' ) . '</p>';

$content .= $wpseo_admin_pages->checkbox( 'cleanreplytocom', __( 'Remove the <code>?replytocom</code> variables.', 'boxbeat-seo' ) );
$content .= '<p class="desc">' . __( 'This prevents threaded replies from working when the user has JavaScript disabled, but on a large site can mean a <em>huge</em> improvement in crawl efficiency for search engines when you have a lot of comments.', 'boxbeat-seo' ) . '</p>';

$content .= $wpseo_admin_pages->checkbox( 'cleanpermalinks', __( 'Redirect ugly URL\'s to clean permalinks. (Not recommended in many cases!)', 'boxbeat-seo' ) );
$content .= '<p class="desc">' . __( 'People make mistakes in their links towards you sometimes, or unwanted parameters are added to the end of your URLs, this allows you to redirect them all away. Please note that while this is a feature that is actively maintained, it is known to break several plugins, and should for that reason be the first feature you disable when you encounter issues after installing this plugin.', 'boxbeat-seo' ) . '</p>';

$wpseo_admin_pages->postbox( 'permalinks', __( 'Permalink Settings', 'boxbeat-seo' ), $content );

$content = $wpseo_admin_pages->select( 'force_transport', __( 'Force Transport', 'boxbeat-seo' ), array( 'default' => __( 'Leave default', 'boxbeat-seo' ), 'http' => __( 'Force http', 'boxbeat-seo' ), 'https' => __( 'Force https', 'boxbeat-seo' ) ) );
$content .= '<p class="desc label">' . __( 'Force the canonical to either http or https, when your blog runs under both.', 'boxbeat-seo' ) . '</p>';

$wpseo_admin_pages->postbox( 'canonical', __( 'Canonical Settings', 'boxbeat-seo' ), $content );

$content = $wpseo_admin_pages->checkbox( 'cleanpermalink-googlesitesearch', __( 'Prevent cleaning out Google Site Search URL\'s.', 'boxbeat-seo' ) );
$content .= '<p class="desc">' . __( 'Google Site Search URL\'s look weird, and ugly, but if you\'re using Google Site Search, you probably do not want them cleaned out.', 'boxbeat-seo' ) . '</p>';

$content .= $wpseo_admin_pages->checkbox( 'cleanpermalink-googlecampaign', __( 'Prevent cleaning out Google Analytics Campaign Parameters.', 'boxbeat-seo' ) );
$content .= '<p class="desc">' . __( 'If you use Google Analytics campaign parameters starting with <code>?utm_</code>, check this box. You shouldn\'t use these btw, you should instead use the hash tagged version instead.', 'boxbeat-seo' ) . '</p>';

$content .= $wpseo_admin_pages->textinput( 'cleanpermalink-extravars', __( 'Other variables not to clean', 'boxbeat-seo' ) );
$content .= '<p class="desc">' . __( 'You might have extra variables you want to prevent from cleaning out, add them here, comma separarted.', 'boxbeat-seo' ) . '</p>';

$wpseo_admin_pages->postbox( 'cleanpermalinksdiv', __( 'Clean Permalink Settings', 'boxbeat-seo' ), $content );

$wpseo_admin_pages->admin_footer();