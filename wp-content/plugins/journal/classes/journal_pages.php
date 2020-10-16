<?php 
defined( 'ABSPATH' ) || exit;
class JornalPages
{
    protected static $_instance = null;
    public static function init() {
        
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }        
        return self::$_instance;
    }
    public function __construct()
    {
        add_action("admin_menu", array($this, "add_menu_admin"));
    }
    function add_menu_admin()
    {
        add_submenu_page( "clj-settings", __("Years" , "j-data"),__("Years" , "j-data"), 'administrator', 'j-years', array($this,"show_all_years") ); 
        add_submenu_page( "clj-settings", __("Gain Notification" , "j-data"),__("Gain Notification" , "j-data"), 'administrator', 'j-weekly-gain', array($this,"show_all_gains") );               
    }
    
    function show_all_years()
    {
        include(JOURNAL_PLUGIN_PATH.'/classes/journal_years.php');
    }
    function show_all_gains()
    {
        include(JOURNAL_PLUGIN_PATH.'/classes/journal_weekly_entries.php');
    }
}
?>