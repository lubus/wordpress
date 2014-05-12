<?php
function phiverivers_widgets_init_new() {
	register_sidebar( array(
		'name' => __( 'footer_menu', 'phiverivers' ),
		'id' => 'sidebar-4',
		'description' => __( 'footer_menu', 'phiverivers' ),
		'before_widget' => '<div id="%1$s" class="%2$s">',
		'after_widget' => '</div>',
		
	) );
	register_sidebar( array(
		'name' => __( 'footer_menu social', 'phiverivers' ),
		'id' => 'sidebar-5',
		'description' => __( 'footer_menu social', 'phiverivers' ),
		'before_widget' => '<div id="%1$s" class="%2$s">',
		'after_widget' => '</div>',
		
	) );
	
	register_sidebar( array(
		'name' => __( 'Recently Viewed Products', 'phiverivers' ),
		'id' => 'sidebar-6',
		'description' => __( 'Recently Viewed Products', 'phiverivers' ),
		'before_widget' => '<div id="%1$s" class="%2$s">',
		'after_widget' => '</div>',
		
	) );
     
	 register_sidebar( array(
		'name' => __( 'Need help block', 'phiverivers' ),
		'id' => 'sidebar-7',
		'description' => __( 'Need help block', 'phiverivers' ),
		'before_widget' => '<div id="%1$s" class="%2$s">',
		'after_widget' => '</div>',
		
	) );
	 
	 register_sidebar( array(
		'name' => __( 'Secure payment block', 'phiverivers' ),
		'id' => 'sidebar-8',
		'description' => __( 'Secure payment block', 'phiverivers' ),
		'before_widget' => '<div id="%1$s" class="%2$s">',
		'after_widget' => '</div>',
		
	) );
	 
	 register_sidebar( array(
		'name' => __( 'Shipping and delivery block', 'phiverivers' ),
		'id' => 'sidebar-9',
		'description' => __( 'Shipping and delivery block', 'phiverivers' ),
		'before_widget' => '<div id="%1$s" class="%2$s">',
		'after_widget' => '</div>',
		
	) );
	 
	 
	 register_sidebar( array(
		'name' => __( 'Static pages sidebar menu', 'phiverivers' ),
		'id' => 'sidebar-10',
		'description' => __( 'Static pages sidebar menu', 'phiverivers' ),
		'before_widget' => '<div id="%1$s" class="%2$s">',
		'after_widget' => '</div>',
		
	) );
	 
	 register_sidebar( array(
		'name' => __( 'home banner', 'phiverivers' ),
		'id' => 'banner_home',
		'description' => __( 'home banner', 'phiverivers' ),
		'before_widget' => '<div id="%1$s" class="%2$s">',
		'after_widget' => '</div>',
		
	) );
	 
	 	 register_sidebar( array(
		'name' => __( 'Newsletter block', 'phiverivers' ),
		'id' => 'newsletter',
		'description' => __( 'Newsletter block', 'phiverivers' ),
		'before_widget' => '<div id="%1$s" class="%2$s">',
		'after_widget' => '</div>',
		
	) );
	 
	 register_sidebar( array(
		'name' => __( 'home content category block', 'phiverivers' ),
		'id' => 'sidebar-11',
		'description' => __( 'home content category block', 'phiverivers' ),
		'before_widget' => '',
		'after_widget' => '',
		
	) );
	 
	 register_sidebar( array(
		'name' => __( 'Shipping content detail', 'phiverivers' ),
		'id' => 'sidebar-12',
		'description' => __( 'Shipping content detail', 'phiverivers' ),
		'before_widget' => '',
		'after_widget' => '',
		
	) );
	 
	 register_sidebar( array(
		'name' => __( 'Drop down menu', 'phiverivers' ),
		'id' => 'drop_downmenu',
		'description' => __( 'Drop down menu', 'phiverivers' ),
		'before_widget' => '',
		'after_widget' => '',
		
	) );
	 
	 
}
add_action( 'widgets_init', 'phiverivers_widgets_init_new' );


?>
