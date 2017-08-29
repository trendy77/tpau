<?php

$IDENTIFIER ='tpau';
require_once('/home/organ151/Scripts/greyMatter/tSiteNoClass.php');

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
    define('ABSPATH', dirname(__FILE__) . '/');

/** Location of your WordPress configuration. */
require('/home/organ151/Scripts/greyMatter/'. $IDENTIFIER . '.php');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

	