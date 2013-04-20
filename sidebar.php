<div id="sidebar">
	<?php
	if (!dynamic_sidebar('default-sidebar')) {
		global $wp_registered_sidebars;
		
		foreach ($wp_registered_sidebars as $id => $options) {
			if ($id == 'default-sidebar') {
				foreach (censeo_default_widgets() as $widget_action) {
					do_action($widget_action, $options);
				}
				
				break;
			}
		}
	}
	?>
</div>