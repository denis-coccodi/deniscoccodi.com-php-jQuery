$(document).ready(function () {

	$(window).resize(function () {

		fixNavbarForIe();

	}).resize();

});

$(".langSelection a").click(function(){

    var lang = $(this).attr("data-lang");

    $.ajax({

        method: "POST",
        url: "./php/actions.php?action=changeLang",
        data: { langRequired: lang },
        success: function(data){
            console.log(data);
            location.reload();

        } 

    });

});

	$("#picsModal").on('shown.bs.modal', function (event) {
		
		var modalCaller = $(event.relatedTarget), //address of the caller itself
			dataModalCaller = modalCaller.data('caller');

		if (dataModalCaller === "info") {
			
			$(".modal-title").html('Info');
			$(".modal-dialog").removeClass("modal-lg");
			$("#picsModalBody").html(
									 
			'<div class="m-0 p-2 text-center info">' +
				'<p class="p-0 m-0 my-auto"><b>' + 
				(($('.navLang a').data('lang') === 'ita') ? 'Website created by Denis Coccodi thanks to:' : 'Sito web creato da Denis Coccodi grazie a:') + 
				'</b></p>' + 
				'<p class="p-0 m-0 mt-3">Bootstrap, Bootstrap Slider, Font Awesome, Google Charts, Google Maps, Modernizr, Stack Overflow <i class="fa fa-heartbeat" style="color: red;" aria-hidden="true"></i>, HTML5, CSS3, Javascript, jQuery, Ajax' + (($('.navLang a').data('lang') === 'ita') ? ' and php.<p class="mt-3">Website tested on IE 10, Edge, Chrome, Firefox, Opera and Safari. Any feedback on possible display errors is welcome, and every error will be fixed as soon as possible. Thank you!</p>' : ' e php.<p class="mt-3">Sito web testato su IE 10, Edge, Chrome, Firefox, Opera e Safari. Ogni feedback su eventuali errori è bene accetto ed ogni errore verrà corretto quanto prima. Grazie!</p>') + '</p>' + 
				'</p>' +
			'</div>'
				
			);
		
		}
		
	});

$('#info').on({
    focus: function () {
        $(this).blur();
    }
}); 

$('#picsModal').on('hidden.bs.modal', function (e) {

    $("#picsModalBody").html('');
    $("#modalTitle").html('');
	$(".modal-dialog").addClass("modal-lg");
	$("*").blur();

});

function fixNavbarForIe() {
	
	// Internet Explorer 6-11
	var isIE = /*@cc_on!@*/false || !!document.documentMode;
	
	if(isIE && Modernizr.mq('screen and (min-width: 992px)')) {
		
		$(".navbar-collapse ul").removeClass("mx-auto").addClass("mx-0");
		$(".navbar-collapse").css("border-top", "4px solid transparent");
		$(".langList a").removeClass("p-2").addClass("px-2").css("border-top", "4px solid transparent");
		$(".langList").removeClass("ml-auto").addClass("ml-1");
		
	} else if(isIE) {
		
		$(".navbar-collapse ul").addClass("mx-auto").removeClass("mx-0");
		$(".langList a").addClass("p-2").removeClass("px-2");
		$(".langList").addClass("ml-auto").removeClass("ml-1");
		
	}
	
} 