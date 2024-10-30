var $ = jQuery.noConflict();
$(function() {
	'use strict';

	acf.add_filter('time_picker_args', function( args, field ){
		args.showSecond = false;
		args.stepMinute = 5;
		return args;
	});

});
