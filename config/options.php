<?php
/*
Sample usage:
$options_page = new Censeo_Options($id, $title, $capabilities='administrator', $parent=false);

$sample_field = new Censeo_Field($name, $label);

$sample_number_field = new Censeo_Field_Number($name, $label);
$sample_number_field->set_min(0);
$sample_number_field->set_max(100);

$options_page->add_fields(array(
	$sample_field,
	$sample_number_field,
));
*/
?>