<?php
defined('ABSPATH') || exit;
class JActiveDeactive
{
	public static function call_active()
	{		
		if ( ! current_user_can( 'activate_plugins' ) )
		{
            		return;
		}
        $plugin = isset( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';
        check_admin_referer( "activate-plugin_{$plugin}" );
		
		
		//Create table queries will go here.
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();
		$j_years = $wpdb->prefix . 'j_years';


			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');


		if ($wpdb->get_var("show tables like '$j_years'") != $j_years) {

							$sql = " CREATE TABLE $j_years (
								`yid` bigint(20) NOT NULL AUTO_INCREMENT,
								`yname` varchar(100) DEFAULT NULL,
								PRIMARY KEY (`yid`)
						) ENGINE=INNODB $charset_collate;";
											
						dbDelta($sql);
		}

				
		$j_weekly_data = $wpdb->prefix . 'j_weekly_data';
		if ($wpdb->get_var("show tables like '$j_weekly_data'") != $j_weekly_data) {

							$sql = " CREATE TABLE $j_weekly_data (
								`wid` bigint(20) NOT NULL AUTO_INCREMENT,
								`wyid` bigint(20) NOT NULL,
								`wdate` DATE NOT NULL,
								`wgain` double NOT NULL,
								`wnotifydate` DATE NULL,								
								CONSTRAINT j_weekly_year
								FOREIGN KEY (wyid) 
								REFERENCES $j_years(yid) ON UPDATE CASCADE ON DELETE CASCADE,
								PRIMARY KEY (`wid`)
						) ENGINE=INNODB $charset_collate;";

							dbDelta($sql);								
		}		
	}
	static function call_deactive()
	{
		if ( ! current_user_can( 'activate_plugins' ) ){return;}            	
        	$plugin = isset( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';
        	check_admin_referer( "deactivate-plugin_{$plugin}" );
		
		//If you want to remove some data from table then those queries will go here.
	}
	static function call_uninstall()
	{
		if ( ! current_user_can( 'activate_plugins' ) ) {return;}
        	check_admin_referer( 'bulk-plugins' );
		
		if (!defined('WP_UNINSTALL_PLUGIN')) {
    				die;
			}
		
		//If you want to remove some data from table then those queries will go here.
	}
}