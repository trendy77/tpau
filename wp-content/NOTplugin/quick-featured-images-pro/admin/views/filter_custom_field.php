<h3><?php echo $this->valid_filters[ 'filter_custom_field' ]; ?></h3>
<?php 
$cf_operators = array(
	'='		=> __( 'is equal', 'quick-featured-images-pro' ), 
	'!='	=> __( 'is not equal', 'quick-featured-images-pro' ),
	'>'		=> __( 'is greater than', 'quick-featured-images-pro' ),
	//'>=', 
	'<'		=> __( 'is lower than', 'quick-featured-images-pro' ),
	//'<=', 
	'LIKE'	=> __( 'contains', 'quick-featured-images-pro' ),
	//'NOT LIKE', 
	//'IN', 
	//'NOT IN', 
	//'BETWEEN', 
	//'NOT BETWEEN', 
	//'EXISTS',
	//'NOT EXISTS'
);
/* for future 
$cf_types = array(
	'CHAR',
	'NUMERIC',
	'BINARY',
	'DATE', 
	'DATETIME', 
	'DECIMAL', 
	'SIGNED', 
	'TIME', 
	'UNSIGNED'
);
*/
$custom_field_keys = $this->get_custom_field_keys();
if ( $custom_field_keys ) {
	foreach ( $this->valid_custom_field as $key => $label ) {
		switch ( $key ) {
			case 'key':
?>
<p>
	<?php _e( 'Select the custom field to find the associated posts/pages.', 'quick-featured-images-pro' ); ?><br />
	<label for="<?php printf( 'qfi_%s', $key ); ?>"><?php echo $label; ?></label><br />
	<select id="<?php printf( 'qfi_%s', $key ); ?>" name="custom_field[<?php echo $key; ?>]">
<?php
				print $this->get_html_options_strings( $this->selected_custom_field, $key, $custom_field_keys );
?>
	</select>
</p>
<?php 
				break;
			case 'value':
?>
<p>
	<?php _e( 'Optional: Type in the value which will be compared with the value of the selected custom field.', 'quick-featured-images-pro' ); ?>
	<?php _e( 'Leave it empty if you just want to test the existence of the custom field per post/page.', 'quick-featured-images-pro' ); ?>
	<br />
	<label for="<?php printf( 'qfi_%s', $key ); ?>"><?php echo $label; ?></label>
	<input type="text" id="<?php printf( 'qfi_%s', $key ); ?>" name="custom_field[<?php echo $key; ?>]" value="<?php if ( isset( $this->selected_custom_field[ $key ] ) ) { echo $this->selected_custom_field[ $key ]; } ?>" />
</p>
<?php 
				break;
			case 'compare':
?>
<p>
	<?php _e( 'Optional: Change the operator of the comparison. The default is to compare equality with the value you type in the \'value\' field.', 'quick-featured-images-pro' ); ?><br />
	<label for="<?php printf( 'qfi_%s', $key ); ?>"><?php echo $label; ?></label><br />
	<select id="<?php printf( 'qfi_%s', $key ); ?>" name="custom_field[<?php echo $key; ?>]">
<?php
				print $this->get_html_options_strings( $this->selected_custom_field, $key, $cf_operators );
?>
	</select>
</p>
<?php 
		} // switch()
	} // foreach()
} else {
?>
<p><?php _e( 'There are no custom fields in use.', 'quick-featured-images-pro' ); ?></p>
<?php 
} // if()
?>
</p>
