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

$test_select = new Censeo_Field_Select('test_select', __('Select', 'censeo'));
$test_select->add_options(array('blue'=>'Blue', 'red'=>'Red', 'yellow'=>'Yellow'));
// $test_select->set_options(array('No', 'Yes'));

$options_page->add_fields(array(
	$test_option,
	$test_number,
	$test_select,
));



/////////////////////////////////
// Social Options Page //////////
/////////////////////////////////
$social_options_page = new Censeo_Options('censeo-social-options', __('Social Options', 'censeo'), 'administrator', 'censeo-options');
?>