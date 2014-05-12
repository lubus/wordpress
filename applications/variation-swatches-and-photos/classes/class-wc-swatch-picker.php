<?php

class WC_Swatch_Picker {

    private $size;
    private $attributes;
    private $selected_attributes;
    private $swatch_type_options;

    public function __construct($product_id, $attributes, $selected_attributes) {
        $this->swatch_type_options = get_post_meta($product_id, '_swatch_type_options', true);

        if (!$this->swatch_type_options) {
            $this->swatch_type_options = array();
        }

        $product_configured_size = get_post_meta($product_id, '_swatch_size', true);
        if (!$product_configured_size) {
            $this->size = 'swatches_image_size';
        } else {
            $this->size = $product_configured_size;
        }

        $this->attributes = $attributes;
        $this->selected_attributes = $selected_attributes;
    }

    public function picker() {
        global $eshopbox,$post;
        $terms = wp_get_post_terms($post->ID,'product_cat',array('fields'=>'all'));
        //echo "<pre>";print_r($terms);echo "</pre>";
        $slug='';
        foreach($terms as $key=>$value){
            if($value->slug=='men' ||$value->slug=='womens'){
                $slug=$value->slug;
            }
        }//echo "<pre>";print_r($value->slug);echo "</pre>";
        ?>
        
        <table class="variations-table" cellspacing="0">
           
                
               <?php $loop = 0; $ii=0;
               $count_size==0;
                foreach ($this->attributes as $name => $options) : $loop++;
                        if($name == 'pa_size'){
                                //echo $count_size = count($options);
                                //echo "<pre>";print_r($options);echo "<pre>";
                                        if($options[0]!=''){                                       
                                               echo '<input type="hidden" id="attribute_pa_size" value="'.$options[0].'">';
                                                $count_size++;    
                                        }else{
                                               //echo '<input type="hidden" id="pa_size_free" value="">';
                                        }       
                        }
                        //echo "<pre>";print_r($options);echo "</pre>";
                    ?>
                    <div class="swatchs_wrap">
                        <div class="<?php echo sanitize_title($name); ?> label_wrap"><label for="<?php echo sanitize_title($name); ?>"><?php echo $eshopbox->attribute_label($name); ?> : </label>
                            <?php if($ii==1 && $slug!=''){ ?>
                           
                            <?php } ?>
                            <?php $ii= $ii+1; ?>
                        </div>
                        <div class="picker">
                            <?php
                            if (isset($this->swatch_type_options[sanitize_title($name)])) {
                                $picker_type = $this->swatch_type_options[sanitize_title($name)]['type'];
                                if ($picker_type == 'default') {
                                        
                                    $this->render_default(sanitize_title($name), $options);
                                } else {                               
                                   $this->render_picker(sanitize_title($name), $options);
                                }
                            } else {                              
                                $this->render_default(sanitize_title($name), $options);
                            }
                            ?>
                            <?php if($name=='pa_size'){
                              if($value->slug=='womens'){ ?>
                                <a href="javascript://" class="sizechartlink"><div id="sizechart_women" title="Size Chart" class="<?php echo $slug;?>">Size Chart</div></a> 
                            <?php } ?>
                            <?php if($value->slug=='men'){ ?>
                                <a href="javascript://" class="sizechartlink"><div id="sizechart_men" title="Size Chart" class="<?php echo $slug;?>">Size Chart</div></a> 
                            <?php } ?>
                             <?php } ?>
                        </div>
                        <?php if($name=='pa_size'){?><td><span id="selectsize" class="selectsize" style="display:none;">(Select a size)</span></td>
						<?php
						
						}?>
                    </div>
                <?php endforeach; ?>
                 <div class="qty_input"><?php eshopbox_quantity_input(); ?></div>
            
        </table>
        <?php
    }

    public function render_picker($name, $options) {
        global $eshopbox;
        if($_GET['imageid']>0 && $name=='pa_color'){
            $metadetail=get_post_meta($_GET['imageid']);
            $selected_value=$metadetail['attribute_pa_color'][0];
        }else{
            $selected_value = (isset($this->selected_attributes[sanitize_title($name)])) ? $this->selected_attributes[sanitize_title($name)] : '';
        }
        $term_by = get_term_by('slug',$selected_value,'pa_size');
        ?>
        <div 
            data-attribute-name="<?php echo 'attribute_' . sanitize_title($name); ?>"
            data-value="<?php echo $selected_value; ?>"
            id="<?php echo esc_attr(sanitize_title($name)); ?>" 
            class="select attribute_<?php echo sanitize_title($name); ?>_picker">
            <?php if($name=='pa_size'){ //echo "count".count($this->pa_size); ?>
                <span><?php echo $term_by->name;
                
                ?></span>
            <?php } ?>
            <input type="hidden" name="<?php echo 'attribute_' . sanitize_title($name); ?>" id="<?php echo 'attribute_' . sanitize_title($name); ?>" value="<?php echo $selected_value; ?>" />

            <?php if (is_array($options)) : ?>
                <?php
                // Get terms if this is a taxonomy - ordered
                if (taxonomy_exists(sanitize_title($name))) :
                    $args = array('menu_order' => 'ASC');
                    $terms = get_terms(sanitize_title($name), $args);
                    $ii=0;
                    
                    foreach ($terms as $term) :
                        
                        if (!in_array($term->slug, $options)) {
                            continue;
                        }
                           

                        if ($this->swatch_type_options[$name]['type'] == 'term_options') {
                            $size = apply_filters('eshopbox_swatches_size_for_product', $this->size, get_the_ID(), sanitize_title($name));
                            $swatch_term = new WC_Swatch_Term('swatches_id', $term->term_id, sanitize_title($name), $selected_value == $term->slug, $size);
                        } elseif ($this->swatch_type_options[$name]['type'] == 'product_custom') {
                            $size = apply_filters('eshopbox_swatches_size_for_product', $this->swatch_type_options[sanitize_title($name)]['size'], get_the_ID(), sanitize_title($name));
                            $swatch_term = new WC_Product_Swatch_Term($this->swatch_type_options[$name], $term->term_id, sanitize_title($name), $selected_value == $term->slug, $size);
                        }


                        do_action('eshopbox_swatches_before_picker_item', $swatch_term);
                        echo $swatch_term->get_output($ii);
                        do_action('eshopbox_swatches_after_picker_item', $swatch_term);
                        $ii++;
                    endforeach;
                else :
                    foreach ($options as $option) :
                        $size = apply_filters('eshopbox_swatches_size_for_product', $this->swatch_type_options[sanitize_title($name)]['size'], get_the_ID(), sanitize_title($name));
                        $swatch_term = new WC_Product_Swatch_Term($this->swatch_type_options[sanitize_title($name)], $option, $name, $selected_value == $option, $size);
                        
                        do_action('eshopbox_swatches_before_picker_item', $swatch_term);
                        echo $swatch_term->get_output();
                        do_action('eshopbox_swatches_after_picker_item', $swatch_term);
                    endforeach;
                endif;
                ?>
            <?php endif; ?>
        </div>
        <?php
    }

    public function render_default($name, $options) {
    //echo "call render default f";
        global $eshopbox;
        ?>
        <select 
            data-attribute-name="<?php echo 'attribute_' . sanitize_title($name); ?>"
            id="<?php echo esc_attr(sanitize_title($name)); ?>" 
            name="attribute_<?php echo sanitize_title($name); ?>">
            <option value=""><?php echo __('Choose an option', 'eshopbox') ?>&hellip;</option>
            <?php if (is_array($options)) : ?>
                <?php
                $selected_value = (isset($this->selected_attributes[sanitize_title($name)])) ? $this->selected_attributes[sanitize_title($name)] : '';
              
                // Get terms if this is a taxonomy - ordered
                if (taxonomy_exists(sanitize_title($name))) :
                    $args = array('menu_order' => 'ASC');
                    $terms = get_terms(sanitize_title($name), $args);

                    foreach ($terms as $term) :
                        if (!in_array($term->slug, $options))
                            continue;
                        echo '<option value="' . esc_attr($term->slug) . '" ' . selected($selected_value, $term->slug) . '>' . $term->name . '</option>';
                    endforeach;
                else :
                    foreach ($options as $option) :
                        echo '<option value="' . esc_attr($option) . '" ' . selected($selected_value, $option) . '>' . $option . '</option>';
                    endforeach;
                endif;
                ?>
            <?php endif; ?>
        </select>
        <?php
    }

}
?>
