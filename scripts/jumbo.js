/*jslint browser: true*/
/*global $, jQuery, alert*/


$(function () {
        
	"use strict";

	$(document).ready(function () {
                  
		$(window).resize(function () {

			jumboLoad();
			fixJumboForIe();

		}).resize();
            
    });

});

function jumboLoad() {

	//$('#jumbo').css('margin-top', $(".navbar").height() + parseInt($('#jumbo').css('padding-top'), 10) - 3);

	$("#jumboListDiv .flex-row div:first-of-type").addClass("my-auto");

	mobileAndPc();

	if (Modernizr.mq('screen and (max-width: 767px)')) {

		below768px();

	} else { // bootstrap sensitive widths: 991px 1200px

		over768px();

	}
	
	jumboListFontSize();
	
}

function fixJumboForIe() {
	
	// Internet Explorer 6-11
	var isIE = /*@cc_on!@*/false || !!document.documentMode;
	
	if(isIE && Modernizr.mq('screen and (min-width: 768px)')) {
		
		$("#jumboListDiv").removeClass("w-75").css("width", "73.3%");
		
	}
	
}

function below768px() {

	$("#jumboRow").removeClass("flex-row");
	$("#jumboRow").css("max-width", "450px");
	$("#jumboPicDiv").removeClass("w-25").addClass("mx-2 mt-2").removeClass("my-2 ml-2");
	$("#jumboPicDiv img").addClass("border-bottom-0 rounded-top").removeClass("border-right-0 rounded-left");
	$("#jumboListDiv").removeClass("w-75").addClass("mx-2 mb-2 border-top-0 rounded-bottom").removeClass("my-2 mr-2 border-left-0 rounded-right");
	$("#jumboRow").addClass("flex-column");
	$("#jumboListDiv .flex-row div").addClass("my-2");
	
}

function over768px() {
	
		$("#jumboRow").addClass("flex-row");
		$("#jumboRow").css("max-width", "none");
		$("#jumboRow").removeClass("flex-column");
		$("#jumboPicDiv").addClass("w-25").removeClass("mx-2 mt-2 border-bottom-0").addClass("my-2 ml-2 border-right-0");
		$("#jumboPicDiv img").removeClass("border-bottom-0 rounded-top").addClass("border-right-0 rounded-left");
		$("#jumboListDiv").addClass("w-75").removeClass("mx-2 mb-2 border-top-0 rounded-bottom").addClass("my-2 mr-2 border-left-0 rounded-right");
		$("#jumboListDiv div").addClass("my-auto");
	
}

function mobileAndPc() {
	
	if (Modernizr.mq('screen and (max-width: 991px)')) {

		$('#bg').removeClass('container');
		$('#bg').addClass('container-fluid');
		$(".faJumboLink").show();

	} else { // bootstrap sensitive widths: 991px 1200px

		$('.extLink').html('');
		$('#bg').removeClass('container-fluid');
		$('#bg').addClass('container');
		$(".faJumboLink").hide();

	}
	
}

function jumboListFontSize() {
	
	if (Modernizr.mq('screen and (max-width: 767px)')) { // bootstrap sensitive widths: 991px 1200px (-16px while using jquery)   
		$("#jumboListDiv").css("font-size", "18px");

	} else if (Modernizr.mq('screen and (max-width: 991px)')) {

		$("#jumboListDiv").css("font-size", "20px");

	} else if (Modernizr.mq('screen and (max-width: 1200px)')) {

		$("#jumboListDiv").css("font-size", "25px");

	} else {

		$("#jumboListDiv").css("font-size", "35px");

	}
	
}
				   
$(function () {
        
	"use strict";

	$("#picsModal").on('shown.bs.modal', function (event) {

		var modalCaller = $(event.relatedTarget), //address of the caller itself
			dataModalCaller = modalCaller.data('caller'); //id stored in a data-* variable of the caller. In the id of the picture caller I'm already encapsulated both the id and the idType to look for.

		if (dataModalCaller === "DenisImgLink") {

			$("#picsModalBody").html('<img class="modalImg" src="images/DenisImg.jpg" alt="Profile picture">');

			if ($('.navLang a').data('lang') === 'ita') {
				$(".modal-title").html('Profile Picture');
			} else {
				$(".modal-title").html('Immagine Profilo');
			}

			squareModal("#picsModalBody img");

		} else if (dataModalCaller === "realAddress") {

			if ($('.navLang a').data('lang') === 'ita') {
				$(".modal-title").html('Address Location');
			} else {
				$(".modal-title").html('Mostra Indirizzo');
			}

			$("#picsModalBody").html('<iframe id="modalMap" class="modalImg" src="' + returnLocation(modalCaller.attr("id")) + '" frameborder="0" style="border:0" allowfullscreen></iframe><div id="mapLoading" class="divLoading"><div class="divLoader"></div></div>');
			
			//----------------- show iframe loading screen
			
				$(document).ready(function () {
					$('#modalMap').on('load', function () {
						$("#mapLoading").css("display", "none");
					});
				});
			
			//------------------
			
			squareModal('#modalMap');

		} else if (dataModalCaller === "emailSender") {

			if ($('.navLang a').data('lang') === 'ita') {
				$(".modal-title").html('Send an Email');
			} else {
				$(".modal-title").html("Invia un'Email");
			}
			generateForm($("#picsModalBody"));
			$("#outcomeDiv").hide();

		}

	});
	
});



$("#picsModal").on('hidden.bs.modal', function (event) {

	$(".modal-body").css("min-height", '0px');

});

$('.close').click(function () {

	$("#picsModalBody").html('');
	$("#modalTitle").html('');

});

function returnLocation(dataCaller) {

	if ($('#' + dataCaller).attr("id") === 'address_1') {

		return 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d9416.395467343!2d9.139351167439203!3d45.43935544121356!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4786c3b8d75fe4c5%3A0xd6f47cf8a5ae146a!2sVia+Francesco+Olgiati%2C+25%2C+20143+Milano+MI!5e0!3m2!1sen!2sit!4v1506549715924';

	} else if ($('#' + dataCaller).attr("id") === 'address_2') {

		return 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2838.761721836488!2d7.663660615878487!3d44.642786779099765!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x12cd5111d6869189%3A0x541c08506aa1e60e!2sVia+Duccio+Galimberti%2C+35%2C+12038+Savigliano+CN!5e0!3m2!1sen!2sit!4v1505922624307';

	}

}

function squareModal(innerItem) {

	$(window).resize(function () {

		$(innerItem).width($(".modal-body").width());
		$(innerItem).height($(".modal-body").width());	

	}).resize();

}

function generateForm(emailModal) {

	emailModal.html('<div id="outcomeDiv"></div><div class="form-group"><label for="fromTxt">' + (($('.navLang a').data('lang') === 'ita') ? 'Insert your email' : 'Inserisci la tua email') + '</label><input type="email" class="form-control" id="fromTxt" aria-describedby="emailHelp" placeholder="Email"></div><div class="form-group"><label for="sbjTxt">' + (($('.navLang a').data('lang') === 'ita') ? 'Subject' : 'Oggetto') + '</label><input type="text" class="form-control" id="sbjTxt" aria-describedby="subjectHelp" placeholder="' + (($('.navLang a').data('lang') === 'ita') ? 'Title' : 'Titolo') + '"></div><div class="form-group"><label for="msgTxt">' + (($('.navLang a').data('lang') === 'ita') ? 'Message' : 'Messaggio') + '</label><textarea class="form-control" id="msgTxt" rows="6"></textarea></div><button id="submitEmail" class="btn btn-warning">' + (($('.navLang a').data('lang') === 'ita') ? 'Send' : 'Invia') + '</button>');

}

function isEmail(email) {

	var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	return regex.test(email); //returns true or false if the email is spelt right or not

}

$("#picsModalBody").on("click", "#submitEmail", function () {
       
    var error = "";
       
    if ($("#fromTxt").val() === "" || !(isEmail($("#fromTxt").val()))) {
        error += '<p>' + (($('.navLang a').data('lang') === 'ita') ? 'Invalid email format' : 'Formato email non valido') + '</p>';
    }

    if ($("#sbjTxt").val() === "") {
        error += '<p>' + (($('.navLang a').data('lang') === 'ita') ? 'Please insert a subject' : 'Oggetto dell\'email non presente') + '</p>';
    }

    if ($("#msgTxt").val() === "") {
        error += '<p>' + (($('.navLang a').data('lang') === 'ita') ? 'Please insert a message' : 'Messaggio non presente') + '</p>';
    }

    if (error !== "") {
        $("#outcomeDiv").show();
        $("#outcomeDiv").html('<div class="alert alert-danger"><p><strong>' + (($('.navLang a').data('lang') === 'ita') ? 'Email could not be sent:' : 'Errore durante l\'invio dell\'email') + '</strong></p>' + error + '</div>');
    } else {
        $("#outcomeDiv").hide();
    }

    if (error === "") {
        
        $.ajax({
            method: "POST",
            url: "./php/emailSender.php",
            data: {
                sender: $("#fromTxt").val(),
                recipient: 'denis@deniscoccodi.com',
                subject: $("#sbjTxt").val(),
                message: $("#msgTxt").val()
            },
            success: function (response) {

                var received = $.parseJSON(response);
                $("#outcomeDiv").html('<div class="alert alert-' + ((received.flag) ? 'success' : 'danger') + '"><p><strong>' + (($('.navLang a').data('lang') === 'ita') ? received.eng : received.ita) + '</strong></p></div>').show();
                
            }
        });
    }

});