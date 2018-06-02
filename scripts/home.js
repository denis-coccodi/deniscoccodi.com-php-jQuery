$(document).ready(function () {

	if (isSafari()) {

		$("#homePageTitle").css("opacity", "1");
		$(".polaroidFrame").css("opacity", "1");
		$(".polaroidPic").css("opacity", "1");
		$("#homePageTitle").css({
			"-ms-animation": "none 0 ease 0 1 normal none running",
			"-webkit-animation": "none 0 ease 0 1 normal none running",
			"animation": "none 0 ease 0 1 normal none running"
		});

	}
	
	var i=0,
	angle = 0;

	populatePolaroidFrames();
	sendOut(i, angle);
	
});

$(document).ready(function () {

	$(window).resize(function () {

		populatePolaroidFrames();
		
	}).resize();
	
});

function sendOut(i, angle) {
	
		$("#polaDiv_" + i).addClass("orientation" + angle);
		$("#polaDiv_" + i + " .polaroidFrame").css({
			"-ms-animation": "scaleUp 2s forwards ease-out, suddenFadeIn 2s forwards ease-out",
			"-webkit-animation": "scaleUp 2s forwards ease-out, suddenFadeIn 2s forwards ease-out",
			"animation": "scaleUp 2s forwards ease-out, suddenFadeIn 2s forwards ease-out"
		});
	
		angle += 45;
		i++;
		if(i<8){	
			
			setTimeout(function() { sendOut(i, angle) }, 200);
			
		}
	
		if (i < 9) {
			
			setTimeout(function() { fadeInPics("#polaDiv_" + (i - 1) + " .polaroidPic");}, 1500);
			
		}
	
		if(i == 8) {
			
			setTimeout(function() {fadeInPics("#homePageTitle");}, 1700 );
			
		}
	
}

function fadeInPics(element) {

	
	$(element).css({
		"-ms-animation": "fadeIn 2s forwards ease-out",
		"-webkit-animation": "fadeIn 2s forwards ease-out",
		"animation": "fadeIn 2s forwards ease-out"
	});
	
}

function populatePolaroidFrames() {
	
	for(var i=0; i < 8; i++) {
	
		ratio = getRatio('./images/home/polaroids/' + i + '.jpg', i, function(ret){
			
			setRatios(ret);
			
		});
		
	}
	
}

function setRatios(ret) {
		
		var i = ret[0]["i"],
		width = ret[0]["width"],
		height = ret[0]["height"],
		ratio = 0;

		if(width > height) { //landscape case

			ratio = width/height;
			$("#polaDiv_" + i + " .polaroidPic").css({
				'background' :  'url(./images/home/polaroids/' + i + '.jpg) no-repeat center center',
				'-o-background-size': $("#polaDiv_" + i + " .polaroidPic").height() * ratio + 'px 100%',
				'-webkit-background-size': $("#polaDiv_" + i + " .polaroidPic").height() * ratio + 'px 100%',
				'background-size': $("#polaDiv_" + i + " .polaroidPic").height() * ratio + 'px 100%'
			});

		} else { //portrait case

			ratio = height/width;
			$("#polaDiv_" + i + " .polaroidPic").css({
				'background' :  'url(./images/home/polaroids/' + i + '.jpg) no-repeat center center',
				'-o-background-size': '100% ' + $("#polaDiv_" + i + " .polaroidPic").width() * ratio + 'px',
				'-webkit-background-size': '100% ' + $("#polaDiv_" + i + " .polaroidPic").width() * ratio + 'px',
				'background-size': '100% ' + $("#polaDiv_" + i + " .polaroidPic").width() * ratio + 'px'
			});

		}
	
}

function getRatio(url, i, callback) {
	
	var image = new Image();
	image.src = url;
	image.i = i;
	image.onload = function() {
		var ret = [{ height: this.height, width: this.width, i: image.i }];
		callback(ret);
	}
	
}

function isSafari() {
	
	is_safari = 0;

  	if(navigator.userAgent.indexOf('Safari') != -1 && navigator.userAgent.indexOf('Chrome') == -1) { //returns >1 only if it's Safari (Chrome excluded)
	  
	 	is_safari = 1;
	  
 	}

	return is_safari;

}