google.charts.load('current', {'packages':['timeline']});
google.charts.setOnLoadCallback(drawChart);

/*var timer;
$(window).bind('resize', function() {
  clearTimeout(timer);
  timer = setTimeout(function(){ drawChart(); }, 300);
});*/

function resize () {
	
    drawChart();
	
}

if (window.addEventListener) { //check if eventListener is an acceptable method (The browser is not IE) 
	
    window.addEventListener('resize', resize);
	
} else {
	
    window.attachEvent('onresize', resize); //alternative for IE
	
}

$(document).ready(function() {

	var d= new Date();	
	$('#slider').slider({
		id: "slidingBar",
		tooltip: 'always',
		min: 1985,
		max: d.getFullYear(),
		range: true,
		value: [2009, d.getFullYear()]
	});

	var tlValues = $('#slider').slider('getValue');
	$('#tlValues').html((($('.navLang a').data('lang') === 'ita') ? 'From ' : 'Dal ') + tlValues[0] + (($('.navLang a').data('lang') === 'ita') ? ' to ' : ' al ') + tlValues[1]);

});

$('#slider').on('slide', function(e){

	var tlValues = $('#slider').slider('getValue');
	$('#tlValues').html((($('.navLang a').data('lang') === 'ita') ? 'From ' : 'Dal ') + tlValues[0] + (($('.navLang a').data('lang') === 'ita') ? ' to ' : ' al ') + tlValues[1]);

});

$('#redraw').click(function() {

	$('#tlError').hide();
	$('#timeline').html('');
	$("#tlLoading").show();
	drawChart();

});

$(document).ready(function() {

    $(window).resize(function() {
        
        if (Modernizr.mq('screen and (max-width: 413px)')) {
			
			$('#tlPortraitWarning').html(($('.navLang a').data('lang') === 'ita') ? 'Swipe left or right to visualize the rest of the chart' : 'Trascina a destra o a sinistra per visualizzare la tabella').show();
			
		} else {
			
			$('#tlPortraitWarning').hide();
			
		}

    }).resize();
});


function getTimelineData(callback) {

	var timelineData = new Array();

	$.ajax({
		url:"./php/actions.php?action=loadTimeline",
		success:function(msg){

			timelineData = $.parseJSON(msg);

			callback(timelineData);

		}

	});

};

function drawChart() {

	var container = document.getElementById('timeline');
	var chart = new google.visualization.Timeline(container);
	var dataTable = new google.visualization.DataTable();
	var matchFound = false;
	
	getTimelineData(function(timelineData){

		dataTable.addColumn({ type: 'string', id: 'type' });
		dataTable.addColumn({ type: 'string', id: 'title' });
		dataTable.addColumn({'type': 'string', 'role': 'tooltip', 'p': {'html': true}});
		dataTable.addColumn({ type: 'date', id: 'StartDate' });
		dataTable.addColumn({ type: 'date', id: 'EndDate' });

		(createRow(dataTable, timelineData, 'education', (($('.navLang a').data('lang') === 'ita') ? 'Education' : 'Formazione')) ? matchFound = true : '' );
		(createRow(dataTable, timelineData, 'workExperience', (($('.navLang a').data('lang') === 'ita') ? 'Work Experience' : 'Lavoro')) ? matchFound = true : '');
		(createRow(dataTable, timelineData, 'volunteering', (($('.navLang a').data('lang') === 'ita') ? 'Volunteering' : 'Volontariato')) ? matchFound = true : '');
		(createRow(dataTable, timelineData, 'hobbies', (($('.navLang a').data('lang') === 'ita') ? 'Hobbies' : 'Hobbies')) ? matchFound = true : '');
		(createRow(dataTable, timelineData, 'nationsDates', (($('.navLang a').data('lang') === 'ita') ? 'Nation' : 'Nazione')) ? matchFound = true : '');

		var tlWidth = setTlWidth();

		var options = {
			colors: ['#ff7200', '#3d3d3d', '#ffba44', '#ffcd77', '#ff6100'],
			focusTarget: 'category',
			tooltip: { isHtml: true },
			width: tlWidth,
			timeline: { rowLabelStyle: {fontName: 'Helvetica', fontSize: 16, color: '#000000' }, barLabelStyle: { fontName: 'Arial', fontSize: 12 } 
			}
		};

		google.visualization.events.addListener(chart, 'error', function (googleError) {
		  google.visualization.errors.removeError(googleError.id);
		});
		
		if (matchFound) { 
			chart.draw(dataTable, options);
			$("#slider").slider("enable");
			$("#tlLoading").hide();
		} else {

			($('#tlError').html(($('.navLang a').data('lang') === 'ita') ?
'Could not find Data in this date range. Try a different date' : 'Non esistono dati relativi a questo frangente di tempo. Prova con una data differente.').show());
		}
	});
}

function setTlWidth() {
	
	if (Modernizr.mq('screen and (max-width: 520px)')) {

		return 600;

	} else {

		return '';

	}
	
}

function createRow(dataTable, timelineData, tableName, title){

	var startEnd = null;
	var checkedDate = null;
	var matchFound = false;
	var tlValues = $('#slider').slider('getValue');
	var tlEnd = tlValues[1] - tlValues[0]; // case 0 handled in the checkBeginEnd function

	checkedDate = checkBeginEnd(tlValues[0], tlEnd);

	$.each(timelineData[tableName], function(key,value){

		if(startEnd = setBeginEnd(value, checkedDate.startYear, checkedDate.duration)) {

			matchFound = true;
			dataTable.addRow([

				title, value.title, generateTooltip(dataTable, value, title), new Date(startEnd.sYear, startEnd.sMonth, startEnd.sDay), new Date(startEnd.eYear, startEnd.eMonth, startEnd.eDay)

			]);

		}

	})

	return matchFound;

}

function generateTooltip(dataTable, value, title){

	  return '<div style="padding:5px 5px 5px 5px; border: 1px solid black;">' +
		  value.title + '<br/><hr class="m-0 p-0 mb-2">' +
		  '<table class="medals_layout">' + 
		  '<tr><td><strong>' + (($('.navLang a').data('lang') === 'ita') ? 'From:' : 'Dal:') + ' </strong></td><td>' + stDate(value) + '</td></tr>' +
		  '<tr><td><strong>' + (($('.navLang a').data('lang') === 'ita') ? 'To:' : 'Al:') + ' </strong></td><td>' + endDate(value) + '</td></tr>' + 
		'</table>' + 
	'</div>';

}

function stDate(value){

	return value.startDate.day + '/' + value.startDate.month + '/' + value.startDate.year;

}

function endDate(value){

	return value.endDate.day + '/' + value.endDate.month + '/' + value.endDate.year;

}

function checkBeginEnd(startYear, duration) {


	var d = new Date();
	startYear = parseInt(startYear, 10);
	duration = parseInt(duration, 10);
	var ret = {'startYear': 2009, 'duration': 8};

	if (startYear > (d.getFullYear())) {

		startYear = d.getFullYear();

	} else if(startYear < 1985) {

		startYear = 1985;

	}

	if(duration < 0) {

		duration = 0;

	} else if((startYear + duration) > d.getFullYear()) {

		duration = d.getFullYear() - startYear;

	} 

	ret.startYear = startYear;
	ret.duration = duration;

	return ret;

}

function setBeginEnd(value, startYear, duration) {

	var d = new Date();
	var startEnd = {
		'sDay': '', 
		'sMonth': '', 
		'sYear': '', 
		'eDay': '', 
		'eMonth': '', 
		'eYear': '' 
	};

	if(value.endDate.year > startYear + duration) {
		if(d.getFullYear() == (startYear + duration)) {

			startEnd.eDay = d.getDate();
			startEnd.eMonth = d.getMonth();
			startEnd.eYear = d.getFullYear();

		} else {

			startEnd.eDay = '31';
			startEnd.eMonth = '11';
			startEnd.eYear = startYear + duration;

		}

	} else if(value.endDate.year >= startYear) {

		startEnd.eDay = value.endDate.day;
		startEnd.eMonth = value.endDate.month - 1;
		startEnd.eYear = value.endDate.year;

	} else {

		return false;

	}

	if(value.startDate.year < startYear) {

		startEnd.sDay = '01';
		startEnd.sMonth = '0';
		startEnd.sYear = startYear;

	} else if(value.startDate.year <= (startYear + duration)) {

		startEnd.sDay = value.startDate.day;
		startEnd.sMonth = value.startDate.month - 1;
		startEnd.sYear = value.startDate.year;

	} else {

		return false;

	}

	return startEnd;

}