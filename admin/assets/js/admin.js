(function ( $ ) {
	"use strict";
	$.lxt_jast = new Object();
	var external = lxt_jast_local_const;
	$.lxt_jast.slug = external.slug,
	$.lxt_jast.ver = external.ver,
	$.lxt_jast.ajaxurl = external.ajaxurl,
	$.lxt_jast.choiceLabel = external.choiceLabel;
	$.lxt_jast.pubjsdir = external.pubjsurl;

	$(function () {
		$.lxt_jast.screen_id = $('#lxt_jast_screen_id').val();

		$('#lxt_jast_survey_title').change(function() {
			if ( $('.nav-tab-active').attr('id') == 'lxt_jast_image_tab' )
				$.lxt_jast.getSurveyChartFrame($('#'+$.lxt_jast.slug+'_survey_title').val());
			else
				$.lxt_jast.getSurveyTextFrame($('#'+$.lxt_jast.slug+'_survey_title').val());
		});

		$('.nav-tab').click(function(){
			if ( $(this).hasClass('nav-tab-active') )
				return;

			$('.nav-tab-active').removeClass('nav-tab-active');
			$(this).addClass('nav-tab-active');

			if ( $(this).attr('id') == 'lxt_jast_image_tab' )
				$.lxt_jast.getSurveyChartFrame($('#'+$.lxt_jast.slug+'_survey_title').val());
			else
				$.lxt_jast.getSurveyTextFrame($('#'+$.lxt_jast.slug+'_survey_title').val());
				
		});
	});

	$.lxt_jast.getSurveyChartFrame = function(surveyTitle) {
		var ajaxObj = {action : $.lxt_jast.slug+'_chart_frame'};
		ajaxObj.title = surveyTitle;
									
		jQuery.post($.lxt_jast.ajaxurl, ajaxObj, function( resp ) {
			$('#'+$.lxt_jast.slug+'_result_content').html(resp);
			//if ($('.'+$.lxt_jast.slug+'_result_img').length > 0) {
				//jQuery.getScript($.lxt_jast.pubjsdir+'draw.chart.js', function() {
				//	$('.'+$.lxt_jast.slug+'_result_img').each($.lxt_jast.drawChart);
				//});
				
			//}
			if ($('#'+$.lxt_jast.slug+'_survey_qust').length > 0) {
				$('#'+$.lxt_jast.slug+'_survey_qust').change(function(){
					if ($(this).val() == "") {
						$('.'+$.lxt_jast.slug+'_result_img').html('');
					}else{
						$(this).find("option:selected").text();
						var sel = JSON.parse($(this).val());
						var img = $('.'+$.lxt_jast.slug+'_result_img');
						var id = $(img).attr('id');

						var secs = id.split('_');
						id = secs[0]+'_'+secs[1]+'_'+secs[2]+'_'+secs[3]+'_'+sel.name;
						$(img).attr('id', id);
						$(img).attr('type', sel.type);
						$(img).attr('title', $(this).find("option:selected").text());

						jQuery.getScript($.lxt_jast.pubjsdir+'draw.chart.js', function() {
							$(img).each($.lxt_jast.drawChart);
						});
					}
				});
			}
		});
	};

	$.lxt_jast.getSurveyTextFrame = function(surveyTitle) {
		var ajaxObj = {action : $.lxt_jast.slug+'_text_frame'};
		ajaxObj.title = surveyTitle;
		ajaxObj.screen_id = $.lxt_jast.screen_id;
									
		jQuery.post($.lxt_jast.ajaxurl, ajaxObj, function( resp ) {
			$('#'+$.lxt_jast.slug+'_result_content').html(resp);
			if ($('#'+$.lxt_jast.slug+'_survey_qust').length > 0) {
				$('#'+$.lxt_jast.slug+'_survey_qust').change(function(){
					var name = $(this).val();
					if (name == "") {
						$('.'+$.lxt_jast.slug+'_result_table').html('');
					}else{
						var table = $('.'+$.lxt_jast.slug+'_result_table');
						var id = $(table).attr('id');

						var secs = id.split('_');
						id = secs[0]+'_'+secs[1]+'_'+secs[2]+'_'+secs[3]+'_'+name;
						$(table).attr('id', id);

						$.lxt_jast.getSurveyTextTable(table, '1');
					}
				});
			}
		});
	};

	$.lxt_jast.getSurveyTextTable = function(table, page) {
		var id = $(table).attr('id');
		var secs = id.split('_');

		var ajaxObj = {action : $.lxt_jast.slug+'_text_table'};
		ajaxObj.post_id = secs[3];
		ajaxObj.name = secs[4];
		ajaxObj.paged = page;
		ajaxObj.screen_id = $.lxt_jast.screen_id;

		jQuery.post($.lxt_jast.ajaxurl, ajaxObj, function( resp ) {
			$(table).html(resp);
			$('#'+id+' .next-page').click(function(e){$.lxt_jast.getTableNewPage(this);e.preventDefault();});
			$('#'+id+' .prev-page').click(function(e){$.lxt_jast.getTableNewPage(this);e.preventDefault();});
			$('#'+id+' .first-page').click(function(e){$.lxt_jast.getTableNewPage(this);e.preventDefault();});
			$('#'+id+' .last-page').click(function(e){$.lxt_jast.getTableNewPage(this);e.preventDefault();});
			$('#'+id+' .current-page').change(function(e){$.lxt_jast.getTableNewPage(this);});
		});
	};

	$.lxt_jast.getTableNewPage = function(pageLink) {
		if ($(pageLink).hasClass('disabled'))
			return false;

		if ($(pageLink).is('a')) {
			var url = $(pageLink).attr('href');
			var paged = $.lxt_jast.getQueryStr(url, 'paged');
		} else {
			var paged = $(pageLink).val();
		}
		var table = $('.'+$.lxt_jast.slug+'_result_table');


		$.lxt_jast.getSurveyTextTable(table, paged);

		return false;
	};

	$.lxt_jast.getQueryStr = function (url, key){
        var rs = new RegExp("(^|)"+key+"=([^\&]*)(\&|$)","gi").exec(url), tmp;
        if(tmp=rs){
            return tmp[2];
        }
        return "";
    };
}(jQuery));
