<?php
/**
 * @package Postmark
 * @version 1.0
 */
/*
Plugin Name: Postmark
Description: Uses the Postmark API to send email
Version: 1.0
Author: Boxbeat Technologies Pvt Ltd
Author URI: http://theboxbeat.com/
*/

// Define
define('POSTMARK_ENDPOINT', 'http://api.postmarkapp.com/email');

// Admin Functionality
add_action('admin_menu', 'pm_admin_menu'); // Add Postmark to Settings

function pm_admin_menu() {
	add_options_page('Postmark', 'Postmark', 'manage_options', 'pm_admin', 'pm_admin_options');
}

function pm_admin_action_links($links, $file) {
    static $pm_plugin;
    if (!$pm_plugin) {
        $pm_plugin = plugin_basename(__FILE__);
    }
    if ($file == $pm_plugin) {
        $settings_link = '<a href="options-general.php?page=pm_admin">Settings</a>';
        array_unshift($links, $settings_link);
    }
    return $links;
}

add_filter('plugin_action_links', 'pm_admin_action_links', 10, 2);


function pm_admin_options() {
	if($_POST['submit']) {
		$pm_enabled = $_POST['pm_enabled'];
		if($pm_enabled):
			$pm_enabled = 1;
		else:
			$pm_enabled = 0;
		endif;

		$api_key = $_POST['pm_api_key'];
		$sender_email = $_POST['pm_sender_address'];

		$pm_poweredby = $_POST['pm_poweredby'];
		if($pm_poweredby):
			$pm_poweredby = 1;
		else:
			$pm_poweredby = 0;
		endif;

		update_option('postmark_enabled', $pm_enabled);
		update_option('postmark_api_key', $api_key);
		update_option('postmark_sender_address', $sender_email);

		$msg_updated = "Postmark settings have been saved.";
	}
	?>

	<script type="text/javascript" >
	jQuery(document).ready(function($) {

		$("#test-form").submit(function(e){
			e.preventDefault();
			var $this = $(this);
			var send_to = $('#pm_test_address').val();

			$("#test-form .button-primary").val("Sending…");
			$.post(ajaxurl, {email: send_to, action:$this.attr("action")}, function(data){
				$("#test-form .button-primary").val(data);
			});
		});

	});
	</script>

	<div class="wrap">

		<?php if($msg_updated): ?><div class="updated"><p><?php echo $msg_updated; ?></p></div><?php endif; ?>
		<?php if($msg_error): ?><div class="error"><p><?php echo $msg_error; ?></p></div><?php endif; ?>

		<div id="icon-tools" class="icon32"></div>
		<h2>Easy Postmark Integration</h2>

		<h3>Your Postmark Settings</h3>
		<form method="post" action="options-general.php?page=pm_admin">
			<table class="form-table">
			<tbody>
				<tr>
					<th><label for="pm_enabled">Send using Postmark</label></th>
					<td><input name="pm_enabled" id="" type="checkbox" value="1"<?php if(get_option('postmark_enabled') == 1): echo ' checked="checked"'; endif; ?>/> <span style="font-size:11px;">Sends emails sent using wp_mail via Postmark.</span></td>
				</tr>
				<tr>
					<th><label for="pm_api_key">Postmark API Key</label></th>
					<td><input name="pm_api_key" id="" type="text" value="<?php echo get_option('postmark_api_key'); ?>" class="regular-text"/> <br/><span style="font-size:11px;">Your API key is available in the <strong>credentials</strong> screen of your Postmark server. <a href="https://postmarkapp.com/servers/">Create a new server in Postmark</a>.</span></td>
				</tr>
				<tr>
					<th><label for="pm_sender_address">Sender Email Address</label></th>
					<td><input name="pm_sender_address" id="" type="text" value="<?php echo get_option('postmark_sender_address'); ?>" class="regular-text"/> <br/><span style="font-size:11px;">This email needs to be one of your <strong>verified sender signatures</strong>. <br/>It will appear as the "from" email on all outbound messages. <a href="https://postmarkapp.com/signatures">Set one up in Postmark</a>.</span></td>
				</tr>
			</tbody>
			</table>
			<div class="submit">
				<input type="submit" name="submit" value="Save" class="button-primary" />
			</div>
		</form>

		<br />

		<h3>Test Postmark Sending</h3>
		<form method="post" id="test-form" action="pm_admin_test">
			<table class="form-table">
			<tbody>
				<tr>
					<th><label for="pm_test_address">Send a Test Email To</label></th>
					<td> <input name="pm_test_address" id="pm_test_address" type="text" value="<?php echo get_option('postmark_sender_address'); ?>" class="regular-text"/> <input type="submit" name="submit" value="Send Test Email" class="button-primary" /></td>
				</tr>
			</tbody>
			</table>
		</form>
	</div>

<?php
}

add_action('wp_ajax_pm_admin_test', 'pm_admin_test_ajax');
function pm_admin_test_ajax() {
	$response = pm_send_test();

	echo $response;

	die();
}

// End Admin Functionality




// Override wp_mail() if postmark enabled
if(get_option('postmark_enabled') == 1){
	if (!function_exists("wp_mail")){
		function wp_mail( $to, $subject, $message, $headers = '', $attachments = array(),$from_name='',$from_email='') {

			// Define Headers
			$postmark_headers = array(
				'Accept: application/json',
                'Content-Type: application/json',
                'X-Postmark-Server-Token: ' . get_option('postmark_api_key')
			);

			// Send Email
			if(!is_array($to)){
				$recipients = explode(",", $to);
			} else {
				$recipients = $to;
			}

			foreach($recipients as $recipient){
			  
			  // Parse the headers
			  $headers_arr = explode("\r\n", $headers);
			  if ($headers_arr) {
			    foreach ($headers_arr as $single_header) {
			      $single_header_arr = explode(': ', $single_header);
			      $header_name = trim($single_header_arr[0]);
			      $header_value = trim($single_header_arr[1]);
			      
			      $orig_headers[$header_name] = $header_value;
			    }
			  }
			  
				// Construct Message
				$email = array();
				$email['To'] = $recipient;
				$email['From'] = 'Phive rivers<'.get_option('postmark_sender_address').'>';
                                 if($from_email!='' && $from_name!=''){
                                    $email['From']=$from_name.'<'.$from_email.'>'; 
                                }
			  // Set the Reply-To to the original From address
			  if ($orig_headers['From']) {
			    $email['ReplyTo'] = $orig_headers['From'];
			  }

	    	$email['Subject'] = $subject;
	    	$email['TextBody'] = $message;

	    	if(strpos($headers, "text/html")){
		    	$email['HtmlBody'] = $message;
	    	}
	    		    	

        $response = pm_send_mail($postmark_headers, $email);
			}
			return $response;
		}
	}
}


function pm_send_test(){
	$email_address = $_POST['email'];

	// Define Headers
	$postmark_headers = array(
		'Accept: application/json',
        'Content-Type: application/json',
        'X-Postmark-Server-Token: ' . get_option('postmark_api_key')
	);

	$message = 'This is a test email sent via Postmark from '.get_bloginfo('name').'.';

	$email = array();
	$email['To'] = $email_address;
	$email['From'] = get_option('postmark_sender_address');
    $email['Subject'] = get_bloginfo('name').' Postmark Test';
    $email['TextBody'] = $message;

    $response = pm_send_mail($postmark_headers, $email);

    if ($response === false){
    	return "Test Failed with Error ".curl_error($curl);
    } else {
    	return "Test Sent";
   	}

    die();
}


function pm_send_mail($headers, $email){
	$curl = curl_init();
    curl_setopt_array($curl, array(
            CURLOPT_URL => POSTMARK_ENDPOINT,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POSTFIELDS => json_encode($email),
            CURLOPT_RETURNTRANSFER => true
    ));

    $response = curl_exec($curl);

    if ($response === false){
    	return false;
    } else {
    	return true;
    }
}

?>