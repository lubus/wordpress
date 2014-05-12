<?php



/**



 * Checkout login form



 *



 * @author 		WooThemes



 * @package 	eshopbox/Templates



 * @version     2.0.0



 */







if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly







//if ( is_user_logged_in()  || ! $checkout->enable_signup ) return;







//$info_message = apply_filters( 'eshopbox_checkout_login_message', __( 'Already have an Account ?', 'eshopbox' ) );



if ( !is_user_logged_in()){ 



?>







<div class="eshopbox-info">



	<?php echo esc_html( $info_message ); ?>



    	<div class="checkout_tabblock" style="display:none;">



    	<div class="checkout_guest">You Are Using Checkout As Guest.</div>

			<ul class="checkout_tabs">



    		<li><a href="javascript://" class="showlogin"><?php _e( 'Sign In', 'eshopbox' ); ?></a></li>



    		<li><a href="javascript://" class="showre"><?php _e( 'Create Account', 'eshopbox' ); ?></a></li>



       	</ul>



        </div>

        

      



        </div>



    	



<?php



	eshopbox_login_form(



		array(



			'message'  => __( 'If you have an account on shopsabhyata.com, please enter your details to login.', 'eshopbox' ),



			'redirect' => get_permalink( eshopbox_get_page_id( 'checkout') ),



			'hidden'   => false



		)



	);



} else {



    $current_user = wp_get_current_user();







//echo '<pre>';



//print_r($current_user);



$euserEmail = $current_user->data->user_email;



$userDisplayName = $current_user->data->display_name;



?>



<div class="eshopbox-info">



	<div class="checkout_tabblock" style="display:none;">



    	<div class="checkout_guest">You have been successfully logged in.</div>



        <div class="checkout_afterlogin">You are logged In as : <b><?php echo $euserEmail;  ?></b> <br /><a href="/logout">Logout</a></div>



    	



        </div>

        

       





        </div>















<?php } ?>



