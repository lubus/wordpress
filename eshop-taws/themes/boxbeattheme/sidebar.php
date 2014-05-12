<?php
/**
 * The sidebar containing the main widget area.
 *
 * If no active widgets in sidebar, let's hide it completely.
 *
 * @package BoxBeat
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
 
 $url = $_SERVER['REQUEST_URI'];
 $expl = explode('?',$url);
  
?>

	<?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
    <?php if(is_archive()) { ?>
		<div id="secondary" class="widget-area" role="complementary">
        <div class="heading_title"><h2>Filter Styles By</h2></div>
			<?php 
                        dynamic_sidebar( 'sidebar-1' );
                        
                        ?>
            
            <aside id="nav_menu-11" class="color_archive">
            <?php if($_GET['filter_color'] != ''){   
            ?>
            <span class="clear_cls"><a href="<?php echo $expl[0]; ?>">Clear</a></span> 
            <?php } ?>
            <?php dynamic_sidebar( 'colorfilterwidget' ); ?>
                     
            
            </aside>
            
		</div><!-- #secondary -->
        <?php  }?>
	<?php endif; ?>
