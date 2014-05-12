<?php
/**
 * @package Admin
 */

if ( !defined( 'WPSEO_VERSION' ) ) {
	header( 'HTTP/1.0 403 Forbidden' );
	die;
}

global $wpseo_admin_pages;

$options = get_option( 'wpseo_social' );

$fbconnect = '<p><strong>' . __( 'Facebook Insights and Admins', 'boxbeat-seo' ) . '</strong><br>';
$fbconnect .= sprintf( __( 'To be able to access your <a href="%s">Facebook Insights</a> for your site, you need to specify a Facebook Admin. This can be a user, but if you have an app for your site, you could use that. For most people a user will be "good enough" though.', 'boxbeat-seo' ), 'https://www.facebook.com/insights' ) . '</p>';

$error    = false;
$clearall = false;

if ( isset( $_GET['delfbadmin'] ) ) {
	if ( wp_verify_nonce( $_GET['nonce'], 'delfbadmin' ) != 1 )
		die( "I don't think that's really nice of you!." );
	$id = $_GET['delfbadmin'];
	if ( isset( $options['fb_admins'][$id] ) ) {
		$fbadmin = $options['fb_admins'][$id]['name'];
		unset( $options['fb_admins'][$id] );
		update_option( 'wpseo_social', $options );
		add_settings_error( 'yoast_wpseo_social_options', 'success', sprintf( __( 'Successfully removed admin %s', 'boxbeat-seo' ), $fbadmin ), 'updated' );
		$error = true;
	}
}

if ( isset( $_GET['fbclearall'] ) ) {
	if ( wp_verify_nonce( $_GET['nonce'], 'fbclearall' ) != 1 )
		die( "I don't think that's really nice of you!." );
	unset( $options['fb_admins'], $options['fbapps'], $options['fbadminapp'], $options['fbadminpage'] );
	update_option( 'wpseo_social', $options );
	add_settings_error( 'yoast_wpseo_social_options', 'success', __( 'Successfully cleared all Facebook Data', 'boxbeat-seo' ), 'updated' );
}

if ( !isset( $options['fbconnectkey'] ) || empty( $options['fbconnectkey'] ) ) {
	$options['fbconnectkey'] = md5( get_bloginfo( 'url' ) . rand() );
	update_option( 'wpseo_social', $options );
}

if ( isset( $_GET['key'] ) && $_GET['key'] == $options['fbconnectkey'] ) {
	if ( isset( $_GET['userid'] ) ) {
		if ( !isset( $options['fb_admins'] ) || !is_array( $options['fb_admins'] ) )
			$options['fb_admins'] = array();
		$user_id                                = $_GET['userid'];
		$options['fb_admins'][$user_id]['name'] = urldecode( $_GET['userrealname'] );
		$options['fb_admins'][$user_id]['link'] = urldecode( $_GET['link'] );
		update_option( 'wpseo_social', $options );
		add_settings_error( 'yoast_wpseo_social_options', 'success', sprintf( __( 'Successfully added %s as a Facebook Admin!', 'boxbeat-seo' ), '<a href="' . esc_url( $options['fb_admins'][$user_id]['link'] ) . '">' . esc_html( $options['fb_admins'][$user_id]['name'] ) . '</a>' ), 'updated' );
	} else if ( isset( $_GET['apps'] ) ) {
		$apps              = json_decode( stripslashes( $_GET['apps'] ) );
		$options['fbapps'] = array( '0' => __( 'Do not use a Facebook App as Admin', 'boxbeat-seo' ) );
		foreach ( $apps as $app ) {
			$options['fbapps'][$app->app_id] = $app->display_name;
		}
		update_option( 'wpseo_social', $options );
		add_settings_error( 'yoast_wpseo_social_options', 'success', __( 'Successfully retrieved your apps from Facebook, now select an app to use as admin.', 'boxbeat-seo' ), 'updated' );
	}
	$error = true;
}

$options = get_option( 'wpseo_social' );

if ( isset( $options['fb_admins'] ) && is_array( $options['fb_admins'] ) ) {
	foreach ( $options['fb_admins'] as $id => $admin ) {
		$fbconnect .= '<input type="hidden" name="wpseo_social[fb_admins][' . esc_attr( $id ) . ']" value="' . esc_attr( $admin['link'] ) . '"/>';
	}
	$clearall = true;
}

if ( isset( $options['fbapps'] ) && is_array( $options['fbapps'] ) ) {
	foreach ( $options['fbapps'] as $id => $page ) {
		$fbconnect .= '<input type="hidden" name="wpseo_social[fbapps][' . esc_attr( $id ) . ']" value="' . esc_attr( $page ) . '"/>';
	}
	$clearall = true;
}

$app_button_text = __( 'Use a Facebook App as Admin', 'boxbeat-seo' );
if ( isset( $options['fbapps'] ) && is_array( $options['fbapps'] ) ) {
	$fbconnect .= '<p>' . __( 'Select an app to use as Facebook admin:', 'boxbeat-seo' ) . '</p>';
	$fbconnect .= '<select name="wpseo_social[fbadminapp]" id="fbadminapp">';

	if ( !isset( $options['fbadminapp'] ) )
		$options['fbadminapp'] = 0;

	foreach ( $options['fbapps'] as $id => $app ) {
		$sel = '';

		if ( $id == $options['fbadminapp'] )
			$sel = 'selected="selected"';
		$fbconnect .= '<option ' . $sel . ' value="' . esc_attr( $id ) . '">' . esc_attr( $app ) . '</option>';
	}
	$fbconnect .= '</select><div class="clear"></div><br/>';
	$app_button_text = __( 'Update Facebook Apps', 'boxbeat-seo' );
}

if ( !isset( $options['fbadminapp'] ) || $options['fbadminapp'] == 0 ) {
	$button_text = __( 'Add Facebook Admin', 'boxbeat-seo' );
	$primary     = true;
	if ( isset( $options['fb_admins'] ) && is_array( $options['fb_admins'] ) && count( $options['fb_admins'] ) > 0 ) {
		$fbconnect .= '<p>' . __( 'Currently connected Facebook admins:', 'boxbeat-seo' ) . '</p>';
		$fbconnect .= '<ul>';
		$nonce = wp_create_nonce( 'delfbadmin' );

		foreach ( $options['fb_admins'] as $admin_id => $admin ) {
			$admin_id = esc_attr( $admin_id );
			$fbconnect .= '<li><a href="' . esc_url( $admin['link'] ) . '">' . esc_html( $admin['name'] ) . '</a> - <strong><a  href="' . admin_url( 'admin.php?page=wpseo_social&delfbadmin=' . $admin_id . '&nonce=' . $nonce ) . '">X</a></strong></li>';
			$fbconnect .= '<input type="hidden" name="wpseo_social[fb_admins][' . $admin_id . '][link]" value="' . esc_attr( $admin['link'] ) . '"/>';
			$fbconnect .= '<input type="hidden" name="wpseo_social[fb_admins][' . $admin_id . '][name]" value="' . esc_attr( $admin['name'] ) . '"/>';
		}
		$fbconnect .= '</ul>';
		$button_text = __( 'Add Another Facebook Admin', 'boxbeat-seo' );
		$primary     = false;
	}
	$but_primary = '';
	if ( $primary )
		$but_primary = '-primary';
	$fbconnect .= '<p><a class="button' . esc_attr( $but_primary ) . '" href="https://yoast.com/fb-connect/?key=' . $options['fbconnectkey'] . '&redirect=' . urlencode( admin_url( 'admin.php?page=wpseo_social' ) ) . '">' . $button_text . '</a></p>';
}

$fbconnect .= '<a class="button" href="https://yoast.com/fb-connect/?key=' . esc_url( $options['fbconnectkey'] ) . '&type=app&redirect=' . urlencode( admin_url( 'admin.php?page=wpseo_social' ) ) . '">' . esc_html( $app_button_text ) . '</a> ';
if ( $clearall ) {
	$fbconnect .= '<a class="button" href="' . admin_url( 'admin.php?page=wpseo_social&nonce=' . wp_create_nonce( 'fbclearall' ) . '&fbclearall=true' ) . '">' . __( 'Clear all Facebook Data', 'boxbeat-seo' ) . '</a> ';
}
$fbconnect .= '</p>';

$wpseo_admin_pages->admin_header( true, 'yoast_wpseo_social_options', 'wpseo_social' );

if ( $error )
	settings_errors();
?>

<h2 class="nav-tab-wrapper" id="wpseo-tabs">
	<a class="nav-tab nav-tab-active" id="facebook-tab" href="#top#facebook"><?php _e( 'Facebook', 'boxbeat-seo' );?></a>
	<a class="nav-tab" id="twitterbox-tab" href="#top#twitterbox"><?php _e( 'Twitter', 'boxbeat-seo' );?></a>
	<a class="nav-tab" id="google-tab" href="#top#google"><?php _e( 'Google+', 'boxbeat-seo' );?></a>
</h2>

<div id="facebook" class="wpseotab">
	<?php
		echo '<p>';
		echo $wpseo_admin_pages->checkbox( 'opengraph', '<label for="opengraph">' . __( 'Add Open Graph meta data', 'boxbeat-seo' ) . '</label>' );
		echo '</p>';
		echo'<p class="desc">' . __( 'Add Open Graph meta data to your site\'s <code>&lt;head&gt;</code> section. You can specify some of the ID\'s that are sometimes needed below:', 'boxbeat-seo' ) . '</p>';
		echo $fbconnect;
		echo $wpseo_admin_pages->textinput( 'facebook_site', __( 'Facebook Page URL', 'boxbeat-seo' ) );
		echo '<h4>' . __( 'Frontpage settings', 'boxbeat-seo' ) . '</h4>';
		echo $wpseo_admin_pages->textinput( 'og_frontpage_image', __( 'Image URL', 'boxbeat-seo' ) );
		echo $wpseo_admin_pages->textinput( 'og_frontpage_desc', __( 'Description', 'boxbeat-seo' ) );
		echo '<p class="desc label">' . __( 'These are the image and description used in the Open Graph meta tags on the frontpage of your site.', 'boxbeat-seo' ) . '</p>';
		echo '<h4>' . __( 'Default settings', 'boxbeat-seo' ) . '</h4>';
		echo $wpseo_admin_pages->textinput( 'og_default_image', __( 'Image URL', 'boxbeat-seo' ) );
		echo '<p class="desc label">' . __( 'This image is used if the post/page being shared does not contain any images.', 'boxbeat-seo' ) . '</p>';
		do_action('wpseo_admin_opengraph_section');
	?>
</div>

<div id="twitterbox" class="wpseotab">
	<?php
		echo '<p>';
		echo $wpseo_admin_pages->checkbox( 'twitter', '<label for="twitter">' . __( 'Add Twitter card meta data', 'boxbeat-seo' ) . '</label>' );
		echo '</p>';
		echo'<p class="desc">' . __( 'Add Twitter card meta data to your site\'s <code>&lt;head&gt;</code> section.', 'boxbeat-seo' ) . '</p>';
		echo $wpseo_admin_pages->textinput( 'twitter_site', __( 'Site Twitter Username', 'boxbeat-seo' ) );
		do_action('wpseo_admin_twitter_section');
	?>
</div>

<div id="google" class="wpseotab">
	<?php
		// echo '<h2>' . __( 'Author metadata', 'boxbeat-seo' ) . '</h2>';
		echo '<label class="select" for="">' . __( 'Author for homepage', 'boxbeat-seo' ) . ':</label>';
		wp_dropdown_users(
			array(
				'show_option_none' => __( "Don't show", 'boxbeat-seo' ),
				'name' => 'wpseo_social[plus-author]',
				'class' => 'select',
				'selected' => ( isset( $options[ 'plus-author' ] ) ) ? $options[ 'plus-author' ] : '',
				'include_selected' => true,
				'who' => 'authors'
			)
		);
		echo '<p class="desc label">' . __( 'Choose the user that should be used for the <code>rel="author"</code> on the blog homepage. Make sure the user has filled out his/her Google+ profile link on their profile page.', 'boxbeat-seo' ) . '</p>';
		echo $wpseo_admin_pages->textinput( 'plus-publisher', __( 'Google Publisher Page', 'boxbeat-seo' ) );
		echo '<p class="desc label">' . __( 'If you have a Google+ page for your business, add that URL here and link it on your Google+ page\'s about page.', 'boxbeat-seo' ) . '</p>';
		do_action('wpseo_admin_googleplus_section');
	?>
</div>

<?php
$wpseo_admin_pages->admin_footer();