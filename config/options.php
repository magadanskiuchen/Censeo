<?php
/*
Sample usage:
$options_page = new Censeo_Options($id, $title, $capabilities='administrator', $parent=false);
*/

$options_page = new Censeo_Options('censeo-options', __('Censeo Options', 'censeo'));
$social_options_page = new Censeo_Options('censeo-social-options', __('Social Options', 'censeo'), 'administrator', 'censeo-options');
?>