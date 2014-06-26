<?php
/**
 * Censeo admin panel fields
 * 
 * @package Censeo
 * @subpackage Fields
 */

$fields = array(
	'Censeo_Field',
	'Censeo_Field_Number',
	'Censeo_Field_Enumerable',
	'Censeo_Field_Multiple',
	'Censeo_Field_Boolean',
	'Censeo_Field_Date',
	'Censeo_Field_Time',
	'Censeo_Field_DateTime',
	'Censeo_Field_Color',
	'Censeo_Field_Location',
	'Censeo_Field_File',
);

foreach ($fields as $field) {
	require_once(CENSEO_LIB . 'fields' . DIRECTORY_SEPARATOR . $field . '.php');
}

?>