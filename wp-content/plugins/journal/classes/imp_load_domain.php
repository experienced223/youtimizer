<?php
defined( 'ABSPATH' ) || exit;
class JLoadDomain
{
    function init()
    {
        load_plugin_textdomain( 'j-data', FALSE, basename( IMPCSV_PATH ) . '/languages/' );
    }
}
?>