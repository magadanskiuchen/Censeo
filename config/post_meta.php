<?php
/*
Sample usage:
$meta_box = new Censeo_Pots_Meta($id, $title, $post_type = 'post');
*/

$post_meta = new Censeo_Post_Meta('post_meta', __('Post Options', 'censeo'), 'post');

$test_field = new Censeo_Field('test', __('Test', 'censeo'));

$post_meta->set_fields(array(
	$test_field,
));
?>