<?php 
final class ExtendedFunctions
{
	protected  static $_instance;
	public static function instance()
	{
		if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }

        return self::$_instance;
	}
	function __construct()
	{
		$this->define_constants();
        $this->includes();
        $this->init_hooks();
	}
	private function define_constants()
    {
        if (!defined("JOURNAL_PLUGIN_PATH")) {
            define("JOURNAL_PLUGIN_PATH", dirname(JOURNAL_PLUGIN_FILE_PATH));//Plugin directory path without slash /
        }

        if (!defined("JOURNAL_PLUGIN_URL")) {
            define("JOURNAL_PLUGIN_URL", plugin_dir_url(JOURNAL_PLUGIN_FILE_PATH)); //Plugin url path with slash /
        }
    }
    private  function includes()
    {

        require_once(JOURNAL_PLUGIN_PATH . "/classes/imp_load_domain.php");
        require_once(JOURNAL_PLUGIN_PATH . "/classes/j_active_deactive.php");
        require_once(JOURNAL_PLUGIN_PATH . "/classes/journal_api.php");
        require_once(JOURNAL_PLUGIN_PATH . "/classes/journal_ajax.php");
        require_once(JOURNAL_PLUGIN_PATH . "/classes/journal_cssjs.php");
        require_once(JOURNAL_PLUGIN_PATH . "/classes/journal_pages.php");
        require_once(JOURNAL_PLUGIN_PATH . "/classes/journal_shortcodes.php");
        
    }
    private function init_hooks()
    {
        //Activation Hook
            register_activation_hook(JOURNAL_PLUGIN_FILE_PATH, array('JActiveDeactive', "call_active"));

        //DeActivation Hook
            register_deactivation_hook(JOURNAL_PLUGIN_FILE_PATH, array('JActiveDeactive', "call_deactive"));

        //Uninstall Hook            
            register_uninstall_hook(JOURNAL_PLUGIN_FILE_PATH, array('JActiveDeactive', "call_uninstall"));
        
        
        add_action('plugins_loaded', array('JLoadDomain', "init"));
        add_action("init", array('JournalAjaxRequests', "init"));
        add_action("init", array('JournalCssJs', "init"));
        add_action("init" , array('JornalPages' , 'init'));
        add_action("init", array('JournalShorcode', "init"));
    }
}
?>