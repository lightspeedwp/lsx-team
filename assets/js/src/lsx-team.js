/*
 * lsx-team.js
 */

(function($) {
	var $teamSlider = $('.wrap.container .lsx-team-block:not(.block-template-list)');

	$teamSlider.on('init', function(event, slick) {
		if (slick.options.arrows && slick.slideCount > slick.options.slidesToShow)
			$teamSlider.addClass('slick-has-arrows');
	});

	$teamSlider.on('setPosition', function(event, slick) {
		if (!slick.options.arrows) $teamSlider.removeClass('slick-has-arrows');
		else if (slick.slideCount > slick.options.slidesToShow)
			$teamSlider.addClass('slick-has-arrows');
	});

	$teamSlider.slick({
		draggable: false,
		infinite: true,
		swipe: false,
		cssEase: 'ease-out',
		dots: true,
		responsive: [
			{
				breakpoint: 992,
				settings: {
					slidesToShow: 3,
					slidesToScroll: 3,
					draggable: true,
					arrows: false,
					swipe: true,
				},
			},
			{
				breakpoint: 768,
				settings: {
					slidesToShow: 1,
					slidesToScroll: 1,
					draggable: true,
					arrows: false,
					swipe: true,
				},
			},
		],
	});

	$('.single-team a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
		$(
			'#lsx-services-slider, #lsx-projects-slider, #lsx-products-slider, #lsx-testimonials-slider, #lsx-team-slider, .lsx-blog-customizer-posts-slider, .lsx-blog-customizer-terms-slider'
		).slick('setPosition');
	});
})(jQuery);
