<?php
/**
 * Options Page
 *
 * @package   Quick_Featured_Images_Pro_Admin
 * @author    Martin Stehle <m.stehle@gmx.de>
 * @license   GPL-2.0+
 * @link      http://quickfeaturedimages.com/
 * @copyright 2014 
 */

$qfi_tools_instance     = Quick_Featured_Images_Pro_Tools::get_instance();
$qfi_defaults_instance  = Quick_Featured_Images_Pro_Defaults::get_instance();
$qfi_settings_instance  = Quick_Featured_Images_Pro_Settings::get_instance();
$qfi_licensing_instance = Quick_Featured_Images_Pro_Licensing::get_instance();

?>
<h2 class="no-bottom"><?php _e( 'Manage featured images in a quick way', 'quick-featured-images-pro' ); ?></h2>
<div class="qfi_page_description">
	<p><?php echo $this->get_page_description(); ?></p>
</div>
<ul>
<?php
	/** 
	 * Bulk Edit Page Item
	 *
	 */
?>
	<li>
		<h3><?php echo $qfi_tools_instance->get_page_headline(); ?></h3>
<?php
if ( current_user_can( $qfi_tools_instance->get_required_user_cap() ) ) {
	printf( 
		'		<p><a href="%s"><span class="dashicons dashicons-admin-tools"></span><br />%s</a></p>',
		admin_url( sprintf( 'admin.php?page=%s', $qfi_tools_instance->get_page_slug() ) ),
		$qfi_tools_instance->get_page_description()
	);
} else {
?>
		<p><span class="dashicons dashicons-admin-tools"></span><br /><?php echo $qfi_tools_instance->get_page_description(); ?></p>
<?php
}
?>
	</li>
<?php
	/** 
	 * Presets Page Item
	 *
	 */
?>
	<li>
		<h3><?php echo $qfi_defaults_instance->get_page_headline(); ?></h3>
<?php
if ( current_user_can( $qfi_defaults_instance->get_required_user_cap() ) ) {
	printf( 
		'						<p><a href="%s"><span class="dashicons dashicons-images-alt"></span><br />%s</a></p>',
		admin_url( sprintf( 'admin.php?page=%s', $qfi_defaults_instance->get_page_slug() ) ),
		$qfi_defaults_instance->get_page_description()
	);
} else {
?>
		<p><span class="dashicons dashicons-admin-defaults"></span><br /><?php echo $qfi_defaults_instance->get_page_description(); ?></p>
<?php
}
?>
	</li>
<?php
	/** 
	 * Image Columns Page Item
	 *
	 */
?>
	<li>
		<h3><?php echo $qfi_settings_instance->get_page_headline(); ?></h3>
<?php
if ( current_user_can( $qfi_settings_instance->get_required_user_cap() ) ) {
	printf( 
		'						<p><a href="%s"><span class="dashicons dashicons-admin-settings"></span><br />%s</a></p>', 	
		admin_url( sprintf( 'admin.php?page=%s', $qfi_settings_instance->get_page_slug() ) ), 
		$qfi_settings_instance->get_page_description() 
	);
} else {
?>
		<p><span class="dashicons dashicons-admin-settings"></span><br /><?php echo $qfi_settings_instance->get_page_description(); ?></p>
<?php
}
?>
	</li>
<?php
	/** 
	 * License Page Item
	 *
	 */
?>
	<li>
		<h3><?php echo $qfi_licensing_instance->get_page_headline(); ?></h3>
<?php
if ( current_user_can( $qfi_licensing_instance->get_required_user_cap() ) ) {
	printf(
		'						<p><a href="%s"><span class="dashicons dashicons-admin-network"></span><br />%s</a></p>', 	
		admin_url( sprintf( 'admin.php?page=%s', $qfi_licensing_instance->get_page_slug() ) ), 
		$qfi_licensing_instance->get_page_description() 
	);
} else {
?>
		<p><span class="dashicons dashicons-admin-network"></span><br /><?php echo $qfi_licensing_instance->get_page_description(); ?></p>
<?php
}
?>
	</li>
</ul>
