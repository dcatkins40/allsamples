jQuery(document).ready(function($) {

	function showResponsiveNav() {
		$(".menu-button").click(function(event) {
			event.preventDefault();
			$(".menu-main-nav-container").slideToggle();
		});
	}
	showResponsiveNav();
	
	function infographicClick() {
		$(".circle").click(function(event) {
			event.preventDefault();
			if(!$(this).hasClass("circle-click")) {
				$(".top-circle").animate({
					right: "65%"
				}, 500 );
				$(".left-circle").animate({
					right: "76%"
				}, 500 );
				$(".right-circle").animate({
					right: "55%"
				}, 500 );
				var circleData = $(this).data("circle");
				$(".circle").removeClass("circle-click");
				$(this).addClass("circle-click");
				$(".bullet-list").each(function() {
					var bulletData = $(this).data("bullet");
					if(circleData === bulletData) {
						$('.bullet-list').not($(this)).slideUp('slow', function() {
							$(this).slideToggle('slow');
						});
					}
				});
			} else {
				$(this).removeClass("circle-click");
				$(".top-circle").animate({
					right: "40%"
				}, 500 );
				$(".left-circle").animate({
					right: "50%"
				}, 500 );
				$(".right-circle").animate({
					right: "30%"
				}, 500 );
				$(".bullet-list").slideUp();
			}
		});
	}
	infographicClick();

	function caseStudyMouseEnter() {
		$(".case-study-container").mouseenter(function() {
			$(this).find("h1").css("color", "white");
			$(this).find("p").css("color", "white");
		});
	}
	caseStudyMouseEnter();

	function caseStudyMouseLeave() {
		$(".case-study-container").mouseleave(function() {
			$(this).find("h1").css("color", "#004990");
			$(this).find("p").css("color", "#004990");
		});
	}
	caseStudyMouseLeave();

	function caseStudyOpen() {
		$(".case-study-container").click(function(event) {
			event.preventDefault();
			var caseStudyKey = $(this).data("key");
			$(".case-study-container").fadeOut("slow", function() {
				$(".team-content").slideUp(function() {
					$(".case-study").each(function() {
						var caseKey = $(this).data("cskey");
						if(caseStudyKey === caseKey) {
							$(this).fadeIn("fast", function() {
								$(".team-content").slideDown();
							});
						}
					});
				});
			});
		});
	}
	caseStudyOpen();
});