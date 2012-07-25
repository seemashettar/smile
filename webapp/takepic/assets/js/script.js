$(document).ready(function(){
	
	var camera = $('#camera'),
		photos = $('#photos'),
		screen =  $('#screen');

	var template = '<a href="uploads/original/{src}" rel="cam" '
		+'style="background-image:url(uploads/thumbs/{src})"></a>';

	/*----------------------------------
		Setting up the web camera
	----------------------------------*/


	webcam.set_swf_url('assets/webcam/webcam.swf');
	webcam.set_api_url('upload.php');	// The upload script
	webcam.set_quality(80);				// JPEG Photo Quality
	webcam.set_shutter_sound(true, 'assets/webcam/shutter.mp3');

	// Generating the embed code and adding it to the page:	
	screen.html(
		webcam.get_html(screen.width(), screen.height())
	);


	/*----------------------------------
		Binding event listeners
	----------------------------------*/


	var shootEnabled = false;
		
	$('#shootButton').click(function(){
		
		if(!shootEnabled){
			return false;
		}
		
		webcam.freeze();
		togglePane();
		return false;
	});
	
	$('#cancelButton').click(function(){
		webcam.reset();
		togglePane();
		return false;
	});
	
	$('#uploadButton').click(function(){
		webcam.upload();
		webcam.reset();
		togglePane();
		return false;
	});

	camera.find('.settings').click(function(){
		if(!shootEnabled){
			return false;
		}
		
		webcam.configure('camera');
	});

	// Showing and hiding the camera panel:
	
	var shown = false;
	$('.camTop').click(function(){
		
		$('.tooltip').fadeOut('fast');
		
		if(shown){
			camera.animate({
				bottom:-466
			});
		}
		else {
			camera.animate({
				bottom:-5
			},{easing:'easeOutExpo',duration:'slow'});
		}
		
		shown = !shown;
	});

	$('.tooltip').mouseenter(function(){
		$(this).fadeOut('fast');
	});


	/*---------------------- 
		Callbacks
	----------------------*/
	
	
	webcam.set_hook('onLoad',function(){
		// When the flash loads, enable
		// the Shoot and settings buttons:
		shootEnabled = true;
	});
	
	webcam.set_hook('onComplete', function(msg){
		
		// This response is returned by upload.php
		// and it holds the name of the image in a
		// JSON object format:
		
		msg = $.parseJSON(msg);
		
		if(msg.error){
			alert(msg.message);
		}
		else {
			// Adding it to the page;
			console.log(msg.original);
			useFaceAPI(msg.original);
			photos.prepend(templateReplace(template,{src:msg.filename}));
			initFancyBox();
		}
	});
	
	webcam.set_hook('onError',function(e){
		screen.html(e);
	});

	/*-------------------------------------
		Use face api to see if the user is happy or sad
	-------------------------------------*/	
	function useFaceAPI(picURL){
		console.log("sending the picurl");
		$.getJSON("http://api.face.com/faces/detect.json?callback=?",
		  {
            urls: picURL,
            api_key: "65d1f286b1aef896852b7ffbdbeedcbb",
            api_secret: "d39d306a5c9bc99690bd47b5761726c8",
            detector: "Aggressive",
            attributes: "all"
		  },
		  function(data) {
		    $.each(data.photos, function(i,item){
                try
                {
                    console.log(item.tags[0].attributes.mood.value);
		    $('#emotion').html(item.tags[0].attributes.mood.value);
		    //$('#emotionmessage').click();
                    saveFaceOutput(item.tags[0].attributes.mood.value);
                }
                catch(error)
                {
                    console.log(error);
                }

		    });
		  });
	}

    function saveFaceOutput(emotion)
    {    
		$.getJSON("../process.php",
		  {
            emotion : emotion
		  },
		  function(data) {
		    console.log(data);
          });
    }

	function useFace(filename){
/*		var data = canvas.toDataURL('image/jpeg', 1.0);
		newblob = dataURItoBlob(data);
		console.log("haha"); */
		file = "";
		$.get(filename, function(response, status, xhr) {
		  if (status == "error") {
		    var msg = "Sorry but there was an error: ";
		    $console.log(msg + xhr.status + " " + xhr.statusText);
		  }
			console.log(response);
			
			file = response.toDataURL('image/jpeg', 1.0);;
		});
		newblob = dataURItoBlob(file);
		var formdata = new FormData();
		formdata.append("api_key", '65d1f286b1aef896852b7ffbdbeedcbb');
		formdata.append("api_secret", 'd39d306a5c9bc99690bd47b5761726c8');
		formdata.append("filename","temp.jpg");
		formdata.append("file",newblob);

		console.log(formdata);
		$.ajax({
				 url: 'http://api.face.com/faces/detect.json',
				 data: formdata,
				 cache: false,
				 contentType: false,
				 processData: false,
				 dataType:"json",
				 type: 'POST',
				 success: function (data) {
				     console.log(data.photos[0]);
				 }
		 });

	}

	//credit http://stackoverflow.com/a/8782422/52160
	function dataURItoBlob(dataURI, callback) {
		// convert base64 to raw binary data held in a string
		// doesn't handle URLEncoded DataURIs
		var byteString;
		if (dataURI.split(",")[0].indexOf("base64") >= 0) {
		    byteString = atob(dataURI.split(",")[1]);
		} else {
		    byteString = unescape(dataURI.split(",")[1]);
		}
		// separate out the mime component
		var mimeString = dataURI.split(",")[0].split(":")[1].split(";")[0];
		// write the bytes of the string to an ArrayBuffer
		var ab = new ArrayBuffer(byteString.length);
		var ia = new Uint8Array(ab);
		for (var i = 0; i < byteString.length; i++) {
		    ia[i] = byteString.charCodeAt(i);
		}
		// write the ArrayBuffer to a blob, and you're done
		var BlobBuilder = window.WebKitBlobBuilder || window.MozBlobBuilder;
		var bb = new BlobBuilder();
		bb.append(ab);
		return bb.getBlob(mimeString);
	}
	
	/*-------------------------------------
		Populating the page with images
	-------------------------------------*/
	
	var start = '';
	
	function loadPics(){
	
		// This is true when loadPics is called
		// as an event handler for the LoadMore button:
		
		if(this != window){
			if($(this).html() == 'Loading..'){
				// Preventing more than one click
				return false;
			}
			$(this).html('Loading..');
		}
		
		// Issuing an AJAX request. The start parameter
		// is either empty or holds the name of the first
		// image to be displayed. Useful for pagination:
		
		$.getJSON('browse.php',{'start':start},function(r){
			
			photos.find('a').show();
			var loadMore = $('#loadMore').detach();
			
			if(!loadMore.length){
				loadMore = $('<span>',{
					id			: 'loadMore',
					html		: 'Load More',
					click		: loadPics
				});
			}
			
			$.each(r.files,function(i,filename){
				photos.append(templateReplace(template,{src:filename}));
			});

			// If there is a next page with images:			
			if(r.nextStart){
				
				// r.nextStart holds the name of the image
				// that comes after the last one shown currently.
				
				start = r.nextStart;
				photos.find('a:last').hide();
				photos.append(loadMore.html('Load More'));
			}
			
			// We have to re-initialize fancybox every
			// time we add new photos to the page:
			
			initFancyBox();
		});
		
		return false;
	}

	// Automatically calling loadPics to
	// populate the page onload:
	
	loadPics();
	

	/*----------------------
		Helper functions
	------------------------*/

	
	// This function initializes the
	// fancybox lightbox script.
	
	function initFancyBox(filename){
		photos.find('a:visible').fancybox({
			'transitionIn'	: 'elastic',
			'transitionOut'	: 'elastic',
			'overlayColor'	: '#111'
		});
	}


	// This function toggles the two
	// .buttonPane divs into visibility:
	
	function togglePane(){
		var visible = $('#camera .buttonPane:visible:first');
		var hidden = $('#camera .buttonPane:hidden:first');
		
		visible.fadeOut('fast',function(){
			hidden.show();
		});
	}
	
	
	// Helper function for replacing "{KEYWORD}" with
	// the respectful values of an object:
	
	function templateReplace(template,data){
		return template.replace(/{([^}]+)}/g,function(match,group){
			return data[group.toLowerCase()];
		});
	}
});
