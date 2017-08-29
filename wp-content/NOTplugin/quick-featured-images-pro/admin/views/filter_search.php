<h3><?php echo $this->valid_filters[ 'filter_search' ]; ?></h3>
<p><?php _e( 'If you want to search for an exact phrase surround it with double quotes.', 'quick-featured-images-pro' ); ?></p>
<p>
	<label for="qfi_search_term"><?php _e( 'Type in a search term', 'quick-featured-images-pro' ); ?></label>
	<input type="text" id="qfi_search_term" name="search_term" value="<?php if ( $this->selected_search_term ) { echo stripslashes( esc_attr( $this->selected_search_term ) ); } ?>" />
</p>
<p>
<?php
foreach ( $this->valid_search_options as $key => $label ) {
?>
	<input type="checkbox" id="<?php printf( 'qfi_%s', $key ); ?>" name="search_options[<?php echo $key; ?>]" value="1" <?php checked( isset( $this->selected_search_options[ $key ] ) ); ?> />
	<label for="<?php printf( 'qfi_%s', $key ); ?>"><?php echo $label; ?></label><br>
<?php
}
?>
</p>
