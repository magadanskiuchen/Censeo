<div id="post-<?php echo get_the_ID(); ?>" <?php post_class(); ?> itemscope itemtype="http://schema.org/Article">
	<meta itemprop="url" content="<?php the_permalink(); ?>" />
	<h2 itemprop="name">
		<?php
		echo (!is_single()) ? ('<a href="' . get_permalink(get_the_ID()) . '" rel="bookmark">') : '';
		the_title();
		echo (!is_single()) ? '</a>' : '';
		?>
	</h2>
	
	<?php the_content(); ?>
</div>
