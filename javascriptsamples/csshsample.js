jQuery(document).ready(function($){

	$('#slider-1').bxSlider({
	controls: false,
	  auto: true
	});

	$('#slider2').bxSlider({
	  nextSelector: '#home-next',
	  prevSelector: '#home-prev',
	  nextText: '',
	  prevText: '',
	  pager: false
	});

	$('#news-slide').bxSlider({
	  nextSelector: '#news-next',
	  prevSelector: '#news-prev',
	  nextText: '',
	  prevText: '',
	  pager: false
	});

	$('#events-slide').bxSlider({
	  nextSelector: '#news-next',
	  prevSelector: '#news-prev',
	  nextText: '',
	  prevText: '',
	  pager: false
	});

	function moveTweets(){
		$('.twitter-user').remove();
		$('.dw-twitter-inner').leanSlider({
			  directionNav: '#slider-direction-nav',
			  pauseTime: false
			}); 
		}
	moveTweets();

	function blogPlaceholder(){
		var blogNumber = $('#all-blog-entries').children().length;

		if(blogNumber === 1){
			//alert('only 1 here!!!');
			$('#all-blog-entries').append( "<article class='post' style='border-bottom:none;'><img src='http://websitestaging.com/neu-cssh/wp-content/uploads/2013/10/placeholder.jpg' /></article>" );
			}
		}
	blogPlaceholder();

	function removeBackground(){
		$('.page-name-only').siblings().find('#right-content').addClass('alt-inner');
		$('.page-name-only').siblings().find('.in-section').addClass('top-border');
	}
	removeBackground();

	function deptDropdown(){
		$('#dept-drop').on('click', function(){
			$('ul#dept-list').slideToggle('fast');
		});
	}
	deptDropdown();
  
	function faqs(){
		$('.faq-button, .question').on('click', function(){
			var faqBlock = $(this).parent();
			if( faqBlock.hasClass('answer-open') ){
				faqBlock.addClass('answer-open').find('.answer').slideToggle();
				faqBlock.children().find('span.the-QFA').css('color', "#4f4f4f");
				$(this).css('font-weight', "normal");
				faqBlock.removeClass('answer-open');
			}
			else{
				faqBlock.addClass('answer-open').find('.answer').slideToggle();
				faqBlock.children().find('span.the-QFA').css('color', "#c00");
				$(this).css('font-weight', "bold");
			}
		});
	}
	faqs();
  
	function centerPlusSign(){
		var faqHeight = $('.faq-wrap').height();
		var faqDif = (faqHeight / 2) - 8;
		$('.faq-button').css('top', faqDif);
	}
	centerPlusSign();


  
	function loadProfile(){
		$('a.faculty-wrap').click(function (e) {
			e.preventDefault();
			var modalUrl = $(this).attr('href');
			$('#myModal').load(modalUrl).reveal({
				animation: 'fade',
				animationspeed: 300,
				closeonbackgroundclick: true,
				dismissmodalclass: 'close-reveal-modal'
			});
		});
	}
	loadProfile();
  
	function loadBlog(){
		$('a.the-blog-entry').click(function (e) {
			e.preventDefault();
			var modalUrl = $(this).attr('href');
			$('#myModal').load(modalUrl/*+ ' article'*/).reveal({
				animation: 'fade',
				animationspeed: 300,
				closeonbackgroundclick: true,
				dismissmodalclass: 'close-reveal-modal'
			});
		});
	}
	loadBlog();

	function loadEvent(){
		$('a.the-event-entry, .past-popUp').click(function (e) {
			e.preventDefault();
			var modalUrl = $(this).attr('href');
			$('#myModal').load(modalUrl).reveal({
				animation: 'fade',
				animationspeed: 300,
				closeonbackgroundclick: true,
				dismissmodalclass: 'close-reveal-modal'
			});
		});
	}
	loadEvent();

	function addvid(){
		$('a.read-more').each(function() {
			if ($(this).attr('href')) {
			} else {
				$(this).attr('data-reveal-id', 'vidModal');
			}
		}); 
	}
	addvid();


	function loadVid(){
		$('a.read-more').click(function (e) {
			var modalUrl = $(this).attr('href');
			var vidUrl = $(this).attr('data-vid');
			if( modalUrl === ""){
				e.preventDefault();
				$('#vidModal').children().find('#iframe').attr('src', vidUrl );
				$('#vidModal').reveal({
					animation: 'fade',
					animationspeed: 300,
					closeonbackgroundclick: true,
					dismissmodalclass: 'close-reveal-modal'
				});
			}
		});
	}
	loadVid();
  
	$('a.close-reveal-modal').bind('click', function() {
		$('#vidModal').children().find('#iframe').attr('src', '' );
	});
  
	function equalHeight(){
		var maxHeight = -1;
		$('.call-out').each(function() {
			maxHeight = maxHeight > $(this).height() ? maxHeight : $(this).height();
		});
		$('.call-out').each(function() {
			$(this).height(maxHeight);
		});
	}
	equalHeight();

	function navMenu(){
		$('h1.menu-toggle').on('click', function() {
			$('#menu-main-nav').slideToggle();
		});
	}
	navMenu();

	function responsiveNav(){
		$( window ).resize(function() {
			var windowW = $(window).width();
			if( windowW > 767) {
				$('ul#menu-main-nav').css('display','block');
			} else {
				$('ul#menu-main-nav').css('display','none');
			}
		});
	}
	responsiveNav();

	function addWidth(){
		var innerW = $('#right-content').width();
		if( innerW < 708){
			$('#right-content').append('<div class="expand-width"></div>');
		}
	}
	addWidth();
});