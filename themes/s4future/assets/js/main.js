

(function($) {
	skel.breakpoints({
		xlarge: '(min-width: 1680px)',
		large: '(max-width: 1280px)',
		medium: '(max-width: 1024px)',
		small: '(max-width: 736px)',
		xsmall: '(max-width: 480px)'
	});

	$(function() {
		var lastId, $nav_a = $('#nav').find('a[href^="/#"]');

		$nav_a
			.each(function () {
			$(this).attr('id', $(this).attr("href").substring(2)+'-link');
		});

		$('#nav a[href^="/#"]').on('click', function(event) {
			var link = this.getAttribute('href').substring(1);
			var target = $(link);
			if( target.length ) {
				event.preventDefault();
				$('html, body').stop().animate({
					scrollTop: target.offset().top
				}, 1000);
			}
		});

		var scrollItems = $nav_a.map(function(){
			var item = $($(this).attr("href").substring(1));
			if (item.length) { return item; }
		});
		// Bind to scroll
		$(window).scroll(function(){
			var position = $(this).scrollTop()+200;

			// Get id of current scroll item
			var cur = scrollItems.map(function(){
				if ($(this).offset().top < position)
					return this;
			});

			// Get the id of the current element
			cur = cur[cur.length-1];
			var id = cur && cur.length ? cur[0].id : "";
			if (lastId !== undefined || lastId !== id) {
				lastId = id;

				$("#"+lastId+"-link").addClass('active');
				$("#"+lastId+"-link").parent().prev().find("a").removeClass('active');
				$("#"+lastId+"-link").parent().next().find("a").removeClass('active');

			}
		});


		var $body = $('body'),
			$header = $('#header'),
			$wrapper = $('#wrapper');

		// Fix: Placeholder polyfill.
		$('form').placeholder();

		// Prioritize "important" elements on medium.
		skel.on('+medium -medium', function() {
			$.prioritize(
				'.important\\28 medium\\29',
				skel.breakpoint('medium').active
			);
		});

		// Title Bar.
		$(
			'<div id="titleBar">' +
			'<a href="#header" class="toggle"></a>' +
			'<span class="title">' +$('#date').text()+"&nbsp;&nbsp;"+ $('#logo').html() + '</span>' +
			'</div>'
		)
			.appendTo($body);

		// Header.
		$('#header')
			.panel({
				delay: 500,
				hideOnClick: true,
				hideOnSwipe: true,
				resetScroll: true,
				resetForms: true,
				side: 'right',
				target: $body,
				visibleClass: 'header-visible'
			});

		// Fix: Remove navPanel transitions on WP<10 (poor/buggy performance).
		if (skel.vars.os == 'wp' && skel.vars.osVersion < 10)
			$('#titleBar, #header, #wrapper')
				.css('transition', 'none');

	});

	$.fn.toggleText = function(t1, t2){
		if (this.text() == t1) this.text(t2);
		else                   this.text(t1);
		return this;
	};

	// when a tab is clicked
	$('.hideSeekTab').on('click', function() {
		// if the one you clicked is open,
		if ($(this).find('.text').hasClass('open')) {
			// then close it.
			$('.hideSeekTab .open').slideToggle().removeClass('open');
			$('.iconBox').removeClass('closed');
			// otherwise,
		} else {
			// close all tabs,
			$('.hideSeekTab .open').slideToggle().removeClass('open');
			// and open the one you clicked
			$(this).find('.text').slideToggle().addClass('open');
			$('.iconBox').removeClass('closed');
			$(this).find('.iconBox').addClass('closed');
		}
	});

	$("#traducao-btn" ).click(function() {
		$( "#traducao" ).toggle("blind");
		$(this).toggleText('Oculta a carta', 'Abre a carta');
	});
})(jQuery);