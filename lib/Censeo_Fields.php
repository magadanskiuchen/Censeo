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
	'Censeo_Field_Select',
);

foreach ($fields as $field) {
	require_once(CENSEO_LIB . 'fields' . DIRECTORY_SEPARATOR . $field . '.php');
}

?>