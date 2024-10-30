(function ( $ ) {
	"use strict";
	$.lxt_jast = new Object();
	var external = lxt_jast_local_const;
	$.lxt_jast.slug = external.slug,
	$.lxt_jast.ver = external.ver,
	$.lxt_jast.ajaxurl = external.ajaxurl,
	$.lxt_jast.choiceLabel = external.choiceLabel;
	$.lxt_jast.pubjsdir = external.pubjsurl;

	if (window['lxt_jast_post_const'] != undefined)
		$.lxt_jast.postId = lxt_jast_post_const.post_id;
	else
		$.lxt_jast.postId = null;

	$.lxt_jast.survey = function(container, postid) {
		$(container).find('.'+$.lxt_jast.slug+'_submit').click(function() {
			var allFields = $(container).find('input[name],textarea[name],select[name]');
			var ret = {};
			allFields.each(function() {
				if ($(this).hasClass($.lxt_jast.slug+'_other'))
					return;
				ret[$(this).attr('name')] = '';
			});

			allFields.each(function() {
				if ($(this).hasClass($.lxt_jast.slug+'_other'))
					return;
				if ( ( $(this).attr('type') == 'radio' || $(this).attr('type') == 'checkbox' ) && !$(this).prop('checked') )
					return;
				if (ret[$(this).attr('name')] !== '')
					ret[$(this).attr('name')] = ret[$(this).attr('name')]+","+$(this).val();
				else
					ret[$(this).attr('name')] = $(this).val();
			});
	
			var ajaxObj = {action : $.lxt_jast.slug+'_savesurvey'};
			ajaxObj.email = ret[$.lxt_jast.slug+'_the_email'];
			delete ret[$.lxt_jast.slug+'_the_email'];
			ajaxObj.result = JSON.stringify(ret);
			ajaxObj.postid = postid;
			
			var message = $(this).attr('message');
			var afterUrl = $(this).attr('url');
						
			jQuery.post($.lxt_jast.ajaxurl, ajaxObj, function( resp ) {
				if (message != '')
					alert(message);

				if ($(container).attr('id').indexOf($.lxt_jast.slug+'_popup')>-1) {
					$(container).bPopup().close();
				} else if (afterUrl == 'back') {
					history.back();
				} else  {
					var pattern = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;
					if (pattern.test(afterUrl)) {
						window.location.href = afterUrl;
					} 
				}
			});
		});

		$(container).find('.'+$.lxt_jast.slug+'_other').change(function() {
			$(this).parent().children(":first").val($(this).val());
		});

		$(container).find('.'+$.lxt_jast.slug+'_other').focus(function() {
			$(this).parent().children(":first").prop("checked", true);
		});
	}

	$(function () {	
		$('.'+$.lxt_jast.slug+'_popup_open').click( function(e) {
			e.preventDefault();
			var targetid = $(this).attr('target');
			var post_id = $(this).attr('postid');
			if ($('#'+$.lxt_jast.slug+'_popup_'+targetid).length == 0) {
				var targetcalss = $(this).attr('targetclass');
				$('<div/>', {
					id: $.lxt_jast.slug + '_popup_' + targetid,
					class: targetcalss,
					html: '<span class="'+$.lxt_jast.slug+'_popup_close"><span>X</span></span><div class="'+$.lxt_jast.slug+'_popup_decorator" ><div id="'+$.lxt_jast.slug+'_popup_container_'+targetid+'"></div></div></div>'
				}).appendTo('body'); 
			}
			$('#'+$.lxt_jast.slug+'_popup_'+targetid).bPopup({
				modalClose: false,
				speed: 450,
				transition: 'slideDown',
				closeClass: $.lxt_jast.slug+'_popup_close',
				loadUrl: $.lxt_jast.ajaxurl,
				loadData: {action : $.lxt_jast.slug+'_loadsurvey', id : post_id},
				content: 'ajax',
				contentContainer: '#'+$.lxt_jast.slug+'_popup_container_'+targetid,
				onOpen: function() { $(this).find('.'+$.lxt_jast.slug+'_popup_decorator').addClass($.lxt_jast.slug+'_loading'); },
				loadCallback: function() { 
					$(this).find('.'+$.lxt_jast.slug+'_popup_decorator').removeClass($.lxt_jast.slug+'_loading');
					$('#'+$.lxt_jast.slug+'_popup_'+targetid).each(function(){ $.lxt_jast.survey(this, post_id) });
				}
			});
		});

		if ($('.'+$.lxt_jast.slug+'_result_img').length > 0) {
			jQuery.getScript($.lxt_jast.pubjsdir+'draw.chart.js', function() {
				$('.'+$.lxt_jast.slug+'_result_img').each($.lxt_jast.drawChart);
			});
		}

		if ($.lxt_jast.postId != null) {
			$('div#content').each(function(){ $.lxt_jast.survey(this, $.lxt_jast.postId) });
		}
	});	
}(jQuery));
