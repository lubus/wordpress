jQuery(document).ready( function($) {
    var calculator = new variation_calculator(product_attributes, product_variations_flat, my_all_set_callback, my_not_all_set_callback);
    calculator.reset_selected();
    calculator.reset_current();
    var productname=jQuery('#productname').val();
    var postname=jQuery('#posttitle').val();
    var stockcheck=jQuery('#stockcheck').val();
    jQuery('#titlepage').html(postname);
    var selectvalue='';
     $('.single_add_to_cart_button').click(function(){
        var size=$('#sizeval').val();
		//alert(size);
        if(size!=0){
            $('#selectsize').hide();
        }else{
            $('#selectsize').show();
            return false;
        }
      $('.woocommerce-error').hide();
    })
    function my_not_all_set_callback() {

        // Reset image
        var img = $('div.images img:eq(0)');
        var link = $('div.images a.zoom:eq(0)');
        var o_src = $(img).attr('data-o_src');
        var o_href = $(link).attr('data-o_href');

        if ( o_src && o_href ) {
            $(img).attr('src', o_src);
            $(link).attr('href', o_href);
        }

        $('form input[name=variation_id]').val('').change();
        //$('.single_variation_wrap').hide();
        //$('.single_variation').text('');


        if( $().uniform && $.isFunction($.uniform.update) ) {
            $.uniform.update();
        }

    }

    function my_all_set_callback(selected) {
		var found = null;

        for (sa in selected) {
            $('#' + sa).val( selected[sa] );
        }

        for(var p = 0; p < product_variations.length; p++) {
            var result = true;
            for (attribute in product_variations[p].attributes) {
                for(selected_attribute in selected) {
                    if (selected_attribute == attribute) {
                        var v = product_variations[p].attributes[attribute];
						//alert(selected[selected_attribute]);
                        if (v != selected[selected_attribute]) {
                            result = false;
                        }
                    }
                }
            }

            if (result){
                found = product_variations[p];
            }

        }


        if (!found) {
            for(var p = 0; p < product_variations.length; p++) {
                var result = true;
                for (attribute in product_variations[p].attributes) {
                    for(selected_attribute in selected) {
                        if (selected_attribute == attribute) {
                            var v = product_variations[p].attributes[attribute];
                            var vs = selected[selected_attribute];
                            if (v != '' && v != vs) {
                                result = false;
                            }
                        }
                    }
                }

                if (result){
                    found = product_variations[p];
                }
            }
        }

        if (found) {
            show_variation(found);
        }
    }

    function show_variation(variation) {
        swap_image(variation);
        $('.variations_button').show();
        $('.single_variation').html( variation.price_html + variation.availability_html );

        if (variation.sku) {
            $('.product_meta').find('.sku').text( variation.sku );
        } else {
            $('.product_meta').find('.sku').text('');
        }

        $('.single_variation_wrap').find('.quantity').show();

        if (variation.min_qty) {
            $('.single_variation_wrap').find('input[name=quantity]').attr('data-min', variation.min_qty).val(variation.min_qty);
        } else {
            $('.single_variation_wrap').find('input[name=quantity]').removeAttr('data-min');
        }

        if ( variation.max_qty ) {
            $('.single_variation_wrap').find('input[name=quantity]').attr('data-max', variation.max_qty);
        } else {
            $('.single_variation_wrap').find('input[name=quantity]').removeAttr('data-max');
        }

        if ( variation.is_sold_individually == 'yes' ) {
            $('.single_variation_wrap').find('input[name=quantity]').val('1');
            //$('.single_variation_wrap').find('.quantity').hide();
        }

        $('form input[name=variation_id]').val(variation.variation_id).change();

        $('.single_variation_wrap').slideDown('200').trigger( 'show_variation', [ variation ] );
        $('form.cart').trigger( 'found_variation', [ variation ] );
        
    }

    function swap_image(variation) {


	var image_id_post = jQuery('#image_id_post').val();

	if(image_id_post>0){
            variation.variation_id=image_id_post;
            jQuery('#image_id_post').val('0');
	}


        var img = $('div.images img:eq(0)');
        var link = $('div.images a.zoom:eq(0)');
        var o_src = $(img).attr('data-o_src');
        var o_title = $(img).attr('data-o_title');

        var o_href = $(link).attr('data-o_href');


        var variation_image = variation.image_src;
        var variation_link = variation.image_link;
        var variation_title = variation.image_title;


        if (!o_src) {
            $(img).attr('data-o_src', $(img).attr('src'));
        }

        if (!o_title) {
            $(img).attr('data-o_title', $(img).attr('title') );
        }

        if (!o_href) {
            $(link).attr('data-o_href', $(link).attr('href'));
        }


        if (variation_image && variation_image.length > 1) {
            $(img).attr('src', variation_image);
            $(img).attr('title', variation_title);
            $(img).attr('alt', variation_title);
            $(link).attr('href', variation_link);
            $(link).attr('title', variation_title);
        } else {
            $(img).attr('src', o_src);
            $(img).attr('title', o_title);
            $(img).attr('alt', o_title);
            $(link).attr('href', o_href);
            $(link).attr('title', o_title);
        }


	/* my code hghj*/

	var imagename_display = jQuery('#imagepath_display').val();
	//alert(imagename_display);
       	var mainvalue=jQuery('#imagepath_display').val();
	var img_path = mainvalue.split('@@@');
	var i = 0;
	var htmlvalue='';
	var j=0;
	for(i=0;i < img_path.length;i++)
		{
		 var imagedetail = img_path[i].split('===');
		 //var largeimagename1=imagedetail[0].replace(".jpg",'');
		 var largeimagename11 = imagedetail[0];
                // var imagelarge=largeimagename11.replace('-400x400','-999x999');
		 if(imagedetail[1]== variation.variation_id && largeimagename11!='')
		  {
		      if(j==0){//large image
                       var idval=imagedetail[2];
			//alert(largeimagename11);
			$('#mainimage').html('<div id="wrap" style="top:0px;z-index:9999;position:relative;"><a rel="zoomWidth:\'540\',zoomHeight: \'500\',position:\'right\',adjustX:50,adjustY:0,tint:\'false\',tintOpacity:0.5,lensOpacity:0.5,softFocus:false,smoothMove:3,showTitle:false,titleOpacity:0.5" style="position: relative; display: block;" title="Alanzo 1" class="woocommerce-main-image zoom cloud-zoom" itemprop="image" href="'+largeimagename11+'"><img style="display: block;" width="500" height="500" alt="Alanzo 1" class="attachment-shop_single wp-post-image" style="display: block;" src="'+largeimagename11+'"></a><div style="z-index: 999; position: absolute; width: 370px; height: 559px; left: 0px; top: 0px; cursor: move;" class="mousetrap"></div></div>');
			  j++;
			}else{//alert(idval);
			   var idval=imagedetail[2];
               htmlvalue +='<a class="zoom" rel="prettyPhoto[product-gallery]" href="'+largeimagename11+'" data-o_href="'+largeimagename11+'"><img class="attachment-shop_thumbnail" width="53" height="78" alt="image" src="'+largeimagename11+'" /></a>';
			}
		  }
		}
	$('.thumbnails').html(htmlvalue);
	thumbnailclick();
	/* my code */


    }
    var test =0;
    var defaultvar=$('#defaultvariation').val();
    if(defaultvar>0){
        var imagename_display = jQuery('#imagepath_display').val();
       	var mainvalue=jQuery('#imagepath_display').val();
	var img_path = mainvalue.split('@@@');
	var i = 0;
	var htmlvalue='';
	var j=0;
	for(i=0;i < img_path.length;i++)
		{
		 var imagedetail = img_path[i].split('===');;
		 //var largeimagename1=imagedetail[0].replace(".jpg",'');
		 var largeimagename11 = imagedetail[0];
		//var imagelarge=largeimagename11.replace('-400x400','-999x999');
		 if(imagedetail[1]== defaultvar && largeimagename11!='')
		  {
		      if(j==0){//large image
                        idval=imagedetail[2];
			
			$('#mainimage').html('<div id="wrap" style="top:0px;z-index:9999;position:relative;"><a rel="zoomWidth:\'540\',zoomHeight: \'500\',position:\'right\',adjustX:50,adjustY:0,tint:\'false\',tintOpacity:0.5,lensOpacity:0.5,softFocus:false,smoothMove:3,showTitle:false,titleOpacity:0.5" style="position: relative; display: block;" title="Alanzo 1" class="woocommerce-main-image zoom cloud-zoom" itemprop="image" href="'+largeimagename11+'"><img width="500" height="500" alt="Alanzo 1" class="attachment-shop_single wp-post-image" src="'+largeimagename11+'"></a><div style="z-index: 999; position: absolute; width: 370px; height: 559px; left: 0px; top: 0px; cursor: move;" class="mousetrap"></div></div>');
			 //alert(largeimagename11); 
                         j++;
			}else{
			  idval=imagedetail[2];
                          htmlvalue +='<a class="zoom" rel="prettyPhoto[product-gallery]" href="'+largeimagename11+'" data-o_href="'+largeimagename11+'"><img class="attachment-shop_thumbnail" width="53" height="78" alt="image" src="'+largeimagename11+'" /></a>';
			}
		  }
		}
        $('#defaultvariation').val('0');
	$('.thumbnails').html(htmlvalue);
        thumbnailclick();
        test=1;
    }
    var $variation_form = $('form.cart');

    $('.variations select', $variation_form).unbind();
    $('div.select-option').delegate('a', 'click', function(event) {
        event.preventDefault();
        var classname=$(this).parent().attr('class');
        var matches=classname.match('disabled');
        if(matches=='disabled'){
            return false;
        }
        var matches=classname.match('selected');
        if(matches=='selected'){
            return false;
        }
        var selectclass=classname.match('selected');
		var $the_option = $(this).closest('div.select-option');
		
		if($the_option.attr('data-value') > 0){
			//alert('size');
			$('td.labelbg').eq(1).html('<label for="pa_color">Size :<span>You selected <i>'+$the_option.attr('data-value')+'</i></span></label>').show();
		}else{
			//alert('color');
			$('td.labelbg').eq(0).html('<label for="pa_color">Color :<span>You selected <i>'+$the_option.attr('data-value')+'</i></span></label>').show();
		}
		

        var defaultvardetail=$('#defaultvariationdetail').val();
        if(defaultvardetail!='' && test!=1){
            var img_path = defaultvardetail.split('@@@');
            var i = 0;
            var htmlvalue='';
            var idval=0;
			var j=0;
            for(i=0;i < img_path.length;i++)
                    {
                     var imagedetail = img_path[i].split('===');
                     var largeimagename11 = imagedetail[0];

                     if((imagedetail[1]== $the_option.data('value')) && (idval==0 || idval==imagedetail[2]) && largeimagename11!='')
                      {
                          if(j==0){//large image
									idval=imagedetail[2];
						//alert(idval);
						$('#mainimage').html('<div id="wrap" style="top:0px;z-index:9999;position:relative;"><a rel="zoomWidth:\'540\',zoomHeight: \'500\',position:\'right\',adjustX:50,adjustY:0,tint:\'false\',tintOpacity:0.5,lensOpacity:0.5,softFocus:false,smoothMove:3,showTitle:false,titleOpacity:0.5" title="Alanzo 1" style="position: relative; display: block;" class="woocommerce-main-image zoom cloud-zoom" itemprop="image" href="'+largeimagename11+'"><img style="display: block;" width="500" height="500" alt="Alanzo 1" class="attachment-shop_single wp-post-image" src="'+largeimagename11+'"></a><div style="z-index: 999; position: absolute; width: 370px; height: 559px; left: 0px; top: 0px; cursor: move;" class="mousetrap"></div>');
						  //alert(largeimagename11);
                                                  j++;
						}else{
						  idval=imagedetail[2];
									  htmlvalue +='<a class="zoom" rel="prettyPhoto[product-gallery]" href="'+largeimagename11+'" data-o_href="'+largeimagename11+'"><img class="attachment-shop_thumbnail" width="53" height="78" alt="image" src="'+largeimagename11+'" /></a>';
						}
                      }
                    }
            $('#defaultvariation').val('0');
            $('.thumbnails').html(htmlvalue);
           thumbnailclick();
        }
        if($the_option.data('value')>0){
            $('#selectsize').hide();
            $('.staticwishlist').hide();
            $('.dynamicwishlist').show();
        }else{
            $('.staticwishlist').show();
            $('.dynamicwishlist').hide();
            //$('#selectsize').show();
        }
        if ($the_option.hasClass('disabled')) {
            return false;
        } else if ($the_option.hasClass('selected')) {
            $the_option.removeClass('selected');

            var select = $the_option.closest('div.select');
            select.data('value', '');
            $(this).parents('div.select').trigger('change', []);

        } else {

            var select = $(this).closest('div.select');
			//alert(select);
            $(select).find('div.select-option').removeClass('selected');
			$the_option.addClass('selected');

            if(selectvalue==''){
                selectvalue=$the_option.attr('data-value');								
            }
			//alert(selectvalue);
			//alert($the_option.attr('data-value'));
            //$('#singlevariation').show();
            jQuery('.swatch-wrapper').removeClass('out_stock');
            var arr = [ 's','m','l','xl','xxl','xxxl' ];
            var stockone=stockcheck.split('***');
            if(jQuery.inArray($the_option.attr('data-value'), arr)>=0){
               jQuery('#sizeval').val($the_option.attr('data-value'));
               jQuery('.swatch-wrapper').each(function(index,element){
                      for(var i=0;i<stockone.length;i++){//alert(stockone[i]);
                        var stocktwo=stockone[i].split('==');//alert(stocktwo[1]+"===="+stocktwo[0]+"===="+selectvalue);
                        if(stocktwo[2]==0 && stocktwo[1]==selectvalue){//alert(stocktwo[1]);
                            jQuery('.swatch-wrapper').each(function(index,element){
                                if(stocktwo[0]==$(element).attr('data-value')){
                                    jQuery(this).addClass('out_stock');
                                }
                            })
                        }
                    }
               })
            }else{
                for(var i=0;i<stockone.length;i++){
                    var stocktwo=stockone[i].split('==');
                    if(stocktwo[2]==0 && stocktwo[1]==$the_option.attr('data-value')){
                        jQuery('.swatch-wrapper').each(function(index,element){
                            if(stocktwo[0]==$(element).attr('data-value')){
                                jQuery(this).addClass('out_stock');
                            }
                        })
                    }
                }
            }
            select.data('value', $the_option.data('value') );
            $(this).parents('div.select').trigger('change', []);
            //$('#singlevariation').after().html('<span class="amount">'+$('#mainprice').html()+'</span>');
            //$('.stock').show();
        }

        //return false;

    });


    $('div.select', $variation_form).bind('change', function() {

    	$variation_form.trigger( 'woocommerce_variation_select_change' );

        var $parent = $(this).closest('.variation_form_section');
        $('select', $parent).each( function(index, element) {
            var optval = $(element).val();

            optval = optval.replace("'", "&#039;");
            optval = optval.replace('"', "&quot;");


            calculator.set_selected( $(element).data('attribute-name'), optval );
        });

        $('div.select', $parent).each( function(index, element) {
            calculator.set_selected( $(element).data('attribute-name'), $(element).data('value') );
        });

        var current_options = calculator.get_current();
        $('select', $parent).each( function(index, element) {
            var attribute_name = $(element).data('attribute-name');
            var avaiable_options = current_options[attribute_name];

            $(element).find('option:gt(0)').each(function(index, option) {
                var optval = $(option).val();

                optval = optval.replace("'", "&#039;");
                optval = optval.replace('"', "&quot;");

                if (!avaiable_options[ optval ] ) {
                    $(option).attr('disabled','disabled');
                } else {
                    $(option).removeAttr('disabled');
                }
            });
        });

        $('div.select', $parent).each( function(index, element) {
            var attribute_name = $(element).data('attribute-name');
            var avaiable_options = current_options[attribute_name];
            $(element).find('div.select-option').each(function(index, option) {
                if (!avaiable_options[ $(option).data('value') ] ) {
                    $(option).addClass('disabled','disabled');
                } else {
                    $(option).removeClass('disabled');
                }
            });
        });

        calculator.trigger_callbacks();

    });

    $('select', $variation_form).change(function() {
        var $parent = $(this).closest('.variation_form_section');
        $('select', $parent).each( function(index, element) {
            var optval = $(element).val();

            optval = optval.replace("'", "&#039;");
            optval = optval.replace('"', "&quot;");
            calculator.set_selected( $(element).data('attribute-name'), optval);
        });

        var current_options = calculator.get_current();
        $('select', $parent).each( function(index, element) {
            var attribute_name = $(element).data('attribute-name');
            var avaiable_options = current_options[attribute_name];

            $(element).find('option:gt(0)').each(function(index, option) {
                var optval = $(option).val();

                optval = optval.replace("'", "&#039;");
                optval = optval.replace('"', "&quot;");

                if (!avaiable_options[ optval ] ) {
                    $(option).attr('disabled','disabled');
                } else {
                    $(option).removeAttr('disabled');
                }
            });

        });

        $('div.select', $parent).each( function(index, element) {
            var attribute_name = $(element).data('attribute-name');
            var avaiable_options = current_options[attribute_name];

            $(element).find('div.select-option').each(function(index, option) {
                if (!avaiable_options[ $(option).data('value') ] ) {
                    $(option).addClass('disabled','disabled');
                } else {
                    $(option).removeClass('disabled');
                }
            });
        });

        calculator.trigger_callbacks();

    });
    //Fire defaults
    $('div.select-option[data-default=true]').find('a').click();
    $('select', 'form.cart').trigger('change', []);
    test=5;
});

function variation_manager(variations) {
    this.variations = variations;
    this.find_matching_variation = function(selected) {

        for (var v = 0;v<this.variations.length;v++) {
            var variation = this.variations[v];
            var matched = true;

            //Find any with an exact match.
            for( var attribute in variation.attributes ) {
                matched = matched & selected[attribute] != undefined && selected[attribute] == variation.attributes[attribute];
            }

            if (matched) {
                return variation;
            }
        }

        //An exact match was not found.   Find any with a wildcard match
        for (var v = 0;v<this.variations.length;v++) {
            var variation = this.variations[v];
            var matched = true;

            //Find any with an exact match.
            for( var attribute in variation.attributes ) {
                matched = matched & selected[attribute] != undefined && (selected[attribute] == variation.attributes[attribute] || variation.attributes[attribute] == '');
            }

            if (matched) {
                return variation;
            }
        }

        return false;
    }
}
/*
function zoom(){
    jQuery(".attachment-shop_single").elevateZoom({
		  zoomType : "lens",
		  lensShape : "round",
		  lensSize    :200
		});
}*/
function variation_calculator(keys, possibile, all_set_callback, not_all_set_callback) {
    this.recalc_needed = true;

    this.all_set_callback = all_set_callback;
    this.not_all_set_callback = not_all_set_callback;

    //The varioius variation key values available as configured in woocommerce.
    this.variation_keys = keys;

    //The actual variations that are configured in woocommerce.
    this.variations_available = possibile;

    //Stores the attribute + values that are currently available
    this.variations_current = {};

    //Stores the selected attributes + values
    this.variations_selected = {};

    this.reset_current = function( ) {
        for(var key in this.variation_keys) {
            this.variations_current[ key ] = {};
            for( var av = 0; av < this.variation_keys[key].length; av++ ) {
                this.variations_current[ key ][ this.variation_keys[key][av] ] = 0;
            }
        }
    };

    this.update_current = function( ) {
        this.reset_current();
		var value='';
        for( var i = 0; i < this.variations_available.length; i++ ) {
            for(var attribute in this.variations_available[ i ]) {
                var available_value = this.variations_available[ i ][attribute];
                var selected_value = this.variations_selected[attribute];
                if (selected_value && selected_value == available_value ) {
                    this.variations_current[ attribute ][ available_value ] = 1;//this is a currently selected attribute value
                } else {

                    var result = true;
                    //Loop though any other item that is selected, checking to see if any DO NOT match.
                    for(var other_selected_attribute in this.variations_selected) {

                        if (other_selected_attribute == attribute) {
                            //We are looking to see if any attribute that is selected will cause this to fail.
                            continue;
                        }

                        var other_selected_attribute_value = this.variations_selected[other_selected_attribute];
                        var other_available_attribute_value = this.variations_available[i][other_selected_attribute];
                        if ( other_selected_attribute_value ) {
                            if ( other_available_attribute_value ) {
                                if (other_selected_attribute_value != other_available_attribute_value) {
                                    result = false;
                                }
                            }
                        }
                    }

                    if (result) {
                        if (available_value) {
                            this.variations_current[ attribute ][ available_value ] = 1;
                        } else {
                            for (var av in this.variations_current[ attribute ]) {
                                this.variations_current[ attribute ][ av ] = 1;
                            }
                        }
                    }

                }
            }
        }

        this.recalc_needed = false;
    };

    this.get_current = function() {
        if (this.recalc_needed) {
            this.update_current();
        }

        return this.variations_current;
    };

    this.get_value_is_current = function( key, value ) {
        if (this.recalc_needed) {
            this.update_current();
        }

        return this.variations_current[ key ][ value ] === true;
    };

    this.reset_selected = function() {
        this.recalc_needed = true;
        this.variations_selected = [];
    }

    this.set_selected = function(key, value) {
        this.recalc_needed = true;
        this.variations_selected[ key ] = value;

    };

    this.get_selected = function() {
        return this.variations_selected;
    }

    this.trigger_callbacks = function(){
        var all_set = true;

        for (var key in this.variation_keys) {
            all_set = all_set & this.variations_selected[key] != undefined && this.variations_selected[key] != '';
        }

        if (all_set) {
            this.all_set_callback( this.variations_selected );
        } else {
            this.not_all_set_callback();
        }
    }
};