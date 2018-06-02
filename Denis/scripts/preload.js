/*function onReady(callback) {
	
    var intervalID = window.setInterval(checkReady, 300);
	
    function checkReady() {
		
        if (document.getElementsByTagName('body')[0] !== undefined) {
            window.clearInterval(intervalID);
            callback.call(this);
			
        }
		
    }
	
}

onReady(function() {
	
	$('#loading').children().fadeOut("fast", window.loaded = function (callback) {
		
		$("body").css("overflow", "auto");
		$("#page").css("visibility", "visible");
		$("#loading").css("display", "none");
		
	});
	
});*/

$('#page').imagesLoaded( { background: true }, function() {
	
	$('#loading').children().fadeOut("", function() {
		
		$("body").css("overflow", "auto");
		$("#page").css("visibility", "visible");
		$("#loading").css("display", "none");
		
	});
	
});