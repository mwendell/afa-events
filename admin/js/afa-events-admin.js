var $ = jQuery.noConflict();
$(function() {
	'use strict';

	acf.add_filter('time_picker_args', function( args, field ){
		args.showSecond = false;
		args.stepMinute = 5;
		return args;
	});

});

$(document).ready(function(){


	$('#acf-field_64bfd99984d48').on('change',(function(){
		var sval = $( '#acf-field_64bfd99984d48 option:selected' ).val();
		$.ajax({
			type: "post",
			dataType: "json",
			url: "/wp/wp-admin/admin-ajax.php", //this is wordpress ajax file which is already avaiable in wordpress
			data: {
				action:'get_event_for_agenda', //this value is first parameter of add_action
				id: sval
			},
			success: function(json){
				//console.log(json);
				start_date = json.data.times.start_date;
				start_date = start_date.replace(/-/g, "");
				start_time = json.data.times.start_time;
				end_date = json.data.times.end_date;
				end_date = end_date.replace(/-/g, "");
				end_time = json.data.times.end_time;
				time_zone = json.data.times.time_zone;
				$('input[name="acf[field_64bfe0150f052][field_64bfe0150f052_field_64beb323c85d4][field_64beb0a59a0f0]"]').val(start_date);
				$('input[name="acf[field_64bfe0150f052][field_64bfe0150f052_field_64beb323c85d4][field_64beb0a59a0f0]"]').next('input').val(json.data.prettystart);
				$('input[name="acf[field_64bfe0150f052][field_64bfe0150f052_field_64beb323c85d4][field_64beb1309a0f1]"]').val(start_time);
				$('input[name="acf[field_64bfe0150f052][field_64bfe0150f052_field_64beb323c85d4][field_64beb1309a0f1]"]').next('input').val(start_time);
				$('input[name="acf[field_64bfe0150f052][field_64bfe0150f052_field_64beb323c85d4][field_64beb1d99a0f2]"]').val(end_date);
				$('input[name="acf[field_64bfe0150f052][field_64bfe0150f052_field_64beb323c85d4][field_64beb1d99a0f2]"]').next('input').val(json.data.prettyend);
				$('input[name="acf[field_64bfe0150f052][field_64bfe0150f052_field_64beb323c85d4][field_64beb1fe9a0f3]"]').val(end_time);
				$('input[name="acf[field_64bfe0150f052][field_64bfe0150f052_field_64beb323c85d4][field_64beb1fe9a0f3]"]').next('input').val(end_time);
				$('input[name="acf[field_64bfe0150f052][field_64bfe0150f052_field_64beb323c85d4][field_6579fa4f9803f]"]').val(time_zone);
			}
		});
		/*
		*/
	}))

	// EVENT SPEAKERS
	jQuery(document).on('change','[data-key="field_65694b4db6f20"] .acf-row [data-key="field_65694b90b6f22"] .acf-input select',(function(){
		var person_id = jQuery(this).val();
		var row_id = jQuery(this).closest('.acf-row').data('id');
		//alert( "Person/Row: " + person_id + "/" + row_id );
		jQuery.ajax({
			type: "post",
			dataType: "json",
			url: "/wp/wp-admin/admin-ajax.php", //this is wordpress ajax file which is already avaiable in wordpress
			data: {
				action:'afa_events_person_data', //this value is first parameter of add_action
				id: person_id
			},
			success: function(json){
				//console.log(json);
				rank = json.data.rank;
				position = json.data.position;
				//suffix = json.data.suffix;
				//nickname = json.data.nickname;
				// for event
				jQuery('#acf-field_65694b4db6f20-' + row_id + '-field_65694b68b6f21').val(rank);
				jQuery('#acf-field_65694b4db6f20-' + row_id + '-field_65694be3b6f23').val(position);
				// for agenda
				jQuery('#acf-field_64bfe0150f052-field_64bfe0150f052_field_65694b4db6f20-' + row_id + '-field_65694b68b6f21').val(rank);
				jQuery('#acf-field_64bfe0150f052-field_64bfe0150f052_field_65694b4db6f20-' + row_id + '-field_65694be3b6f23').val(position);
			}
		});
	}))

	// PODCAST HOSTS
	/*
	jQuery(document).on('change','[data-key="field_6750cac22c42f"] .acf-row [data-key="field_6750cac22c435"] .acf-input select',(function(){
		var person_id = jQuery(this).val();
		var row_id = jQuery(this).closest('.acf-row').data('id');
		//alert( "Person/Row: " + person_id + "/" + row_id );
		jQuery.ajax({
			type: "post",
			dataType: "json",
			url: "/wp/wp-admin/admin-ajax.php", //this is wordpress ajax file which is already avaiable in wordpress
			data: {
				action:'m24_person_data', //this value is first parameter of add_action
				id: person_id
			},
			success: function(json){
				//console.log(json);
				rank = json.data.rank;
				position = json.data.position;
				//suffix = json.data.suffix;
				//nickname = json.data.nickname;
				//console.log( 'RANK: ' + rank );
				//console.log( 'POS: ' + position );
				jQuery('#acf-field_6750cac22c42f-' + row_id + '-field_6750cac22c434').val(rank);
				jQuery('#acf-field_6750cac22c42f-' + row_id + '-field_6750cac22c436').val(position);
			}
		});
	}))
	*/


})
