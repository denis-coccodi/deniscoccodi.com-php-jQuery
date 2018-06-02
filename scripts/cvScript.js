$(document).ready(function() {

    $(window).resize(function() {
		
        $(".cvCollapsable").changeArrowOrientation();
		fixTitlesForIe();

		$('.cvCollapsable').on('hidden.bs.collapse', function () {

		   $(this).changeArrowOrientation();

		});

		$('.cvCollapsable').on('shown.bs.collapse', function () {

		   $(this).changeArrowOrientation();

		});
        
        if (Modernizr.mq('screen and (max-width: 767px)')) {
            
            $(".leftColumn").removeClass("text-center");
            $(".leftColumn").addClass("ml-3");
    
        } else {
            
            $(".leftColumn").addClass("text-center");
            $(".leftColumn").removeClass("ml-3");
            
        }
        
        if (Modernizr.mq('screen and (max-width: 991px)')) {
            
            $(".leftColumn").removeClass("col-md-3");
            $(".rightColumn").removeClass("col-md-9");
            $(".leftColumn").addClass("col-md-4");
            $(".rightColumn").addClass("col-md-8");

        } else {
            
            $(".leftColumn").removeClass("col-md-4");
            $(".rightColumn").removeClass("col-md-8");
            $(".leftColumn").addClass("col-md-3");
            $(".rightColumn").addClass("col-md-9");
            
        }

    }).resize();
});

$('.sectionTitleDiv').click( function() {
    
    $(this).toggleClass('titleReleased');
    
});

function fixTitlesForIe() {
	
	// Internet Explorer 6-11
	var isIE = /*@cc_on!@*/false || !!document.documentMode;
	
	if(isIE && Modernizr.mq('screen and (min-width: 992px)')) {
		
		$(".sectionTitleDiv h4").removeClass("my-0").addClass("mt-3");
		
	} else {
		
		$(".sectionTitle h4").addClass("my-0").removeClass("mt-3");
		
	}
	
}

(function( $ ){
    
   $.fn.changeArrowOrientation = function() {

            return this.each(function() {
                    
                    $('body').find('[data-target="#'+ $(this).attr('id')+'"]').find(".sectionHr").html('<i class="fa fa-angle-'+ ($(this).hasClass('show')?'up':'down') +' fa-3x" aria-hidden="true"></i>');
				
            });

    };

})( jQuery );

$(document).ready(function() {

    $("#picsModal").on('shown.bs.modal', function(event) {

        var modalCaller = $(event.relatedTarget); //address of the caller itself
        var dataModalCall = modalCaller.data('caller'); //id stored in a data-* 
        var dataPictureCall = modalCaller.data('classmc'); //id stored in a data-* variable of the caller. In the id of the picture caller I'm already encapsulated both the id and the idType to look for.
        
        if (dataPictureCall == "imgModLink") {

            $.ajax({
                url:"./php/actions.php?action=loadImgLinks",
                method:"POST",
                data:{ caller: dataModalCall },
                success:function(msg){

                    var containerArray = $.parseJSON(msg);
                    var imgLinks = containerArray['imgLinksArray'][0];
                    var aspectRatios = containerArray['imgLinksArray'][1];

                    $("#modalTitle").html(containerArray['imgTitle']);

                    if(imgLinks) {

                        $("#picsModalBody").html(loadCarousel(imgLinks));

                    }

                    $(window).resize(function() {

                        resizeImg(aspectRatios);

                    }).resize();


                    $('#carouselIndicators').on('slid.bs.carousel', function () {

                        resizeImg(aspectRatios);

                    });


                }

            });

        }

    });
    
});

function resizeImg(aspectRatios) {

	var currentImage = $("#imgCarousel_"+$('#carouselIndicators .active').index());
	//currentImage.css("cssText", 'max-width:'+$(".modal-dialog").width()+'px !important;');
	//console.log($(".modal-dialog").width(),currentImage.width());

	currentImage.css("cssText", "height: "+currentImage.width()*aspectRatios[$('#carouselIndicators .active').index()]+"px !important;");

}

function loadCarousel (imgLinks){

    var carouselHTML = "";

    carouselHTML += '<div id="carouselIndicators" class="carousel slidecarousel-fade" data-ride="carousel" data-interval="false" data-wrap="false"><ol class="carousel-indicators">';

    for(var i=0; i< imgLinks.length; i++){

        carouselHTML += '<li data-target="#carouselIndicators" data-slide-to="'+i+'" '+ ((i == 0)?'class="active"':'') +'</li>';

    }

    carouselHTML +=  '</ol><div class="carousel-inner" role="listbox">';

    for(var i=0; i< imgLinks.length; i++) {

        carouselHTML += '<div class="carousel-item'+ ((i == 0)?' active':'') +'"><img class="d-block carouselImg img-fluid" id="imgCarousel_'+ i  +'" src="'+ imgLinks[i] +'" alt="Denis_Coccodi_'+ i +'"></div>'

    }

    carouselHTML += '</div><a class="carousel-control-prev" href="#carouselIndicators" role="button" data-slide="prev"><span class="carousel-control-prev-icon" aria-hidden="true"></span><span class="sr-only">Previous</span></a><a class="carousel-control-next" href="#carouselIndicators" role="button" data-slide="next"><span class="carousel-control-next-icon" aria-hidden="true"></span><span class="sr-only">Next</span></a></div>';

    return carouselHTML;
}