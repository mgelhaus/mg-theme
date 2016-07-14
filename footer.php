<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package MG\Theme
 */

?>
	</main>

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="site-info">
			</span><?php printf( esc_html__( 'Theme by %1$s.', THEME_TEXT_DOMAIN ), '<a href="https://github.com/mgelhaus" rel="designer">Michael Gelhaus</a>' ); echo "\n"; ?>
		</div>
	</footer>

<?php wp_footer(); ?>

</body>
</html>
