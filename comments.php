<?php
if (!post_password_required()) {
	echo '<div id="comments" class="comments-area">';
	
	if (have_comments()) {
		echo '<h2 class="comments-title">';
		
		$comments_raw_number = get_comments_number();
		$comments_number = number_format_i18n($comments_raw_number);
		printf(_n('1 comment', '%1$s comments', $comments_raw_number, 'censeo'), $comments_number);
		
		echo '</h2>';
		
		echo '<ul class="commentlist">';
		wp_list_comments(array(/*'callback' => 'twentytwelve_comment',*/ 'style' => 'ul'));
		echo '</ul>';
		
		if (get_comment_pages_count() > 1 && get_option('page_comments')) {
			?>
			<nav id="comment-nav-below" class="navigation" role="navigation">
				<h1 class="assistive-text section-heading"><?php _e('Comment navigation', 'censeo'); ?></h1>
				<div class="nav-previous"><?php previous_comments_link(__('&larr; Older Comments', 'censeo')); ?></div>
				<div class="nav-next"><?php next_comments_link(__('Newer Comments &rarr;', 'censeo')); ?></div>
			</nav>
			<?php
		}
		
		if (!comments_open() && get_comments_number()) {
			echo '<p class="nocomments">' . __('Comments are closed.' , 'censeo') . '</p>';
		}
	}
	
	comment_form();
	
	echo '</div>';
}
?>