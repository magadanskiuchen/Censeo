<?php
/*
Sample usage:
$options_page = new Censeo_Options($id, $title, $capabilities='administrator', $parent=false);
*/

/////////////////////////////////
// Options Page /////////////////
/////////////////////////////////
$options_page = new Censeo_Options('censeo-options', __('Censeo Options', 'censeo'));

$test_option = new Censeo_Field('test', __('Test', 'censeo'));

$test_number = new Censeo_Field_Number('test_number', __('Number', 'censeo'));
$test_number->set_min(0);
$test_number->set_max(10);
$test_number->set_step(2);

$test_enumerable = new Censeo_Field_Enumerable('test_enumerable', __('Enumerable', 'censeo'));
$test_enumerable->add_options(array('All', 'Rock', 'Pop', 'R&B', 'Metal', 'Heavy Metal'));
// $test_enumerable->set_render_variant(Censeo_Field_Enumerable_Render_Variant::RADIO);

$test_multiple = new Censeo_Field_Multiple('test_multiple', __('Multiple', 'censeo'));
$test_multiple->add_options(array(
	'red'=>'Red',
	'orange'=>'Orange',
	'yellow'=>'Yellow',
	'lime'=>'Lime',
	'green'=>'Green',
	'cyan'=>'Cyan',
	'blue'=>'Blue',
	'purple'=>'Purple',
	'pink'=>'Pink',
));
// $test_multiple->set_render_variant(Censeo_Field_Multiple_Render_Variant::CHECKBOXES);

$test_boolean = new Censeo_Field_Boolean('test_boolean', __('Boolean', 'censeo'));
// $test_boolean->set_render_variant(Censeo_Field_Boolean_Render_Variant::CHECKBOX);
// $test_boolean->set_render_variant(Censeo_Field_Boolean_Render_Variant::RADIO);

$test_date = new Censeo_Field_Date('test_date', __('Date', 'censeo'));
$test_date->set_week_starts_on(Censeo_Field_Date_Week_Days::MONDAY);

$test_time = new Censeo_Field_Time('test_time', __('Time', 'censeo'));

$test_datetime = new Censeo_Field_DateTime('test_datetime', __('Date-time', 'censeo'));

$options_page->add_fields(array(
	$test_option,
	$test_number,
	$test_enumerable,
	$test_multiple,
	$test_boolean,
	$test_date,
	$test_time,
	$test_datetime,
));



/////////////////////////////////
// Social Options Page //////////
/////////////////////////////////
$social_options_page = new Censeo_Options('censeo-social-options', __('Social Options', 'censeo'), 'administrator', 'censeo-options');
?>