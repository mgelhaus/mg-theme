<?php 

$is_modified = \get_the_time( 'U' ) !== \get_the_modified_time( 'U' )
?>
				<div class="entry-meta">
					<span class="posted-on"><?php esc_html_e('Posted on', THEME_TEXT_DOMAIN); echo ' '; ?><time class="entry-date published<?php if ( !$is_modified ) echo ' updated'; ?>" datetime="<?php esc_attr_e( get_the_time( 'c' ) ); ?>"><?php esc_html_e( get_the_time('F j, Y') ); ?></time><?php if ( $is_modified ) : ?><time class="updated" datetime="<?php esc_attr_e( get_the_modified_time( 'c' ) ); ?>"><?php esc_html_e( get_the_modified_time('F j, Y') ); ?></time><?php endif;  echo PHP_EOL; ?>
					<span class="byline"><?php esc_html_e('by'); echo ' '; ?><a class="url fn n" href="<?php esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" title="<?php esc_attr_e( 'Author :', THEME_TEXT_DOMAIN ); esc_attr_e( get_the_author() ); ?>"><span class="author vcard"><?php esc_html_e( get_the_author() ); ?></span></a></span>
				</div>
<?php 
