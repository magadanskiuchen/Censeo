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
$test_enumerable->add_options(array('No', 'Yes'));
$test_enumerable->set_render_variant(Censeo_Field_Enumerable_Render_Variant::RADIO);

$options_page->add_fields(array(
	$test_option,
	$test_number,
	$test_enumerable,
));



/////////////////////////////////
// Social Options Page //////////
/////////////////////////////////
$social_options_page = new Censeo_Options('censeo-social-options', __('Social Options', 'censeo'), 'administrator', 'censeo-options');
?>