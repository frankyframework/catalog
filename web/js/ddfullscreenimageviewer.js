// jQuery Full Screen Image Viewer
// By Dynamic Drive: http://www.dynamicdrive.com
// June 12th 17'- Creation Date

;(function($){

	// Configure CSS media query condition to disable script. See http://www.javascriptkit.com/dhtmltutors/cssmediaqueries.shtml for possible conditions
	var disablescriptmql = window.matchMedia? window.matchMedia("screen and (max-device-width: 100px)") : {matches: false, addListener: function(){}}
	var defaults = {scale: 1}
	var $canvasref = null
	var $fullscreenimagearea = null
	var $closebutton = null
	var $loadingdiv = null
	var canvashtml = '<div id="fullscreencanvas"><div id="fullscreenimagearea"></div></div><div id="fullimageloadingdiv"><div class="spinner"></div></div><div id="closeviewer" title="Close Viewer">Close</div>'
	var url =  window.urlSketchbook || window.location.href;
	function calfullscreenscale(imgref){
		var scale = Math.min(window.innerWidth/imgref.width, window.innerHeight/imgref.height)
		return scale
	}


	function fullscreenimage($img, setting){ // fullscreenimage plugin function
		if (!$canvasref){
			$(document.body).append(canvashtml)
			$canvasref = $('#fullscreencanvas')
			$fullscreenimagearea = $('#fullscreenimagearea')
			$closebutton = $('#closeviewer')
			$loadingdiv = $('#fullimageloadingdiv')

			$closebutton.on('click', function(){
				$(document.body).removeClass('revealviewer')
				$fullscreenimagearea.data('$largeimage').unbind()
				$fullscreenimagearea.empty()
				$loadingdiv.css('visibility', 'hidden')
				history.pushState({}, null, url);
			})
		}
		var zoomioscale = $img.attr('data-scale') || setting.scale
		var descriptionimage = $img.attr('alt');
		var share = $img.attr('data-share');
		
		$img.on('click', function(){
			var largeimagesrc = $img.attr('data-large') || setting.largeimage || $img.attr('src')
			$(document.body).addClass('revealviewer')
			if (this.getAttribute('data-large')){
				$loadingdiv.css('visibility', 'visible')
			}
			var largeimage = new Image()
			var $largeimage = $(largeimage)
			$largeimage.on('load', function(){ // whenlarge image loads
				var scale = calfullscreenscale(largeimage)
				$fullscreenimagearea.html( '<img src="' + largeimage.src + '" style="width:' + largeimage.width*scale + 'px; height:' + largeimage.height*scale + 'px;" />' )
				$loadingdiv.css('visibility', 'hidden')
				if (zoomioscale > 1){
					var $fullscreenimage = $fullscreenimagearea.find('img:eq(0)')
					$fullscreenimage.zoomio({scale:zoomioscale, fixedcontainer: true})
				}
				$fullscreenimagearea.append('<div id="descriptionimage" style="width:' + largeimage.width*scale + 'px;"></div>');
				if(descriptionimage.length > 0) {
					$("#descriptionimage").append('<p>'+descriptionimage+'</p>');
				}

				$("#descriptionimage").append('<ul class="ul_controles share"><li class="_srare_detalle"><div class="sharethis-inline-share-buttons" data-url="'+window.location.origin+share+'"></div></li></ul>');
				//window.__sharethis__.initialize();
				history.pushState({}, null, share);
			})
			largeimage.src = largeimagesrc
			$fullscreenimagearea.data('$largeimage', $largeimage)
		})
	}


	$.fn.fullscreenimage = function(options){ // set up jquery fullscreenimage plugin
		var s = $.extend({}, defaults, options)
		return this.each(function(){ //return jQuery obj
			var $target = $(this)

			$target = ($target.is('img'))? $target : $target.find('img:eq(0)')
			if ($target.length == 0 || disablescriptmql.matches){
				return true
			}
			fullscreenimage($target, s)
		}) // end return this.each

	}

})(jQuery);