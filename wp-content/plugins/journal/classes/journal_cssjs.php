<?php 
defined( 'ABSPATH' ) || exit;
class JournalCssJs
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
        add_action( 'wp_enqueue_scripts' ,  array($this,'j_front_enqueue_scripts'));
        add_action( 'admin_enqueue_scripts' ,  array($this,'j_admin_enqueue_scripts'));
    }
    public function j_front_enqueue_scripts()
    {
            wp_register_style( 'j-front-css-common', JOURNAL_PLUGIN_URL."css/j-front-second.css");
            wp_register_style( 'j-front-tabs', '//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');
            
            wp_register_script( 'j-front-js-common',JOURNAL_PLUGIN_URL."js/j-front-second.js" , array('jquery'), '',true );
            wp_localize_script( 'j-front-js-common',"ajObj" ,array("url" => admin_url("admin-ajax.php") , "nonce" => wp_create_nonce('j-up-profile')) );

    }
    public function j_admin_enqueue_scripts()
    {
        wp_register_style( 'j-back-css-common', JOURNAL_PLUGIN_URL."css/j-admin-second.css");
        wp_register_script( 'j-back-js-common', JOURNAL_PLUGIN_URL."js/j-admin-second.js", array('jquery'),'' ,true );
        //wp_enqueue_style('j-back-css-common');
        //wp_enqueue_script('j-back-js-common');
    }
}
?>