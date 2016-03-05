<?php
/**
 * Helper function to return a list of localized JS strings
 * 
 * @since 0.1
 * @return array
 */
function censeo_get_fields_localization() { # TODO: eternalize function into separate file
	return array(
		'sunday' => __('Sunday', 'censeo'),
		'monday' => __('Monday', 'censeo'),
		'tuesday' => __('Tuesday', 'censeo'),
		'wednesday' => __('Wednesday', 'censeo'),
		'thursday' => __('Thursday', 'censeo'),
		'friday' => __('Friday', 'censeo'),
		'saturday' => __('Saturday', 'censeo'),
		
		'su' => _x('Su', 'min_days', 'censeo'),
		'mo' => _x('Mo', 'min_days', 'censeo'),
		'tu' => _x('Tu', 'min_days', 'censeo'),
		'we' => _x('We', 'min_days', 'censeo'),
		'th' => _x('Th', 'min_days', 'censeo'),
		'fr' => _x('Fr', 'min_days', 'censeo'),
		'sa' => _x('Sa', 'min_days', 'censeo'),
		
		'sun' => _x('Sun', 'short_days', 'censeo'),
		'mon' => _x('Mon', 'short_days', 'censeo'),
		'tue' => _x('Tue', 'short_days', 'censeo'),
		'wed' => _x('Wed', 'short_days', 'censeo'),
		'thu' => _x('Thu', 'short_days', 'censeo'),
		'fri' => _x('Fri', 'short_days', 'censeo'),
		'sat' => _x('Sat', 'short_days', 'censeo'),
		
		'january' => __('January', 'censeo'),
		'february' => __('February', 'censeo'),
		'march' => __('March', 'censeo'),
		'april' => __('April', 'censeo'),
		'may' => __('May', 'censeo'),
		'june' => __('June', 'censeo'),
		'july' => __('July', 'censeo'),
		'august' => __('August', 'censeo'),
		'september' => __('September', 'censeo'),
		'october' => __('October', 'censeo'),
		'november' => __('November', 'censeo'),
		'december' => __('December', 'censeo'),
		
		'jan' => _x('Jan', 'short_months', 'censeo'),
		'feb' => _x('Feb', 'short_months', 'censeo'),
		'mar' => _x('Mar', 'short_months', 'censeo'),
		'apr' => _x('Apr', 'short_months', 'censeo'),
		'may' => _x('May', 'short_months', 'censeo'),
		'jun' => _x('Jun', 'short_months', 'censeo'),
		'jul' => _x('Jul', 'short_months', 'censeo'),
		'aug' => _x('Aug', 'short_months', 'censeo'),
		'sep' => _x('Sep', 'short_months', 'censeo'),
		'oct' => _x('Oct', 'short_months', 'censeo'),
		'nov' => _x('Nov', 'short_months', 'censeo'),
		'dec' => _x('Dec', 'short_months', 'censeo'),
		
		'hourText' => _x('Hour', 'timepicker', 'censeo'),
		'minuteText' => _x('Minute', 'timepicker', 'censeo'),
		'secondText' => _x('Second', 'timepicker', 'censeo'),
		'millisecText' => _x('Millisecond', 'timepicker', 'censeo'),
		'microsecText' => _x('Microsecond', 'timepicker', 'censeo'),
		'timezoneText' => _x('Timezone', 'timepicker', 'censeo'),
		'AM' => _x('AM', 'timepicker', 'censeo'),
		'A' => _x('A', 'timepicker', 'censeo'),
		'PM' => _x('PM', 'timepicker', 'censeo'),
		'P' => _x('P', 'timepicker', 'censeo'),
		'closeText' => _x('Close', 'timepicker', 'censeo'),
		'currentText' => _x('Now', 'timepicker', 'censeo'),
		'timeOnlyTitle' => _x('Choose Time', 'timepicker', 'censeo'),
		'timeText' => _x('Time', 'timepicker', 'censeo'),
	);
}
?>