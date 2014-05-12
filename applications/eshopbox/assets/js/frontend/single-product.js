jQuery(document).ready(function($) {

	// Tabs
	$('.eshopbox-tabs .panel').hide();

	$('.eshopbox-tabs ul.tabs li a').click(function(){

		var $tab = $(this);
		var $tabs_wrapper = $tab.closest('.eshopbox-tabs');

		$('ul.tabs li', $tabs_wrapper).removeClass('active');
		$('div.panel', $tabs_wrapper).hide();
		$('div' + $tab.attr('href'), $tabs_wrapper).show();
		$tab.parent().addClass('active');

		return false;
	});

	$('.eshopbox-tabs').each(function() {
		var hash = window.location.hash;
		if (hash.toLowerCase().indexOf("comment-") >= 0) {
			$('ul.tabs li.reviews_tab a', $(this)).click();
		} else {
			$('ul.tabs li:first a', $(this)).click();
		}
	});

	// Star ratings for comments
	$('#rating').hide().before('<p class="stars"><span><a class="star-1" href="#">1</a><a class="star-2" href="#">2</a><a class="star-3" href="#">3</a><a class="star-4" href="#">4</a><a class="star-5" href="#">5</a></span></p>');

	$('body')
		.on( 'click', '#respond p.stars a', function(){
			var $star   = $(this);
			var $rating = $(this).closest('#respond').find('#rating');

			$rating.val( $star.text() );
			$star.siblings('a').removeClass('active');
			$star.addClass('active');

			return false;
		})
		.on( 'click', '#respond #submit', function(){
			var $rating = $(this).closest('#respond').find('#rating');
			var rating  = $rating.val();

			if ( $rating.size() > 0 && ! rating && eshopbox_params.review_rating_required == 'yes' ) {
				alert(eshopbox_params.i18n_required_rating_text);
				return false;
			}
		});

	// prevent double form submission
	$('form.cart').submit(function(){
		$(this).find(':submit').attr( 'disabled','disabled' );
	});

});