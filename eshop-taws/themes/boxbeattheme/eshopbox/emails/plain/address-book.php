<?php
/**
 * Template Name:addessbook
 *
 * 
 * 
 * 
 */

get_header(); ?>

	<div id="primary" class="site-content">
		
<div class="my_acoount_wrap">
<div class="my_account_top">
	<span class="title_email_wrap">
		<div class="title_email">
			<h1>Address Book</h1>
			<?php
			    $current_user = wp_get_current_user();
			    /**
			     * @example Safe usage: $current_user = wp_get_current_user();
			     * if ( !($current_user instanceof WP_User) )
			     *     return;
			     */
                             
			    	echo '<h4>' .$current_user->user_firstname .' '.$current_user->user_lastname .'</h4>';
				    echo $current_user->user_email;
				  
?>
		</div>
	</span>
	<!-- title_email -->
	<span class="count_total_wrap">
		<div class="count_total">
			<div class="cart_header">
						<?php global $eshopbox; ?>
							 <p>You have <?php echo sprintf(_n('%d', '%d', $eshopbox->cart->cart_contents_count, 'woothemes'), $eshopbox->cart->cart_contents_count);?> items in your shopping bag.</p>
					</div>
					<?php echo "<span class='subtotal_span'>subtotal : " .$eshopbox->cart->get_cart_subtotal();?></span>
					<?php echo "<span class='ordertotal_span'>Ordertotal : " .$eshopbox->cart->get_cart_subtotal( true ); ?></span>
			
			<a href=" <?php echo get_permalink( eshopbox_get_page_id( 'cart' ) )?>">View Shopping Bag</a>
		</div>
	</span>
	<!-- count_total -->

	<span class="help_support_wrap">
		<div class="help_support">
			<h1>need help</h1>
			<h3 id="tel_num">09854875486</h3>
			<h3 id="email_num">support@theethnicroute.com</h3>
		</div>
	</span>
	<!-- help_support -->
</div>
<!-- my_account_top -->
</div>
<!-- my_account_top_warp -->
<div class="sider_bar_account_bot_wrap">
<div class="sider_bar_account">
<?php dynamic_sidebar('sidebar-9'); ?>
</div>


<div class="right_content_sidebar">
<?php if (is_page(1055)) { ?>
	<div class="re_cls3">
			<h3>Address Book</h3>
	</div>
<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly



global $eshopbox;



$customer_id = get_current_user_id();



if ( get_option('eshopbox_ship_to_billing_address_only') == 'no' ) {

	$page_title = apply_filters( 'eshopbox_my_account_my_address_title', __( 'My Addresses', 'eshopbox' ) );

	$get_addresses    = array(

		'billing' => __( 'Billing Address', 'eshopbox' ),

		'shipping' => __( 'Shipping Address', 'eshopbox' )

	);

} else {

	$page_title = apply_filters( 'eshopbox_my_account_my_address_title', __( 'My Address', 'eshopbox' ) );

	$get_addresses    = array(

		'billing' =>  __( 'Billing Address', 'eshopbox' )

	);

}



$col = 1;

?>

<?php if ( get_option('eshopbox_ship_to_billing_address_only') == 'no' ) echo '<div class="col2-set addresses">'; ?>



<?php foreach ( $get_addresses as $name => $title ) : ?>



	<div class="col-<?php echo ( ( $col = $col * -1 ) < 0 ) ? 1 : 2; ?> address">

		<header class="title">

			<h3><?php echo $title; ?></h3>

		</header>

		<address>

			<?php

				$address = array(

					'first_name' 	=> get_user_meta( $customer_id, $name . '_first_name', true ),

					'last_name'		=> get_user_meta( $customer_id, $name . '_last_name', true ),

					'company'		=> get_user_meta( $customer_id, $name . '_company', true ),

					'address_1'		=> get_user_meta( $customer_id, $name . '_address_1', true ),

					'address_2'		=> get_user_meta( $customer_id, $name . '_address_2', true ),

					'city'			=> get_user_meta( $customer_id, $name . '_city', true ),

					'state'			=> get_user_meta( $customer_id, $name . '_state', true ),

					'postcode'		=> get_user_meta( $customer_id, $name . '_postcode', true ),

					'country'		=> get_user_meta( $customer_id, $name . '_country', true )

				);



				$formatted_address = $eshopbox->countries->get_formatted_address( $address );



				if ( ! $formatted_address )

					_e( 'You have not set up this type of address yet.', 'eshopbox' );

				else

					echo $formatted_address;

			?>

		</address>
<a href="<?php echo esc_url( add_query_arg('address', $name, get_permalink(eshopbox_get_page_id( 'edit_address' ) ) ) ); ?>" class="edit"><?php _e( 'Edit ', 'eshopbox' ); ?><?php echo $title;?></a>
	</div>



<?php endforeach; ?>



<?php if ( get_option('eshopbox_ship_to_billing_address_only') == 'no' ) echo '</div>'; ?>
		</div><!-- #content -->
	</div><!-- #primary -->

<?php } ?>
<?php if (is_page(1058)) { ?>
	<div class="re_cls4">
			<h3>MY PROFILE</h3>
	</div>	
<script type="text/javascript">
	/*jQuery(function(e) {
		jQuery('#container-5 ul').tabs({ fxSlide: true, fxFade: true, fxSpeed: 'normal' });
	});*/

	 jQuery(document).ready(function(){
                /*
		jQuery('.cancel_changes').click(function(){
			disp_type_val = jQuery(this).attr("id"); //dispaddr_1
			list_address = disp_type_val.split("_");
			//alert(list_address);
			jQuery(".column-"+list_address[1]).hide();
			jQuery(".col-"+list_address[1]).show();
			return false;
		});

		jQuery('div.address a.edit').click(function(){
			jQuery(this).parent('.address').hide();
			var position_no = jQuery(this).parent('.address').attr('class');
			var f_id = position_no.split(' ');
			var n_id = f_id[0].split('-');
			jQuery('div.column-'+n_id[1]).show();
			return false;
		});

		jQuery('ul.ui-tabs-nav li a').click(function(e){
			jQuery('ul.ui-tabs-nav li').removeClass('ui-tabs-selected');
			jQuery(this).parent('li').addClass('ui-tabs-selected');
			var l_href = jQuery(this).attr('href');
			href_arr = l_href.split('_');
			//alert(l_href);
			jQuery('.ui-tabs-container').hide();
			jQuery(href_arr[0]+'-'+href_arr[1]).show();
			//window.location.hash = l_href;
			//e.preventDefault();
		});
                */
              //--- Code goes for edit account details form----
		jQuery('#edit_profile').click(function(){
                    jQuery('div.first-form').hide('slide');
                    jQuery('#form-edit-account-details').show('slide');
		});

		jQuery('.cancel_form_first').click(function(){
			jQuery('.f1_errmsg').html('').css({visiblity:"hidden"});
			//jQuery('.succmsg').html('').css({visiblity:"hidden"});
			jQuery('div.edit_current_usr').hide('slide');
			jQuery('div.first-form').show('slide');
			jQuery('#form-edit-account-details').hide();
			return false;
		});

		//--- Form submit for edit user first name --
		jQuery('form#form-edit-account-details').submit(function(e){
			e.preventDefault();
			var f_name = jQuery.trim(jQuery('#f_name').val());
			var email = jQuery.trim(jQuery('#email').val());
			var phone = jQuery.trim(jQuery('#phone').val());
			
			if(f_name == '' || email == '' || phone == ''){
				jQuery('.f1_errmsg').html('Please enter complete form details').css({visibility:"visible"});
			}else{
				jQuery.ajax({
					type : 'POST',
					url  : /ajaxvals.php/,
					data : 'action=update_name&f_name='+f_name+'&email='+email+'&phone='+phone,
					datatype: "json",
					success: function (data){
						if(data == 0){
                                                    jQuery('.f1_errmsg').html('Please enter complete form details').css({visibility:"visible"});
						}else{
                                                    jQuery("#form-edit-account-details").hide();
                                                    jQuery('#f_name').val(f_name);
                                                    jQuery('#email').val(email);
                                                    jQuery('#phone').val(phone);

                                                    jQuery('span.full_name').html(f_name);
                                                    jQuery('span.email').html(email);
                                                    jQuery('span.phone').html(phone);

                                                    //jQuery('.succmsg').html('Full Name updated successfully').css({visibility:"visible"});
                                                    jQuery('div.first-form').show();
						}
					}

				});
			}
		});

		jQuery('.txtinput').focus(function() {
			jQuery('.errmsg').html('').css({visiblity:"hidden"});
		});
});

        </script>
			<div class="my_profile">
                        <div class="first-form">
			<?php
                        $phone = get_user_meta($current_user->ID, 'phone', true);
                        echo '<div><span class="lebel_text"> Name</span><span class="full_name">' .$current_user->display_name .' '.$current_user->user_lastname .'</span></div>';
                        echo '<div><span class="lebel_text"> Email</span> <span class="email">' .$current_user->user_email .'</span></div>';?>
                        <div><span class="lebel_text">Phone</span> <span class="phone"><?php echo $phone; ?></span></div>
                        <input type="submit" value="EDIT PROFILE" id="edit_profile">
                        </div>

                        <form name="edit-user-name" class="form_1" id="form-edit-account-details" method="post" style="display:none;">
                            <p class="f1_errmsg" style="visiblity:hidden"></p>
                            <div>
                            <span class="lebel_text"> Name</span>
                                <input type="text" name="f_name" class="txtinput" id="f_name" value="<?php echo $current_user->display_name; ?>">
                            </div>
                            <div><span class="lebel_text"> Email</span>
                                <input type="text" name="email" class="txtinput" id="email" value="<?php echo $current_user->user_email; ?>">
                            </div>
                            <div><span class="lebel_text"> Phone</span>
                                <input type="text" name="phone" class="txtinput" id="phone" value="<?php echo $phone;  ?>">
                            </div>
                            <input type="submit" class="button cancel_form_first" name="cancel" value="<?php _e( 'Cancel', 'eshopbox' ); ?>" />
                            <input type="submit" class="button" name="change_fullname" value="<?php _e( 'Save Changes', 'eshopbox' ); ?>" />
                        </form>

			<div id="fragment-13" class="ui-tabs-container ui-tabs-hide">
			<?php //$eshopbox->show_messages(); ?>
                        <div class="take_one">
			<div class="edit_current_usr" style="display:none;">			
			<p class="errmsg" style="visiblity:hidden"></p>
			<!-- Form for edit user full name -->
				
			<!-- Form for edit user email -->
				<form name="change-password" class="form_2" id="change-password" method="post" style="display:none;">
				<p class="form-row form-row-first">
				<label for="password_0"><?php _e( 'Current password', 'eshopbox' ); ?> <span class="required">*</span></label>
				<input type="password" class="input-text" name="password_0" id="password_0" />
				</p>
				<p class="form-row form-row-first">
				<label for="password_1"><?php _e( 'New password', 'eshopbox' ); ?> <span class="required">*</span></label>
				<input type="password" class="input-text" name="password_1" id="password_1" />
				</p>
				<p class="form-row form-row-last">
				<label for="password_2"><?php _e( 'Confirm password', 'eshopbox' ); ?> <span class="required">*</span></label>
				<input type="password" class="input-text" name="password_2" id="password_2" />
				</p>
				<div class="clear"></div>
				<?php $eshopbox->nonce_field('change_password')?>
				<input type="hidden" name="action" value="change_password" />
				<input type="submit" class="button cancel_form_changes" name="cancel" value="<?php _e( 'Cancel', 'eshopbox' ); ?>" />
				<input type="submit" class="button" name="change_email" value="<?php _e( 'Save Changes', 'eshopbox' ); ?>" />
				</form>
			</div>
			<div class="current_usr">
			<p class="succmsg" style="visiblity:hidden"></p>
			<!--<span class="myaccount_user">
			<?php
			echo "Full Name : &nbsp;<span class='userename'>" .$current_user->display_name.'</span>';
			?>&nbsp;&nbsp;<input type="submit" value="EDIT PROFILE" id="form_1" class="edit-account-details">
			<a href="javascript://" id="form_1" class="button view edit-account-details">Edit</a>
			</span>-->
			<!--<div class="padd_accnt">
			<?php
			echo " Email Address : <span class='useremail'>" .$current_user->user_email.'</span>';
			if($current_user->phone!= '') echo "<br/>".$current_user->phone;?>&nbsp;&nbsp;
			<input type="submit" value="CHANGE PASSWORD" id="Change_passowrd" id="form_2" class="button view edit-account-details">
			<a href="javascript://" id="form_2" class="button view edit-account-details">Edit</a>
			</div>-->

			</div>

		</div>
			<?php
			if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
			global $eshopbox;
			if($_GET['type'] != 'account'){ $eshopbox->show_messages(); } ?>
					<h3 class="change_title">Change password</h3>					
            </div>
			</div>
			<div class="change_password">
				<div class="re_cls4"><h3>Password</h3></div>
			<div><span class="lebel_text">Password</span> <span class="full_name">***********</span></div>
			<input type="submit" value="CHANGE PASSWORD" id="form_2" class="button view edit-account-details">
			<input type="submit" value="CHANGE PASSWORD" id="Change_passowrd">
			</div>
				<!-- change_password -->
		</div>
	</div>
<?php } ?>
<?php get_footer(); ?>