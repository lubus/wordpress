<?php
/*
Plugin Name: Inventory Update
Description:Create order csv depending on different criterias
Author: Shalu garg
Version: 1.0
Author URI: Boxbeat Technologies Pvt Ltd
*/
global $wpdb,$product;
$plugin_file_name=plugin_dir_path(__FILE__);
if(!defined(INVENTORY_PATH))
define('INVENTORY_PATH',$plugin_file_name);
if(!defined(INVENTORY_URL))
define('INVENTORY_URL',plugin_dir_url(__FILE__));


//function for categpory filter
function pro_cat_filter($product_array)
{  
    
    $pro_id=$product_array->ID;
    $parent_id=$product_array->post_parent;
    $cats= wp_get_post_terms( $parent_id,'product_cat' );
    $postcat=$_POST['category_filter'];
    $allcat=array();
    foreach($cats as $key=>$actobj){
        $allcat[]=$actobj->slug;
    }  
   if($postcat=='all')
    {
        $filter=$product_array;
    }
    else if(in_array($postcat,$allcat))
    {
         $filter=$product_array;
    }
    return $filter;

}
function pro_type_filter($product_array){
    $pro_id=$product_array->ID;
    $product=new WC_Product($pro_id);
    $term_protype=$product->post->product_type;
    //$term_data=wp_get_post_terms($pro_id,'product_type');
   // $term_protype=$term_data[0]->slug;
    if($term_protype=='simple'){
        $typefilter=$product_array;
    }
    return $typefilter;

}
//function displaying inventory update page
function inventory_update()
{
    $cats=get_terms('product_cat');
   //arguments for fetching all products
   
    if($_POST['inventorycsv']=='true')
    {
         $protype=$_POST['protype_filter'];
        if($protype=='variable'){
            $post_type='product_variation';
        }
        else if($protype=='simple')
        {
            $post_type='product';
        }        
        $args = array(
            'posts_per_page'   => -1,
            'post_type'        => $post_type,
            'post_status'      => 'publish',
            'suppress_filters' => true );
         $pro_array=get_posts($args);
        
        $filename='product_inventory.csv';
        $file=INVENTORY_PATH.$filename;
        ob_end_clean();
        $fp=fopen($file,'w+');
        $pro_header=array('ID','PRODUCT NAME','SKU','COLOR','SIZE','STOCK','REGULAR PRICE','SALE PRICE');
        fputcsv($fp,$pro_header);
        $filter=array();
       
        $typefilter=array();
        if($protype =='simple')
        {
            $pro_array=array_filter($pro_array,'pro_type_filter');
        }
      
//         echo "<pre>";print_r($args);echo "</pre>";
    
        $profinal_array=array_filter($pro_array,'pro_cat_filter');
          //echo "<pre>";print_r($profinal_array);echo "</pre>";exit;
         
        foreach($profinal_array as $key=>$proobj)
        {

            $proid=$proobj->ID;
            $product=new WC_Product($proid);
            $stock=$product->post->stock;
            $regular_price=$product->post->regular_price;
            $sale_price=$product->post->sale_price;
            $parent_id=$product->post->parent->id;
            $parent_status=$product->post->parent->post->post_status;
         
//            $current_cat=  wp_get_post_terms( $parent_id, 'product_cat' );
//            foreach($current_cat as $temp=>$cat)
//            {
//                if($cat->parent=='0')
//                {
//                    $current_cat=$cat->slug;
//                }
//            }
           // echo "<pre>";print_r($proobj);echo "</pre>";
            //echo "<pre>";print_r($product);echo "</pre>";
            $taxonomy_name='attribute_pa_size';
            $pro_title=$proobj->post_title;
            $size=$product->post->product_custom_fields[$taxonomy_name];
            $parentid=$product->post->parent->post->ID;
            $sku=get_post_meta($proid,'_sku');
            if(empty($size))
            {
               $size=$product->post->product_custom_fields['pa_size'];
            }
            if(!empty($size))
            {
                $size=$size[0];
            }
            $color=$product->post->product_custom_fields['attribute_pa_color'];
            if(empty($color))
            {
                $color=$product->post->product_custom_fields['pa_color'];
            }
            if(!empty($color))
            {
                $color=$color[0];
            }
            $final_pro_array['proid']=$proid;
            $final_pro_array['proname']=$pro_title;
            $final_pro_array['sku']=$sku[0];
            $final_pro_array['color']=$color;
            $final_pro_array['size']=$size;
            $final_pro_array['stock']=$stock;
            $final_pro_array['regular_price']=$regular_price;
            $final_pro_array['sale_price']=$sale_price;
             //echo "<pre>";print_r($final_pro_array);echo "</pre>";exit;
//            if($parent_status == 'publish')
//            {
                fputcsv($fp,$final_pro_array);
            //}
        }
        header('Content-Description: File Transfer');
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename='.basename($file));
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        flush();
        readfile($file);
        $out = ob_get_contents();
        ob_end_clean();
        echo trim($out);
        exit; 
    }
    ?>
    <form method="post" action=" ">
        <p><label for="category_filter"><?php _e('Select Your Category','eshopbox');?></label></p>
        <p><select name="category_filter" id="category_filter">
            <option value="all" <?php  echo ($_POST['category_filter']=='all')?'selected="selected"': '';?> >All Category</option>
            <?php foreach($cats as $key=>$catobj){?>
            <option value="<?php echo $catobj->slug; ?>" <?php  echo ($_POST['category_filter']=='$catobj->slug')?'selected="selected"': '';?> ><?php echo $catobj->name; ?></option>
            <?php } ?>
            </select></p>
             <p><label for="protype_filter"><?php _e('Select Your Product type','eshopbox');?></label></p>
            <p>
                <select name="protype_filter" id="protype_filter">
<!--                    <option  value="" <?php  //echo ($_POST['protype_filter']=='')?'selected="selected"': '';?> >Product Type</option>-->
                    <option value="simple" <?php  echo ($_POST['protype_filter']=='simple')?'selected="selected"': '';?>>Simple</option>
                    <option value="variable"  <?php  echo ($_POST['protype_filter']=='variable')?'selected="selected"': '';?>>Variable</option>
                </select>
            </p>
        <p><input type="hidden" name="inventorycsv" value="true"/></p>
        <p><input type="submit" class="button" value="<?php _e( 'Export Csv', 'eshopbox' ); ?>" /></p>
     </form>
    <form action=" " method="post" enctype="multipart/form-data">
        <label for="file">Filename:</label>
        <input type="file" name="file" id="file"><br>
         <p><input type="hidden" name="inventoryimportcsv" value="true"/></p>
        <input type="submit" name="submit" value="Import Csv">
    </form>



<?php
    if($_POST['inventoryimportcsv']=='true')
    {
        $allowedExts = array("csv");
        $temp = explode(".", $_FILES["file"]["name"]);
        $extension = end($temp);
        if(in_array($extension, $allowedExts))
        {
            if($_FILES['file']['error']>0)
            {
                echo "Error".$_FILES['file']['error']."<br>";
            }

        move_uploaded_file($_FILES['file']['tmp_name'],INVENTORY_PATH.'/'.$_FILES['file']['name']);
        $file = fopen(INVENTORY_PATH.'/'.$_FILES['file']['name'],"r");
        $i=1;
        $allcolumns=array();
        while(! feof($file))
          {          
                $allcolumns[]=fgetcsv($file);
           
          }
        $checkstock='';
	$stock=0;
        foreach($allcolumns as $key=>$data)
        {   if($key == 0)
            {
                $idkey=array_search('ID',$data);
                $namekey=array_search('PRODUCT NAME',$data);
                $colorkey=array_search('COLOR',$data);
                $sizekey=array_search('SIZE',$data);
                $stockkey=array_search('STOCK',$data);
                $regularpricekey=array_search('REGULAR PRICE',$data);
                $salepricekey=array_search('SALE PRICE',$data);
                if(in_array('ID',$data) && in_array('PRODUCT NAME',$data) && in_array('COLOR',$data) && in_array('SIZE',$data) && in_array('STOCK',$data)&& in_array('REGULAR PRICE',$data)&& in_array('SALE PRICE',$data)){
                    $cond=1;
                }
                else
                {
                    $cond=0;
                }
            }
            if($cond == 0)
            { 
                echo "Error :: Wrong csv format";exit;
            }
            else
            {                 
                if($key !=0)
                {
                    if(!empty($data[$idkey]))
                    {
                        $proid=get_post($data[$idkey]);  
                        if($checkstock=='' || $proid->post_parent==$checkstock){
				$checkstock=$proid->post_parent;
				$stockval=$stockval+$data[$stockkey];
			}else{
				$checkstock=$proid->post_parent;
				$stockval=0;
				$stockval=$data[$stockkey];
			}//echo $stockval."</br>";
                        if($data[$stockkey]!='')
                        {

		                update_post_meta($data[$idkey],'_stock',$data[$stockkey]);
				update_post_meta($proid->post_parent,'_stock',0);
				update_option('_transient_wc_product_total_stock_'.$proid->post_parent,$stockval);
				if($stockval>0)
                                {
                                    update_post_meta($proid->post_parent,'_stock_status','instock');
                                }
                                else
                                {
                                    update_post_meta($proid->post_parent,'_stock_status','outofstock');
                                }
                        }
                        if($data[$colorkey]!='')
                        {
                        //update_post_meta($data[$idkey],'attribute_pa_color',$data[$colorkey]);
                        }
                         if($data[$sizekey]!='')
                        {
                        //update_post_meta($data[$idkey],'attribute_pa_size',$data[$sizekey]);
                        }
                        $proid=get_post($data[$idkey]);                       
                        if($data[$regularpricekey]!='')
                        {
                            update_post_meta($proid->post_parent,'_price',$data[$regularpricekey]);
                            update_post_meta($proid->post_parent,'_min_variation_price',$data[$regularpricekey]);
                            update_post_meta($proid->post_parent,'_max_variation_price',$data[$regularpricekey]);
                            update_post_meta($proid->post_parent,'_max_variation_regular_price',$data[$regularpricekey]);
                            update_post_meta($proid->post_parent,'_min_variation_regular_price',$data[$regularpricekey]);
                            update_post_meta($data[$idkey],'_regular_price',$data[$regularpricekey]);
                            update_post_meta($data[$idkey],'_price',$data[$regularpricekey]);
                        }
                        if($data[$salepricekey]!='')
                        {
                            update_post_meta($proid->post_parent,'_min_variation_price',$data[$salepricekey]);
                            update_post_meta($proid->post_parent,'_max_variation_sale_price',$data[$salepricekey]);
                            update_post_meta($proid->post_parent,'_min_variation_sale_price',$data[$salepricekey]);
                            update_post_meta($proid->post_parent,'_price',$data[$salepricekey]);
                            update_post_meta($data[$idkey],'_sale_price',$data[$salepricekey]);
                            update_post_meta($data[$idkey],'_price',$data[$salepricekey]);
                        }   
                    }
                }
            }
        }        
        fclose($file);
        }
        else
        {
            echo "Invalid file.Select only csv files";exit;
        }
    }
}
function eshopbox_inventory_menu(){

      add_options_page('Inventory Update','Inventory Update', 'manage_options', 'inventory-update.php', 'inventory_update');
}
add_action('admin_menu', 'eshopbox_inventory_menu');
?>
