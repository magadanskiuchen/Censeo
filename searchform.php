<?php
global $search_id;
$search_id++;

$search_val = isset($_GET['s']) ? $_GET['s'] : '';
?>
<form role="search" method="get" class="searchform" action="<?php echo site_url('/'); ?>">
	<label class="screen-reader-text" for="s-<?php echo $search_id; ?>"><?php _e('Search for:', 'censeo'); ?></label>
	<input type="text" name="s" id="s-<?php echo $search_id; ?>" placeholder="<?php esc_attr_e('Search for:', 'censeo'); ?>" value="<?php echo esc_attr($search_val); ?>" />
	<input type="submit" value="Search" />
</form>