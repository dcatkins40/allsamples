jQuery(document).ready(function($){
	var player;
	var modVP;
	var nextVideo = 0;
	var videos = video_id_array;
	var video_id_array;
	var bcplayer = {};
	bcplayer.onTemplateLoad = {};
	bcplayer.onTemplateReady = {};

	if(window.innerWidth > 768){
	    bcplayer.onTemplateLoad = function(ex){
	        bcplayer.player = brightcove.getExperience(ex);
	        bcplayer.APIModules = brightcove.api.modules.APIModules;
	        bcplayer.videoPlayer = bcplayer.player.getModule(bcplayer.APIModules.VIDEO_PLAYER);
	        jQuery('<div id="playOverlay" />').appendTo('.videoHolder').click(function(e){
	            e.preventDefault();
	            jQuery(this).fadeOut('slow', function(){
	                bcplayer.videoPlayer.play();
	            });
	        });
	    }
	    bcplayer.onTemplateReady = function(e){
	    }
	}

	function myTemplateLoaded(experienceID) {   
		player = brightcove.api.getExperience(experienceID);   
		modVP = player.getModule(brightcove.api.modules.APIModules.VIDEO_PLAYER);
	}  

	function onTemplateReady(evt) {
		modVP.loadVideoByID(videos[nextVideo]);   
		modVP.addEventListener(brightcove.api.events.MediaEvent.BEGIN, onMediaBegin);   
		modVP.addEventListener(brightcove.api.events.MediaEvent.COMPLETE, onMediaComplete); 
	}

	function onMediaComplete(evt) {    
		nextVideo++;    
		/*if (nextVideo == videos.length) {     
			nextVideo = 0;    
		}*/
		modVP.loadVideoByID(videos[nextVideo]);
	}

	function onMediaBegin(evt) {   
		document.getElementById("mediaInfo").innerHTML = evt.media.displayName; 
	}

	function deletePlaylist() {
		$('.deletePlaylist').click(function(event) {
			event.preventDefault();
			if(confirm("Are you sure you want to delete this playlist?")) {
				var playlistID = $(this).attr('rel');
				var data = {
					delete_playlist: playlistID
				}
				var playlistFadeout = $(this).parent().fadeOut('slow');
				$.post('<?php bloginfo('stylesheet_directory'); ?>/process.php', data, function(response) {
					$('#wrapper').append(response);
					playlistFadeout;
				});
			}
		});
	}
	deletePlaylist();

	function displayDropdown() {
	    $('.dropdown').click(function(event) {
	        event.preventDefault();
	        $('.playlistHidden').slideUp('slow');
	        $('.playlist-header').removeClass('header-background-open').addClass('header-background');
	        if($(this).find('.playlistHidden').css('display') === 'none') {
	            $(this).find('.playlistHidden').slideToggle('slow', function(){
	            	positionFooter();
	            });
	            $(this).removeClass('header-background').addClass('header-background-open');
	        }
	    });
	}
	displayDropdown();

	function deleteVideo() {
	    $('.deleteVideo').click(function(event) {
	    	event.preventDefault();
	    	if(confirm("Are you sure you want to delete this video?")) {
	    		var videoID = $(this).attr('rel');
	    		var playlistID = $(this).find('.deletePlaylist').attr('rel');
	    		var deleteVideoData = {
	    			delete_video: videoID,
	    			playlist_id_delete_video: playlistID
	    		}
	    		var videoFadeout = $(this).parent().fadeOut('slow');
	    		$.post('<?php bloginfo('stylesheet_directory'); ?>/process.php', deleteVideoData, function(response) {
	    			videoFadeout;
	    		});
	    	}
	    });
	}
	deleteVideo();

	function savePlaylist() {
		$('.savePlaylist').click(function(event) {
			event.preventDefault();
			var videoArray = new Array();
			var playlistID = $(this).find('.deletePlaylist').attr('rel');
			$(this).find('.ui-state-default').each(function(index) {
				var video = $(this).find('.deleteVideo').attr('rel');
				videoArray.push(video);
			});
			var reorderData = {
				array_values: videoArray,
				playlist_id_reorder: playlistID
			}
			$.post('<?php bloginfo('stylesheet_directory'); ?>/process.php', reorderData, function(response) {
	    		alert('Your playlist has been saved!');
			});
		});
	}
	savePlaylist();

	function playAll() {
		$('.playAll').click(function(event) {
			event.preventDefault();
			var pageIDArray = new Array();
			$(this).find('.ui-state-default').each(function(index) {
				var video = $(this).find('.deleteVideo').attr('rel');
				pageIDArray.push(video);
			});
			var videoIDData = {
				page_id_array: pageIDArray
			}
			$.post('<?php bloginfo('stylesheet_directory'); ?>/process.php', videoIDData, function(response) {
				video_id_array = response;
				video_id_array = video_id_array.substr(1);
				video_id_array = video_id_array.substr(0, video_id_array.length - 1);
				video_id_array = video_id_array.split(',');
				$('#colorOverlay').css('display', 'block');
				$('#popupVideo').toggle('slow');
				$('#popupVideo').trigger('popup_action');
			});
			$('#popupVideo').bind('popup_action', function(event) {
				videos = video_id_array;
			});
		});
	}
	playAll();
});