<?php
/**
 * Represents the footer for the admin page
 *
 * @package   Quick_Featured_Images_Pro
 * @author    Martin Stehle <m.stehle@gmx.de>
 * @license   GPL-2.0+
 * @link      http://stehle-internet.de
 * @copyright 2013 Martin Stehle
 */
// check if file is called in an object context
// else use non-object context
if ( isset($this->plugin_slug ) ) {
	$text_domain = $this->plugin_slug;
} else {
	$text_domain = self::$plugin_slug;
}
?>
			</div><!-- .qfi_content -->
		</div><!-- #qfi_main -->
		<div id="qfi_footer">
			<div class="qfi_content">
<?php
/*
printf( '<pre>max_execution_time: %s</pre>', var_export( ini_get( 'max_execution_time' ), true ) );
printf( '<pre>memory_limit: %s</pre>', var_export( ini_get( 'memory_limit' ), true ) );
*/
?>
				<h2><?php _e( 'Credits and informations', $text_domain ); ?></h2>
				<dl>
					<dt><?php _e( 'Do you like the plugin?', $text_domain ); ?></dt><dd><a href="http://www.quickfeaturedimages.com<?php _e( '/reviews/', 'quick-featured-images-pro' ); ?>"><?php _e( 'Please rate it at quickfeaturedimages.com!', $text_domain ); ?></a></dd>
					<dt><?php _e( 'Do you need support or have an idea for the plugin?', $text_domain ); ?></dt><dd><a href="http://www.quickfeaturedimages.com/forums/"><?php _e( 'Post your questions and ideas in the forum at at quickfeaturedimages.com!', $text_domain ); ?></a></dd>
					<dt><?php _e( 'Special thanks for the fine frontend style of the plugin go to', $text_domain ); ?></dt><dd><a href="http://alexandra-mutter.de/?ref=quick-featured-images-pro"><?php echo get_avatar( 'allamoda07@googlemail.com', 44 ); ?>alexandra mutter design</a></dd>
				</dl>
			</div><!-- .qfi_content -->
		</div><!-- #qfi_footer -->
	</div><!-- .qfi_wrapper -->
</div><!-- .wrap -->
