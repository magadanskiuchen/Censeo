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

$options_page->add_fields(array(
	$test_option,
	$test_number,
));



/////////////////////////////////
// Social Options Page //////////
/////////////////////////////////
$social_options_page = new Censeo_Options('censeo-social-options', __('Social Options', 'censeo'), 'administrator', 'censeo-options');
?>