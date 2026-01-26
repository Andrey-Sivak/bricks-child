<article id="brx-content" <?php post_class( 'WordPress' ); ?>>
	<?php
	the_content();
	echo Bricks\Helpers::page_break_navigation(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	?>
</article>
