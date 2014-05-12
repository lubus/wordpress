(
	function(){

		tinymce.create(
			"tinymce.plugins.EshopBoxShortcodes",
			{
				init: function(d,e) {},
				createControl:function(d,e)
				{

					var ed = tinymce.activeEditor;

					if(d=="eshopbox_shortcodes_button"){

						d=e.createMenuButton( "eshopbox_shortcodes_button",{
							title: ed.getLang('eshopbox.insert'),
							icons: false
							});

							var a=this;d.onRenderMenu.add(function(c,b){

								a.addImmediate(b, ed.getLang('eshopbox.order_tracking'),"[eshopbox_order_tracking]" );
								a.addImmediate(b, ed.getLang('eshopbox.price_button'), '[add_to_cart id="" sku=""]');
								a.addImmediate(b, ed.getLang('eshopbox.product_by_sku'), '[product id="" sku=""]');
								a.addImmediate(b, ed.getLang('eshopbox.products_by_sku'), '[products ids="" skus=""]');
								a.addImmediate(b, ed.getLang('eshopbox.product_categories'), '[product_categories number=""]');
								a.addImmediate(b, ed.getLang('eshopbox.products_by_cat_slug'), '[product_category category="" per_page="12" columns="4" orderby="date" order="desc"]');

								b.addSeparator();

								a.addImmediate(b, ed.getLang('eshopbox.recent_products'), '[recent_products per_page="12" columns="4" orderby="date" order="desc"]');
								a.addImmediate(b, ed.getLang('eshopbox.featured_products'), '[featured_products per_page="12" columns="4" orderby="date" order="desc"]');

								b.addSeparator();

								a.addImmediate(b, ed.getLang('eshopbox.shop_messages'), '[eshopbox_messages]');

								b.addSeparator();

								c=b.addMenu({title:ed.getLang('eshopbox.pages')});
										a.addImmediate(c, ed.getLang('eshopbox.cart'),"[eshopbox_cart]" );
										a.addImmediate(c, ed.getLang('eshopbox.checkout'),"[eshopbox_checkout]" );
										a.addImmediate(c, ed.getLang('eshopbox.my_account'),"[eshopbox_my_account]" );
										a.addImmediate(c, ed.getLang('eshopbox.edit_address'),"[eshopbox_edit_address]" );
										a.addImmediate(c, ed.getLang('eshopbox.change_password'),"[eshopbox_change_password]" );
										a.addImmediate(c, ed.getLang('eshopbox.view_order'),"[eshopbox_view_order]" );
										a.addImmediate(c, ed.getLang('eshopbox.pay'),"[eshopbox_pay]" );
										a.addImmediate(c, ed.getLang('eshopbox.thankyou'),"[eshopbox_thankyou]" );

							});
						return d

					} // End IF Statement

					return null
				},

				addImmediate:function(d,e,a){d.add({title:e,onclick:function(){tinyMCE.activeEditor.execCommand( "mceInsertContent",false,a)}})}

			}
		);

		tinymce.PluginManager.add( "EshopBoxShortcodes", tinymce.plugins.EshopBoxShortcodes);
	}
)();