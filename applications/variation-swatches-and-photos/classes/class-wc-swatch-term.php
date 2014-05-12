<?php

class WC_Product_Swatch_Term extends WC_Swatch_Term {

    protected $attribute_options;

    public function __construct($attribute_options, $term_id, $taxonomy, $selected=false) {
        global $eshopbox, $_wp_additional_image_sizes;
        $this->attribute_options = $attribute_options;

        $this->taxonomy_slug = $taxonomy;
        if (taxonomy_exists($taxonomy)) {
            $this->term = get_term($term_id, $taxonomy);
            $this->term_label = $this->term->name;
            $this->term_slug = $this->term->slug;
        } else {
            $this->term = false;
            $this->term_label = $term_id;
            $this->term_slug = $term_id;
        }
        
        $this->selected = $selected;
        
        $this->size = $attribute_options['size'];
        $the_size = isset($_wp_additional_image_sizes[$this->size]) ? $_wp_additional_image_sizes[$this->size] : $_wp_additional_image_sizes['shop_thumbnail'];
        if (isset($the_size['width']) && isset($the_size['height'])) {
            $this->width = $the_size['width'];
            $this->height = $the_size['height'];
        } else {
            $this->width = 32;
            $this->height = 32;
        }
        
        $key = sanitize_title($this->term_slug);
        $this->type = $attribute_options['attributes'][$key]['type'];

        if (isset($attribute_options['attributes'][$key]['image']) && $attribute_options['attributes'][$key]['image']) {
            $this->thumbnail_id = $attribute_options['attributes'][$key]['image'];
            $this->thumbnail_src = current( wp_get_attachment_image_src( $this->thumbnail_id, $this->size ) );
        } else {
            $this->thumbnail_src = $eshopbox->plugin_url() . '/assets/images/placeholder.png';
        }

        $this->color = isset($attribute_options['attributes'][$key]['color']) ? $attribute_options['attributes'][$key]['color'] : '#FFFFFF;';
    }
}

class WC_Swatch_Term {

    protected $attribute_meta_key;
    protected $term_id;
    protected $term;
    protected $term_label;
    protected $term_slug;
    
    protected $taxonomy_slug;
    protected $selected;
    protected $type;
    protected $color;
    protected $thumbnail_src;
    protected $thumbnail_id;
    protected $size;
    protected $width = 32;
    protected $height = 32;

    public function __construct($attribute_data_key, $term_id, $taxonomy, $selected=false, $size = 'swatches_image_size') {

        $this->attribute_meta_key = $attribute_data_key;
        $this->term_id = $term_id;
        $this->term = get_term($term_id, $taxonomy);
        $this->term_label = $this->term->name;
        $this->term_slug = $this->term->slug;
        $this->taxonomy_slug = $taxonomy;
        $this->selected = $selected;
        $this->size = $size;

        $this->on_init();
    }

    public function on_init() {
        global $eshopbox, $_wp_additional_image_sizes;

        $this->init_size($this->size);

        $type = get_eshopbox_term_meta($this->term_id, $this->meta_key() . '_type', true);
        $color = get_eshopbox_term_meta($this->term_id, $this->meta_key() . '_color', true);
        $this->thumbnail_id = get_eshopbox_term_meta($this->term_id, $this->meta_key() . '_photo', true);

        $this->type = $type;
        $this->thumbnail_src = $eshopbox->plugin_url() . '/assets/images/placeholder.png';
        $this->color = '#FFFFFF';

        if ($type == 'photo') {
            if ($this->thumbnail_id) {
                $this->thumbnail_src = current( wp_get_attachment_image_src( $this->thumbnail_id, $this->size ) );
            } else {
                $this->thumbnail_src = $eshopbox->plugin_url() . '/assets/images/placeholder.png';
            }
        } elseif ($type == 'color') {
            $this->color = $color;
        }
    }

    public function init_size($size) {
        global $eshopbox, $_wp_additional_image_sizes;
        $this->size = $size;
        $the_size = isset($_wp_additional_image_sizes[$size]) ? $_wp_additional_image_sizes[$size] : $_wp_additional_image_sizes['shop_thumbnail'];
        if (isset($the_size['width']) && isset($the_size['height'])) {
            $this->width = $the_size['width'];
            $this->height = $the_size['height'];
        } else {
            $this->width = 32;
            $this->height = 32;
        }
    }

    public function get_output($keyloop='',$placeholder = true, $placeholder_src = 'default') {
        global $eshopbox,$post;
        

	/**************** my code **********************/
$detail_image_url = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'shop_single');
$img_size = $detail_image_url[1].'x'.$detail_image_url[2];
$url = $_SERVER['REQUEST_URI'];
$array1 = explode('=', $url);
$last_arr_element = sizeof($array1) - 2;
$post_name = $post->post_name;
$post_parent_id = eshop_get_post_id($post_name);

		 $result = eshop_swatch_join($post_parent_id);//echo "testtest<pre>";print_r($result);//exit;
		 $i=0;
		$upload_dir = wp_upload_dir();
		 while($fetch = mysql_fetch_array($result))
			{//echo "testtest<pre>";print_r($fetch);//exit;
			$post_id = $fetch['ID'];
			$id[$i]=$post_id;
                        $varslug[$i]=$fetch['meta_value'];
			$meta_values = get_post_meta($post_id, 'variation_image_gallery');
			$main_image_id = get_post_meta($post_id, '_thumbnail_id');
                        $bb=wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'large');
                        //$cc=wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'shop_thumbnail');
                        $aa=wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'shop_single');
			 	foreach($main_image_id as $key=> $val){
				$main_image_idd = get_post_meta($val, '_wp_attached_file');					
                                                if($main_image_idd[0]!=''){
                                                     $expl = explode('.',$main_image_idd[0]);
                                                     $val__ = $expl[0].'-'.$img_size.'.'.$expl[1];
                                                     if(!in_array($upload_dir['baseurl'].'/'.$val__,$main_image_idd))
                                                            $main_img_path[$fetch['meta_value']] = $aa[0];
                                                            $main_img_path1[$fetch['meta_value']] = $bb[0];
                                                }
				}
				$expl_val = explode(',',$meta_values[0]);
				$image_pathh='';
				$image_variation='';
				if($expl_val[0]!=''){
                                    foreach($expl_val as $key=> $vals){
                                        //$image_ = wp_get_attachment_image_src( $vals,'medium');
                                        $image_ = wp_get_attachment_image_src( $vals,'shop_single');                                     
                                        $image_large = wp_get_attachment_image_src( $vals,'large');                                      
                                        $image_pathh .= $image_[0].'==='.$fetch['ID']."@@@";
                                        $image_variation .= $image_large[0].'==='.$fetch['meta_value']."===".$fetch['ID']."@@@";
                                    }
                                }    
				$image_path .= $main_img_path[$fetch['meta_value']].'==='.$fetch['ID']."@@@".$image_pathh;
				//$image_path .= '==='.$fetch['ID']."@@@".$image_pathh;
				$image_var .= $main_img_path[$fetch['meta_value']].'==='.$fetch['meta_value']."===".$fetch['ID']."@@@".$image_variation;	
                                $largeimage.=$bb[0].'==='.$fetch['meta_value']."===".$fetch['ID']."@@@".$image_variation;
                                $thumbimage.=$cc[0].'==='.$fetch['meta_value']."===".$fetch['ID']."@@@".$image_variation;
//                                
				$i++;			
			} 
//echo "<pre>imgcheck";print_r($image_path);echo "</pre>";
/***************** my code **********************/

        $picker = '';
        $datavalue=getquantity($post->ID);
        
	$href = apply_filters('eshopbox_swatches_get_swatch_href', '#', $this);
        $anchor_class = apply_filters('eshopbox_swatches_get_swatch_anchor_css_class', 'swatch-anchor', $this);
        $image_class = apply_filters('eshopbox_swatches_get_swatch_image_css_class', 'swatch-img', $this);
        $image_alt = apply_filters('eshopbox_swatches_get_swatch_image_alt', 'thumbnail', $this); 
        $sizevalue=0;
        if ($this->type == 'photo' || $this->type == 'image') {
            $picker .= '<a href="#" style="width:' . $this->width . 'px;height:' . $this->height . 'px;" title="' . $this->term_label . '">';
            $picker .= '<img src="' . apply_filters('eshopbox_swatches_get_swatch_image', $this->thumbnail_src, $this->term_slug, $this->taxonomy_slug)  . '" alt="Thumbnail" class="wp-post-image swatch-photo' . $this->meta_key() . '" width="' . $this->width . '" height="' . $this->height . '"/>';
            $picker .= '</a>';
        } elseif ($this->type == 'color') {
            $sizes_count = wp_get_post_terms($post->ID,'pa_size');//echo "<pre>";print_r($sizes_count);echo "</pre>";
            if(count($sizes_count) == 1 && $this->taxonomy_slug == 'pa_size'){
                $picker .= '<a href="#" style="display:none;text-indent:-9999px;width:' . $this->width . 'px;height:' . $this->height . 'px;background-color:' . apply_filters('eshopbox_swatches_get_swatch_color', $this->color, $this->term_slug, $this->taxonomy_slug) . ';" title="' . $this->term_label . '" >' . $this->term_label  . '</a>';              }else{
                $picker .= '<a href="#" style="text-indent:-9999px;width:' . $this->width . 'px;height:' . $this->height . 'px;background-color:' . apply_filters('eshopbox_swatches_get_swatch_color', $this->color, $this->term_slug, $this->taxonomy_slug) . ';" title="' . $this->term_label . '" >' . $this->term_label  . '</a>';
                //$picker .= 'Free-size';
                }
            //$picker .= '<a href="#" style="text-indent:-9999px;width:' . $this->width . 'px;height:' . $this->height . 'px;background-color:' . apply_filters('eshopbox_swatches_get_swatch_color', $this->color, $this->term_slug, $this->taxonomy_slug) . ';" title="' . $this->term_label . '">' . $this->term_label  . '</a>';
        } elseif ($placeholder!=1) {
            if ($placeholder_src == 'default') {
                $src = $eshopbox->plugin_url() . '/assets/images/placeholder.png';
            } else {
                $src = $placeholder_src;
            }

            $picker .= '<a href="#" style="width:' . $this->width . 'px;height:' . $this->height . 'px;" title="' . $this->term_label . '">';
            $picker .= '<img src="' . $src . '" alt="Thumbnail" class="wp-post-image swatch-photo' . $this->meta_key() . '" width="' . $this->width . '" height="' . $this->height . '"/>';
            $picker .= '</a>';
        } else {
            $sizeno=5;
            return '';
        }//echo "<pre>";print_r($this);echo "</pre>";
        if($this->type == 'color'){
            $sizevalue=0;
        }else{
            $sizevalue=5;
        }	
        $out = '<div class="select-option swatch-wrapper" data-value="' . $this->term_slug . '" ' . ($this->selected ? 'data-default="true"' : '') . '>';
        $out .= apply_filters('eshopbox_swatches_picker_html', $picker, $this);
        $out .= '</div>';
	if (!is_numeric($this->term_slug) && $keyloop==0){
            $out .= '<input type="hidden" name="imagepath_display" id="imagepath_display" value="'.str_replace('0x0','450x450',$image_path).'" />';
            //$out .= '<input type="hidden" name="imagepath_display" id="imagepath_display" value="'.$image_path.'" />';
            
	}
	else if($keyloop==0){ 
	$out .= '<input type="hidden" name="imagepath_display" id="imagepath_display" value="" />';
	}
        //echo "<pre>";print_r($post);echo "</pre>";
        $default_swatch = get_post_meta($post->ID,'_default_attributes');
        $default_swatch_c = $default_swatch[0]['pa_color'];
	if($id != NULL){
        foreach($id as $keyid=>$idval){
            if($varslug[$keyid]==$default_swatch_c){
                $defaultid=$id[$keyid];
            }
        }
	}
        $valdefid=$_GET['imageid']>0?$_GET['imageid']:$defaultid;  
        if($keyloop==0){
            $out .= '<input type="hidden" name="defaultvariation" id="defaultvariation" value="'.$valdefid.'" />';
            $out .= '<input type="hidden" name="defaultvariationdetail" id="defaultvariationdetail" value="'.str_replace('0x0','450x450',$image_var).'" />';
            $out.='<input type="hidden" name="productname" id="productname" value="'.$post_name.'">';
            $out.='<input type="hidden" name="posttitle" id="posttitle" value="'.$post->post_title.'">';
            $out.='<input type="hidden" name="stockcheck" id="stockcheck" value="'.$datavalue.'">';
            $out.='<input type="hidden" name="sizeval" id="sizeval" value="'.$sizevalue.'">';
            $out.='<input type="hidden" name="largeimage" id="largeimage" value="'.$largeimage.'">';
            //$out.='<input type="hidden" name="thumbimage" id="thumbimage" value="'.$thumbimage.'">';
	    //$out.='<input type="hidden" name="zoom_img_size" id="largeimage" value="'.$largeimage.'">';
	    //$out.='<input type="hidden" name="single_img_size" id="largeimage" value="'.$largeimage.'">';
        }
        return $out;
    }

    public function get_type() {
        return $this->type;
    }

    public function get_color() {
        return $this->color;
    }

    public function get_image_src() {
        return $this->thumbnail_src;
    }

    public function get_image_id() {
        return $this->thumbnail_id;
    }

    public function get_width() {
        return $this->width;
    }

    public function get_height() {
        return $this->height;
    }

    public function meta_key() {
        return $this->taxonomy_slug . '_' . $this->attribute_meta_key;
    }

}

?>
