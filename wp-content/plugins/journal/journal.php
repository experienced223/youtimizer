<?php
/*
	Plugin Name: Journal Setup
	Plugin URI: http://www.cloudweblabs.com/
	Description: This plugin reads data from one journal and after performing some calculation inserts it into another journal.
	Version: 1.5
	Author: Cloudweblabs
	Author URI: http://www.cloudweblabs.com/
	Text Domain: j-data
    Domain Path: /languages
*/

defined( 'ABSPATH' ) || exit;
global $wp;
function logged_in_redirect()
{
    if ( is_user_logged_in() == true ) {
       if(add_query_arg( $wp->query_vars, home_url( $wp->request ) ) == "/?=http://youtimizer.com") {
		   	wp_redirect( "http://youtimizer.com/club-performance" );
		}
    }
}
add_action('init', 'logged_in_redirect');

function clr_crate_table()
{
	
	global $wpdb;
	$clr_jdata = $wpdb->prefix."clr_jdata";	
	$charset_collate = $wpdb->get_charset_collate();
	
	if( $wpdb->get_var( "show tables like '$clr_jdata'" ) != $clr_jdata ) {
		 $clr_jdata_sqld = "CREATE TABLE IF NOT EXISTS  $clr_jdata(
			`clr_id` bigint(20) NOT NULL AUTO_INCREMENT,
			`clr_gain_text` varchar(250) DEFAULT NULL,
			`clr_start_date` date DEFAULT NULL,
			`clr_close_date` date DEFAULT NULL,
			`clr_accounting_year` varchar(50) DEFAULT NULL,
			`clr_total_members` varchar(100) DEFAULT NULL,
			`clr_total_deposite` varchar(250) DEFAULT NULL,
			`clr_gain_percentage` varchar(50) DEFAULT NULL,
			`clr_total_gain` varchar(250) DEFAULT NULL,
			`clr_payment_date` date DEFAULT NULL,
			`clr_report_file` varchar(250) DEFAULT NULL,
            PRIMARY KEY  (clr_id)
		) ENGINE=MyISAM $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $clr_jdata_sqld );
	}
}
register_activation_hook( __FILE__, 'clr_crate_table' );
add_action("admin_enqueue_scripts","make_clj_script");
function make_clj_script()
{
	wp_register_script("tinymceee","//cdn.tinymce.com/4/tinymce.min.js");	
	wp_register_style("admin-journal",plugin_dir_url(__FILE__)."css/journal.css");
	wp_register_style("admin-sumess",plugin_dir_url(__FILE__)."css/sumoselect.min.css");
	wp_register_style("admin-pr",plugin_dir_url(__FILE__)."css/parsley.css");
	wp_register_style("jquery-ui.css","//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css");	
	wp_register_script("sumess",plugin_dir_url(__FILE__)."js/jquery.sumoselect.min.js",array("jquery"),"1.5",true);		
	wp_register_script("prsl",plugin_dir_url(__FILE__)."js/parsley.min.js",array("jquery"),"1.5",true);	
	
}
add_action('admin_menu', 'clj_create_menu');
function clj_create_menu() {
	add_menu_page(__("Youtimizer", "journal"), __("Youtimizer", "journal"), "manage_options", "clj-settings", "journal_settings_page", "dashicons-megaphone");
	add_submenu_page("clj-settings",__("Add New Period", "journal"), __("Add New Period", "journal"), "manage_options", "add-new-period", "add_new_period");
	add_submenu_page("clj-settings",__("All Records","journal"),__("All Records","journal"),"manage_options","clj-all-records","clj_all_records");
}
add_action( 'admin_init', 'register_clsettings' );
function register_clsettings() 
{
	register_setting( 'clj-settings-general', 'clj_settings' );
}

function journal_settings_page() {
	ini_set('max_execution_time', 0);
	wp_enqueue_style("jquery-ui.css");
    wp_enqueue_script('jquery-ui-tabs');
	wp_enqueue_style("admin-journal");
	wp_enqueue_style("admin-sumess");	
	wp_enqueue_style("admin-pr");
	wp_enqueue_script("sumess");
	wp_enqueue_script("prsl");
	wp_enqueue_script("tinymceee");
	$get_clj_settings = get_option("clj_settings");
			
	$jarray	= cl_get_request("https://restapi.e-conomic.com/journals-experimental?pageSize=100",false,array(),"journal_extract_request");
	$contra_account	= cl_get_request("https://restapi.e-conomic.com/accounts?pageSize=100",false,array(),"account_extract_request");
?>
<div class="wrap jdata">
<h1><?php _e('All Journal Settings', 'epicwebs'); ?></h1>
<form id="all_settings" method="post" action="options.php">
	
	<?php if(isset( $_GET['settings-updated'])) { ?>
	<div class="updated">
        <p><?php _e('Settings updated successfully', $textdomain); ?></p>
    </div>
	<?php } ?>    
    
<div id="jtabs">

    <ul>
        <li><a href="#jtabs-1"><span>Api & Journal settings</span></a></li> 
        <li><a href="#jtabs-2"><span>Send Report email settings</span></a></li>           
    </ul>    
    <div id="jtabs-1">
      	<table class="form-table">
        	<tbody>
            
            	<tr class="form-field">
                	<th scope="row">
                    	App secret token
                    </th>                    
                    <td>
                    <div class="clrow">
                    	<div class="col-6">
                    		<input type="text" name="clj_settings[secret_token]" value="<?php echo isset($get_clj_settings['secret_token']) ? $get_clj_settings['secret_token']:"";?>" required="required"/>
                        </div>                       
                    </div>
                    </td>
                </tr>
                
                <tr class="form-field">
                	<th scope="row">
                    	Grant token
                    </th>
                    
                    <td>
                    <div class="clrow">
                    	<div class="col-6">
                    		<input type="text" name="clj_settings[grant_token]" value="<?php echo isset($get_clj_settings['grant_token']) ? $get_clj_settings['grant_token']:"";?>" required="required"/>
                        </div>                        
                    </div>
                    </td>
                </tr>
                            
            	<tr class="form-field">
                	<th scope="row">
                    	Deposit Account"
                    </th>                    
                    <td>
                    <div class="clrow">
                    	<div class="col-6">
                        
                        <select name="clj_settings[deposite_journal]" id="deposite_journal" <?php echo !empty($contra_account)? "required" :""?>>
                        	<option value="" selected="selected"> === Select value === </option>
                            <?php
                            if(!empty($contra_account))
								{
									foreach($contra_account as $eee)	
									{
										$selectedd = ($eee['aid'] == $get_clj_settings['deposite_journal']) ? "selected='selected'":"";
print <<<RRR
										<option value="{$eee['aid']}" {$selectedd}>{$eee['aname']}</option>
RRR;
									}
								}                            
							?>
                        </select>
                        
                        <input type="hidden" name="clj_settings[deposite_journal_name]" value="">
                    		
                        </div>                       
                    </div>
                    </td>
                </tr>
                
                <tr class="form-field">
                	<th scope="row">
                    	Gain Journal
                    </th>
                    
                    <td>
                    <div class="clrow">
                    	<div class="col-6">
                        
                            <select name="clj_settings[gain_journal]" id="gain_journal" <?php echo !empty($jarray)? "required" :""?>>
                                <option value="" selected="selected"> === Select value === </option>
                                <?php
								if(!empty($jarray))
								{
									foreach($jarray as $k)
									{
										$selectedd = ($k['jid'] == $get_clj_settings['gain_journal']) ? "selected='selected'":"";
print <<<RRR
										<option value="{$k['jid']}" {$selectedd}>{$k['jname']}</option>
RRR;
									}
								}
							?>
                            </select>                                            		
                            <input type="hidden" name="clj_settings[gain_journal_name]" value="">  
                        </div>                        
                    </div>
                    </td>
                </tr>
                
                <tr class="form-field">
                	<th scope="row">
                    	Gain Account
                    </th>
                    
                    <td>
                    <div class="clrow">
                    	<div class="col-6">
                        
                        <select name="clj_settings[contra_deposite]" id="contra_deposite" <?php echo !empty($contra_account)? "required" :""?>>
                               <option value="" selected="selected"> === Select value === </option>
                                <?php
								if(!empty($contra_account))
								{
									foreach($contra_account as $c)	
									{
										$selectedd = ($c['aid'] == $get_clj_settings['contra_deposite']) ? "selected='selected'":"";
print <<<RRR
										<option value="{$c['aid']}" {$selectedd}>{$c['aname']}</option>
RRR;
									}
								}
							?>
                        </select> 
                                    <input type="hidden" name="clj_settings[contra_deposite_name]" value="">                                       		
                        </div>                        
                    </div>
                    </td>
                </tr>                                            
                <tr class="form-field">
                	<th scope="row">
                    	Enter Currency Value 
                    </th>
                    
                    <td>
                    <div class="clrow">
                    	<div class="col-6">
                    		<input type="text" name="clj_settings[currency_val]" value="<?php echo isset($get_clj_settings['currency_val']) ? $get_clj_settings['currency_val']:"";?>" required="required"/>
                        </div>                        
                    </div>
                    </td>
				</tr>
				
				<tr class="form-field">
                	<th scope="row">
						Departmental Distribution
                    </th>
                    
                    <td>
                    <div class="clrow">
                    	<div class="col-6">
                    		<input type="text" name="clj_settings[departmental_distribution]" value="<?php echo isset($get_clj_settings['departmental_distribution']) ? $get_clj_settings['departmental_distribution']:"";?>" required="required"/>
                        </div>                        
                    </div>
                    </td>
				</tr>
				<tr class="form-field">
                	<th scope="row">
						Post Request Journal Number
                    </th>                    
                    <td>
                    <div class="clrow">
                    	<div class="col-6">
                    		<input type="text" name="clj_settings[gain_journal_journalNumber]" value="<?php echo isset($get_clj_settings['gain_journal_journalNumber']) ? $get_clj_settings['gain_journal_journalNumber']:"";?>" required="required"/>
                        </div>                        
                    </div>
                    </td>
				</tr>				
                
            </tbody>	
        </table>
    </div>    
    <div id="jtabs-2">
      	<table class="form-table">
        	<tbody>            
            	<tr class="form-field">
                	<th scope="row">
                    	Email Subject
                    </th>                    
                    <td>
                    <div class="clrow">
                    	<div class="col-6">
                    		<input type="text" name="clj_settings[send_report_subject]" value="<?php echo isset($get_clj_settings['send_report_subject']) ? $get_clj_settings['send_report_subject']:"";?>" />
                        </div>                       
                    </div>
                    </td>
                </tr>                
                <tr class="form-field">
                	<th scope="row">
                    	From Name
                    </th>                    
                    <td>
                    <div class="clrow">
                    	<div class="col-6">
                    		<input type="text" name="clj_settings[send_report_from_name]" value="<?php echo isset($get_clj_settings['send_report_from_name']) ? $get_clj_settings['send_report_from_name']:"";?>" />
                        </div>                        
                    </div>
                    </td>
                </tr>                            
            	<tr class="form-field">
                	<th scope="row">
                    	From Email
                    </th>
                    <td>
                    <div class="clrow">
                    	<div class="col-6">
                                  <input type="text" name="clj_settings[send_report_from_email]" value="<?php echo isset($get_clj_settings['send_report_from_email']) ? $get_clj_settings['send_report_from_email']:"";?>" />                                  		
                        </div>                       
                    </div>
                    </td>
                </tr>                
                <tr class="form-field">
                	<th scope="row">
                    	Email Body                        
                    </th>
                    <td>
                    <div class="clrow">
                    	<div class="col-6">                        
                       Use below Keywords in email body:<br> %%user_name%%<br> %%gain_text%%<br>%%start_date%%<br>%%close_date%%<br>%%accounting_year%%<br>%%total_members%%<br>%%total_deposite%%<br>%%gain_percentage%%<br>%%total_gain%%<br>%%payment_date%%<br />                        
						<textarea id="textarea_id" name="clj_settings[send_report_body]" cols="50" rows="50"><?php echo $get_clj_settings['send_report_body']?></textarea>                         
                        </div>                        
                    </div>
                    </td>
				</tr>     
				<tr class="form-field">
                	<th scope="row">
                    	Weekly Notification Email Body                        
                    </th>
                    <td>
                    <div class="clrow">
                    	<div class="col-6">                        
						Use below Keywords in pdf body:<br/>%%user_name%%<br/>%%date%%<br/>%%gain_value%%<br/>%%cumulative_value%%<br/>
						<textarea id="textarea_id2" name="clj_settings[weekly_notification_body]" cols="50" rows="50"><?php echo $get_clj_settings['weekly_notification_body']?></textarea>                         
                        </div>                        
                    </div>
                    </td>
                </tr>               
                
            </tbody>	
        </table>
    </div>
    
</div>
<script>
jQuery(document).ready(function() {
    tinyMCE.init({
        mode : "none",
		   width: "500",
        height: "300",
    });
    tinyMCE.execCommand('mceAddEditor', false, 'textarea_id');
	tinyMCE.execCommand('mceAddEditor', false, 'textarea_id2');
});
</script>
<script>
jQuery(document).ready(function($){
	$('#jtabs').tabs();	
	$("#deposite_journal").SumoSelect({search:true,searchText:"Search Deposit Journal..."});
	$("#gain_journal").SumoSelect({search:true,searchText:"Search Gain Journal..."});
	$("#contra_deposite").SumoSelect({search:true,searchText:"Contra Account Deposit..."});
	$("#contra_gain").SumoSelect({search:true,searchText:"Contra Account Gain..."});
	$("#bank_account").SumoSelect({search:true,searchText:"Bank Account..."});	
	$('#all_settings').parsley();
jQuery("#all_settings").on("submit",function(){	
		var deposite_journal = $("select[name='clj_settings[deposite_journal]'] option:selected").text();
		var gain_journal = $("select[name='clj_settings[gain_journal]'] option:selected").text();
		var contra_deposite = $("select[name='clj_settings[contra_deposite]'] option:selected").text();
		var contra_gain = $("select[name='clj_settings[contra_gain]'] option:selected").text();		
		$("input[name='clj_settings[deposite_journal_name]']").val(deposite_journal);
		$("input[name='clj_settings[gain_journal_name]']").val(gain_journal);
		$("input[name='clj_settings[contra_deposite_name]']").val(contra_deposite);
		$("input[name='clj_settings[contra_gain_name]']").val(contra_gain);					
	});
});
</script>    
		<?php settings_fields( 'clj-settings-general' ); ?>
		<?php do_settings_sections( 'clj-settings-general' ); ?>        
    <?php submit_button(); ?>
</form>
</div>
<?php }
function cl_get_request($jurll,$next,$jarray=array(),$mapping_callback)
{
		
	do
	{
			$journal_data = cl_get_data($jurll);
			
			if(is_array($journal_data))
			{		
							$collection = $journal_data['collection'];			
							$desired_array = array_map($mapping_callback, $collection);							
							$jarray = array_merge($jarray,$desired_array);
							
							$next = isset($journal_data['pagination']['nextPage']) ? true : false;
							$jurll = isset($journal_data['pagination']['nextPage']) ? $journal_data['pagination']['nextPage'] : "";														
			}
			
	}while($next === true);
		
	
	return $jarray;
}	
function cl_get_data($api_url)
{
	
		$get_clj_settings = get_option("clj_settings");
		
		$result_data = wp_remote_get( $api_url ,
             array(			 			 		
    		'redirection' => 10,
    		'httpversion' => '1.0',
    		'blocking'    => true,
	    	'sslverify'   => false,	
			'timeout' => 1000,
            'headers' => array(
								'X-AppSecretToken' => isset($get_clj_settings['secret_token']) ? $get_clj_settings['secret_token']:"",
                                'X-AgreementGrantToken'=> isset($get_clj_settings['grant_token']) ? $get_clj_settings['grant_token']:"",
							    'Content-Type'=>"application/json"
							   ) 
             ));
			 
			 $res_code = wp_remote_retrieve_response_code($result_data);
			 
			 if($res_code == 200)
			 {
					$dataa = json_decode($result_data['body'],true);			 			 
					return $dataa;
			 }	
			 else
			 {
					return false; 
			 }			 
}
function journal_extract_request($v)
{
	
 		$temp = array();
		$temp['jid'] = $v['journalNumber'];
		$temp['jname'] = $v['name'];
  return($temp);
}
function account_extract_request($v)
{
	
 		$temp = array();
		$temp['aid'] = $v['accountNumber'];
		$temp['aname'] = $v['name'];
  return($temp);
}
function accounting_request_extract_request($v)
{
	
		$temp = array();
		$temp['year'] = $v['year'];
  return($temp);
}
$GLOBALS['supplier_amount'] = array();
function journal_detail_extract_request($v)
{
		if(!isset($v['departmentalDistribution']))
		{
			$GLOBALS['supplier_amount'][$v['supplier']['supplierNumber']][] = $v['amount'];
		}
}
function add_new_period()
{
	session_start();
	$get_clj_settings = get_option("clj_settings");
	$deposite_journal = $get_clj_settings['deposite_journal'];
	$gain_journal = $get_clj_settings['gain_journal'];
	$up_msggg = "";
	
	global $wpdb;
	$clr_jdata = $wpdb->prefix."clr_jdata";
	
	if(isset($_REQUEST['act']) && $_REQUEST['act']=="complete_request")
	{		
		if(isset($_SESSION['store_to_db_array']['start_date']) && isset($_SESSION['store_to_db_array']['close_date']) && isset($_SESSION['store_to_db_array']['payment_date']) && isset($_SESSION['store_to_db_array']['period_name']) && isset($_SESSION['store_to_db_array']['accounting_year']) && isset($_SESSION['store_to_db_array']['gain_percentage']) && !empty($_SESSION['suppliers_array']))
		{
						$ressponse_returned = cl_update_gain_journal();
						if($ressponse_returned)
						{
								$up_msggg = "The gains for the period has been posted in Journal Entries.";
								$wpdb->insert($clr_jdata,array(
								
										"clr_gain_text"=>$_SESSION['store_to_db_array']["period_name"],
										"clr_start_date"=>$_SESSION['store_to_db_array']["start_date"],
										"clr_close_date"=>$_SESSION['store_to_db_array']["close_date"],
										"clr_total_members"=>$_SESSION['store_to_db_array']["total_members"],
										"clr_total_deposite"=>$_SESSION['store_to_db_array']['total_deposite'],
										"clr_accounting_year" => $_SESSION['store_to_db_array']["accounting_year"],
										"clr_gain_percentage"=>$_SESSION['store_to_db_array']["gain_percentage"],
										"clr_total_gain"=>$_SESSION['store_to_db_array']['total_gian'],
										"clr_payment_date"=>$_SESSION['store_to_db_array']["payment_date"],
										//"clr_entryType"=>$_SESSION['store_to_db_array']["entryType"],
										"clr_report_file"=>""
									)
								);
								unset($GLOBALS['supplier_amount']);
						}
						else
						{
								$up_msggg = "Error: Gain data not inserted.";
								
						}			
		}
	}	
	unset($_SESSION['store_to_db_array']);
	unset($_SESSION['suppliers_array']);
	wp_enqueue_style("jquery-ui.css");
	wp_enqueue_style("admin-journal");
	wp_enqueue_style("admin-pr");	
	wp_enqueue_script("prsl");
	wp_enqueue_script('jquery-ui-datepicker');	
	$accounting_years	= cl_get_request("https://restapi.e-conomic.com/accounting-years?pageSize=100",false,array(),"accounting_request_extract_request");	
	?>
    <div class="wrap jdata">
    	<h1>Add New Period</h1>
			<?php
            $noooooo = "";
			if(isset($_POST['entered_closed_date']))
			{
					
				ini_set('max_execution_time', 0);

                $close_date = date("Y-m-d",strtotime(str_replace("/","-",$_POST['entered_closed_date'])));				
				$close_date_year = date("Y",strtotime($close_date));
				$accounting_year_settt = $close_date_year;
				$account_yeasrsss = array_column($accounting_years, 'year');
				//echo "<pre>"; print_r($account_yeasrsss);exit;
				
				//while( in_array($close_date_year, $account_yeasrsss))
				foreach($account_yeasrsss as $close_date_year)
				{									
					$get_journal_data = cl_get_request('https://restapi.e-conomic.com/accounts/'.$deposite_journal.'/accounting-years/'.$close_date_year.'/entries?filter=date$lte:'.$close_date.'&pageSize=100',false,array(),"journal_detail_extract_request");					
					
					//$close_date_year =  $close_date_year-1;
				}
				                                
                if(!empty($GLOBALS['supplier_amount']))
                {
                        $total_deposite = 0;
						$total_gian = 0;
						
                        foreach($GLOBALS['supplier_amount'] as $sup=>$val)
                        {     						
							$total_deposite += array_sum($val);
                        }
						
						
                }
				else
				{
					$noooooo =  "<div class='no_recoreds'>No supplier found.</div>";
				}                    
			}
			
			
            if(isset($_POST['create_period']))
            {
				ini_set('max_execution_time', 0);
                $start_date = date("Y-m-d",strtotime(str_replace("/","-",$_POST['start_date'])));
                $close_date = date("Y-m-d",strtotime(str_replace("/","-",$_POST['close_date'])));
                $payment_date = date("Y-m-d",strtotime(str_replace("/","-",$_POST['payment_date'])));
                							
											
				
				$close_date_year = date("Y",strtotime($close_date));
				$accounting_year_settt = $close_date_year;
				$account_yeasrsss = array_column($accounting_years, 'year');
				
				//while( in_array($close_date_year, $account_yeasrsss))
				foreach($account_yeasrsss as $close_date_year)
				{									
					$get_journal_data = cl_get_request('https://restapi.e-conomic.com/accounts/'.$deposite_journal.'/accounting-years/'.$close_date_year.'/entries?filter=date$lte:'.$close_date.'&pageSize=100',false,array(),"journal_detail_extract_request");					
					
					//$close_date_year =  $close_date_year-1;
				}
				
                if(!empty($GLOBALS['supplier_amount']))
                {                    
					echo "<style>
					table.sss,table.sss td, table.sss th {  
					  border: 1px solid #ddd;
					  text-align: left;
					}
					
					table.sss {
					  border-collapse: collapse;
					  width: 100%;
					}
					
					table.sss th,table.sss  td {
					  padding: 15px;
					}
					.search_res_container
					{
						height:500px;
						overflow-y:scroll;	
					}	
        			</style>";
                    echo "<div class='search_res_container'><table class='sss'>
                            <tr>
                                <th>Supplier Name</th>
								 <th>Bank Account Number</th>
                                <th>Supplier Number</th>
                                <th>Total Deposite</th>
                                <th>Total Gain</th>
                            </tr>
                        ";
						
                        $total_deposite = 0;
						$total_gian = 0;

						/* $supplier_name = wp_remote_get( "https://restapi.e-conomic.com/suppliers/1005" ,  array(
							'redirection' => 10,
				    		'httpversion' => '1.0',
				    		'blocking'    => true,
					    	'sslverify'   => false,	
							'timeout' => 30000,
                            'headers' => array(
                                        'X-AppSecretToken' => isset($get_clj_settings['secret_token']) ? $get_clj_settings['secret_token']:"",
                                        'X-AgreementGrantToken'=> isset($get_clj_settings['grant_token']) ? $get_clj_settings['grant_token']:"",
                                        'Content-Type'=>"application/json"
                                       ) 
                            ));
							$res_code = wp_remote_retrieve_response_code($supplier_name);
							
							if($res_code == 200){                           
								 $dataa = json_decode($supplier_name['body'],true);
								$supplier_name_show = $dataa['name'];
								$supplier_account_numebr_get  = "XX";
								$paymentTypeNumber = "";
								
								if(isset($dataa['remittanceAdvice']['creditorId']))
								{
									$supplier_account_numebr_get = $dataa['remittanceAdvice']['creditorId'];									
								}
								
								if(isset($dataa['remittanceAdvice']['paymentType']['paymentTypeNumber']))
								{
									$paymentTypeNumber = $dataa['remittanceAdvice']['paymentType']['paymentTypeNumber'];
								} 
						 }else{
								$supplier_name_show = " ";
								$supplier_account_numebr_get  = "XX";
								$paymentTypeNumber = "";
						 }
						echo $supplier_name_show;
						echo '<br>';
						echo $supplier_account_numebr_get;
						echo '<br>';
						echo $paymentTypeNumber;
						echo '<br>';
						echo '<pre>';
						print_r($dataa);
						echo '</pre>';
						echo '<br>';
						exit; */
                        foreach($GLOBALS['supplier_amount'] as $sup=>$val){
                            
                            $supplier_name = wp_remote_get( "https://restapi.e-conomic.com/suppliers/".$sup ,  array(
							
							'redirection' => 10,
				    		'httpversion' => '1.0',
				    		'blocking'    => true,
					    	'sslverify'   => false,	
							'timeout' => 3000000000000000000,
                            'headers' => array(
								'X-AppSecretToken' => isset($get_clj_settings['secret_token']) ? $get_clj_settings['secret_token']:"",
								'X-AgreementGrantToken'=> isset($get_clj_settings['grant_token']) ? $get_clj_settings['grant_token']:"",
								'Content-Type'=>"application/json"
							   ) 
                            ));
						 $res_code = wp_remote_retrieve_response_code($supplier_name);
						 $dataa = json_decode($supplier_name['body'],true);
						 if($res_code == 200){                           
								 $dataa = json_decode($supplier_name['body'],true);
								$supplier_name_show = $dataa['name'];
								$supplier_account_numebr_get  = "XX";
								$paymentTypeNumber = "";
								
								if(isset($dataa['remittanceAdvice']['creditorId']))
								{
									$supplier_account_numebr_get = $dataa['remittanceAdvice']['creditorId'];									
								}
								
								if(isset($dataa['remittanceAdvice']['paymentType']['paymentTypeNumber']))
								{
									$paymentTypeNumber = $dataa['remittanceAdvice']['paymentType']['paymentTypeNumber'];
								} 
						 }else{
								$supplier_name_show = " ";
								$supplier_account_numebr_get  = "XX";
								$paymentTypeNumber = "";
						 } 
						 
						 $supplier_name_show = "afsadfasf";
						 $supplier_account_numebr_get  = "XX";
						 $paymentTypeNumber = "asdfasdfasf";
						 
						 ?>
                        
                        <tr>
                            <td>
                                <?php
                                    echo $supplier_name_show;
                                ?>
                            </td>
                            <td>
                                <?php
                                    echo $supplier_account_numebr_get;
                                ?>
                            </td>
                            
                            <td>
                                <?php echo $sup;?>
                             </td>
                             <td>   
                                <?php
									$deposited_value = array_sum($val);
									echo $deposited_value;
									
								$total_deposite += array_sum($val);
								?>
                             </td>
                            <td> 
                                <?php								
								 	$gain_value =  $_POST['gain_percentage']*(array_sum($val)/100);
									echo $gain_value;
									
								$total_gian += $_POST['gain_percentage']*(array_sum($val)/100);
								
								$_SESSION['suppliers_array'][] = array("sid"=>$sup,"sname"=>$supplier_name_show,"deposite_total_value"=>$deposited_value,"gain_total_value"=>$gain_value,"supplier_account_numebr_get"=>$supplier_account_numebr_get,"paymentTypeNumber"=>$paymentTypeNumber);
								
								
								?>
                            </td>
                        </tr>
                        <?php
                            
                        }
						
                    echo "</table></div>";
					//exit;
						$_SESSION['store_to_db_array']["start_date"] = $start_date;
						$_SESSION['store_to_db_array']["close_date"] = $close_date;
						$_SESSION['store_to_db_array']["payment_date"] = $payment_date;
						$_SESSION['store_to_db_array']["period_name"] = $_POST['gain_period_name'];
						$_SESSION['store_to_db_array']["accounting_year"] = $accounting_year_settt;
						$_SESSION['store_to_db_array']["gain_percentage"] = $_POST['gain_percentage'];
						$_SESSION['store_to_db_array']["total_gian"] = $total_gian;
						$_SESSION['store_to_db_array']["total_deposite"] = $total_deposite;
						$_SESSION['store_to_db_array']['total_members'] = count($GLOBALS['supplier_amount']);
					?>
                    
						<div class="total_deposite_table">Total deposite for the period: <?php echo $total_deposite;?></div>
						<div class="total_gain_table">Total gain for the period: <?php echo $total_gian;?></div>
                        <div class="jactions">
                        
                        	<div class="cl_cancel_link">
                            	<a href="<?php echo admin_url("admin.php?page=add-new-period");?>" class="button button-primary">
                                	Cancel
                                </a>
                            </div>
                            
                            <div class="cl_submit_link">
                            	<a href="<?php echo admin_url("admin.php?page=add-new-period&act=complete_request");?>" class="button button-primary">
                                	Complete
                                </a>
                            </div>
                            
                        </div>
                        
                    <?php
                }
				else
				{
					print "<div class='no_recoreds'>No supplier found.</div>";
				}
        
            }
            else
            {
			if(!empty($up_msggg))	
			{
				print "<div class='no_recoreds'>".$up_msggg."</div>";	
			}
			if(!empty($noooooo))	
			{				
				print $noooooo;									
			}				
            ?>            
            <form method="post">
                <div class="pre_calculation_for_date">
						<div class="inner_1">Get total deposit per date</div>                        
                        <div class="inner_1"><input type="text" name="entered_closed_date" value="" required="required"></div>
                        <div class="inner_1"><input type="submit" name="get_per_date" value="Get" class="button-primary" required></div>        <?php
			if(!empty($total_deposite))
			{
			?>                        
                        <div class-"innere_1">
	                        Total Deposit
                        </div>
                        
                        <div class="inner_1">
	                        <?php echo number_format($total_deposite,0,",",".");?>
                        </div>
                        
			<?php
            }
            ?>
				</div>
            </form>    
            
                        <form method="post" id="create_record">
                        <div class="add_data_container">
                                <table class="form-table">
                                    <tbody>
                                        <tr>
                                            <th scope="row">
                                                Gaind period name
                                            </th>
                                            <td>
                                            <div class="clrow">
                                                <div class="col-6">
                                                    <input type="text" name="gain_period_name" value="" placeholder="Enter Gaind period name" required="required">
                                                </div>
                                            </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">
                                                Start date
                                            </th>
                                            <td>
                                            <div class="clrow">
                                                <div class="col-6">
                                                    <input type="text" name="start_date" value="" placeholder="Enter Start date" required="required">
                                                </div>
                                            </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">
                                                Closing date
                                            </th>
                                            <td>
                                            <div class="clrow">
                                                <div class="col-6">
                                                    <input type="text" name="close_date" value="" placeholder="Enter Closing date" required="required">
                                                </div>
                                            </div>
                                            </td>
                                        </tr>
                                        <tr style="display:none;">
                                            <th scope="row">
                                                Accounting year
                                            </th>
                                            <td>
                                            <div class="clrow">
                                                <div class="col-6">
                                                <select name="accounting_year1211">
                                                <option value=""> === Select year === </option>
                                                <?php
                                                if(!empty($accounting_years))
                                                {
                                                    foreach($accounting_years as $yr)	
                                                    {
                                                        ?>
                                                            <option value="<?php echo $yr['year'];?>"><?php echo $yr['year'];?></option>
                                                        <?php
                                                    }	
                                                }
                                                ?>
                                                </select>
                                                    
                                                </div>
                                            </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">
                                                Gain percentage
                                            </th>
                                            <td>
                                            <div class="clrow">
                                                <div class="col-6">
                                                    <input type="number" name="gain_percentage" value="" placeholder="Enter Gain percentage" required="required" step="0.01">
                                                </div>
                                            </div>
                                            </td>
                                        </tr>	
                                        <tr>
                                            <th scope="row">
                                                Payment date
                                            </th>
                                            <td>
                                            <div class="clrow">
                                                <div class="col-6">
                                                    <input type="text" name="payment_date" value="" placeholder="Enter Payment date" required="required">
                                                </div>
                                            </div>
                                            </td>
                                        </tr>
                                        <tr>
                                        <th>
                                        
                                        </th>
                                            <td>
                                            <div class="clrow ">
                                                <div class="col-6">
                                                <input type="submit" name="create_period" value="Create Period" class="cl_right_btn button button-primary">
                                                  </div>
                                            </div>
                                            </td>
                                        </tr>		
                                    </tbody>	
                                </table>
                             </div>   
                        </form>                        
                    <script>
                        jQuery(document).ready(function($) {
                                $('#create_record').parsley();
                                $( "input[name='start_date']" ).datepicker({"dateFormat":"dd/mm/yy"});
								$( "input[name='entered_closed_date']" ).datepicker({"dateFormat":"dd/mm/yy"});
                                $( "input[name='close_date']" ).datepicker({"dateFormat":"dd/mm/yy"});
                                $( "input[name='payment_date']" ).datepicker({"dateFormat":"dd/mm/yy"});
                        });
                    </script>
             <?php            
            
			}
?>
	</div>
<?php
}

function cl_update_gain_journal()
{
	ini_set('max_execution_time', 0);	
	$get_clj_settings = get_option("clj_settings");
	$gain_journal = $get_clj_settings['gain_journal'];
	$gain_journal_journalNumber = $get_clj_settings['gain_journal_journalNumber'];

	$contra_account_number = $get_clj_settings['contra_deposite'];
	$departmental_destribution = $get_clj_settings['departmental_distribution'];
	
	
			$supplier_invoice_array = array();
			
			foreach($_SESSION['suppliers_array'] as $sup)
			{
				if($sup['supplier_account_numebr_get'] == "XX" || empty($sup['paymentTypeNumber']))
				{
					continue;	
				}
								
				else if($sup['paymentTypeNumber'] == 10)
				{
						$account_key = "ibanSwift";
				}
				else
				{
						$account_key = "accountNo";
				}
				
				$posting_amount = $sup['gain_total_value'] < 0 ? $sup['gain_total_value'] : (-1*$sup['gain_total_value']);
				
				
					$supplier_invoice_array[] = array(
						"supplier"=>array("supplierNumber"=>$sup['sid'],"self"=>"https://restapi.e-conomic.com/suppliers/".$sup['sid']),

						"amount"=>round($posting_amount,2),

						'text'=>$_SESSION['store_to_db_array']["period_name"],

						"contraAccount"=>array("accountNumber"=>(int)$contra_account_number,"self"=>"https://restapi.e-conomic.com/accounts/".$contra_account_number),

						"currency"=>array("code"=>"DKK","self"=>"https://restapi.e-conomic.com/currencies/DKK"),

						// "departmentalDistribution"=>array("departmentalDistributionNumber"=>$departmental_destribution),

						"paymentDetails"=>array($account_key=>$sup['supplier_account_numebr_get'],

						"message"=>$_SESSION['store_to_db_array']["period_name"],

						"paymentType"=>array("paymentTypeNumber"=>(int)$sup['paymentTypeNumber'])),

						"dueDate"=>$_SESSION['store_to_db_array']["payment_date"],

						"date"=>$_SESSION['store_to_db_array']["close_date"],

						"type"=>"supplierInvoice"
					);
					
			}

			
$supplierInvoices = wp_remote_post( "https://restapi.e-conomic.com/journals-experimental/".$gain_journal."/vouchers", array(
	'method' => 'POST',
	'timeout' => 450,
	'redirection' => 5,
	'httpversion' => '1.0',
	'blocking' => true,
   	'sslverify'   => false,				
	'headers' => array(
		'X-AppSecretToken' => isset($get_clj_settings['secret_token']) ? $get_clj_settings['secret_token']:"",
        'X-AgreementGrantToken'=> isset($get_clj_settings['grant_token']) ? $get_clj_settings['grant_token']:"",
		'Content-Type'=>"application/json"
								
	),
	'body' => '{
        "accountingYear": {
            "year": "'.$_SESSION['store_to_db_array']["accounting_year"].'"
        },
        "journal": {
            "journalNumber": '.$gain_journal_journalNumber.',
            "self": "https://restapi.e-conomic.com/journals-experimental/'.$gain_journal_journalNumber.'"
        },
        "entries": {
            "supplierInvoices": '.json_encode($supplier_invoice_array).'
		}
    }',
	'cookies' => array()
    )
);

/*
	echo "https://restapi.e-conomic.com/journals-experimental/".$gain_journal."/vouchers"; echo "<br><br><br>";
	echo '{
        "accountingYear": {
            "year": "'.$_SESSION['store_to_db_array']["accounting_year"].'"
        },
        "journal": {
            "journalNumber": '.$gain_journal.',
            "self": "https://restapi.e-conomic.com/journals-experimental/'.$gain_journal.'"
        },
        "entries": {
            "supplierInvoices": '.json_encode($supplier_invoice_array).'
		}
    }'; exit;
*/
	
$supplierInvoices_status = wp_remote_retrieve_response_code($supplierInvoices);

	if($supplierInvoices_status == 201)
	{
		return true;
	}
	
	return false;
}

function clj_all_records()
{
	session_start();
	wp_enqueue_style("admin-journal");
	wp_enqueue_script("tinymceee");
	
	global $wpdb;
	$clr_jdata = $wpdb->prefix."clr_jdata";	
	$del_msg = "";
	$get_clj_settings = get_option("clj_settings");
	$multipy_currency = $get_clj_settings['currency_val'];
	
	
	if(isset($_POST['send_email_to_users']) && filter_input(INPUT_POST,'send_email_id', FILTER_VALIDATE_INT))
	{
		
		
		$email_subject = $get_clj_settings['send_report_subject'];
		$email_from_name = $get_clj_settings['send_report_from_name'];
		$email_from_email = $get_clj_settings['send_report_from_email'];
		$email_message = $_POST['send_email_body'];
		
		$get_rowww = $wpdb->get_row("SELECT * from $clr_jdata where clr_id  = ".$_POST['send_email_id'],ARRAY_A);
		
		$email_message = str_replace("%%gain_text%%",$get_rowww['clr_gain_text'],$email_message);
		$email_message = str_replace("%%start_date%%",$get_rowww['clr_start_date'],$email_message);
		$email_message = str_replace("%%close_date%%",$get_rowww['clr_close_date'],$email_message);
		$email_message = str_replace("%%accounting_year%%",$get_rowww['clr_accounting_year'],$email_message);
		$email_message = str_replace("%%total_members%%",$get_rowww['clr_total_members'],$email_message);
		$email_message = str_replace("%%total_deposite%%",$get_rowww['clr_total_deposite']*$multipy_currency,$email_message);
		$email_message = str_replace("%%gain_percentage%%",$get_rowww['clr_gain_percentage'],$email_message);
		$email_message = str_replace("%%total_gain%%",$get_rowww['clr_total_gain']*$multipy_currency,$email_message);
		$email_message = str_replace("%%payment_date%%",$get_rowww['clr_payment_date'],$email_message);
						
				
		$headers[] = 'From: '.$email_from_name.' <'.$email_from_email.'>';		
		$attachmentss = array();
		//$attachmentss[] = __DIR__."/pdfsss/".$get_rowww['clr_report_file'];
		
		
			$blogusers = get_users(array('meta_key' => 'monthly_mail',
			'meta_value' => '1',
			'meta_compare' => '='));
			if($blogusers)
			{				
				foreach($blogusers as $usr)
				{
					$user_first_name = $usr->display_name;
					$email_message = str_replace("%%user_name%%",$user_first_name,$email_message);					
					wp_mail($usr->user_email,$email_subject,$email_message,$headers,$attachmentss);
					//wp_mail("tset.cloudweblabs@gmail.com",$email_subject,$email_message,$headers,$attachmentss);
				}	
			}
					
		$send_email = "Email sent to all users";
	}
			
	if(isset($_POST['gen_pdf']) && filter_input(INPUT_POST,'record_id', FILTER_VALIDATE_INT))
	{
		
		$get_reeeee = $wpdb->get_row("Select * from $clr_jdata where clr_id = ".$_POST['record_id'],ARRAY_A);

		$msggg = str_replace("%%gain_text%%",$get_reeeee["clr_gain_text"],$_POST['generate_pdf']);
		$msggg = str_replace("%%start_date%%",$get_reeeee["clr_start_date"],$msggg);
		$msggg = str_replace("%%close_date%%",$get_reeeee["clr_close_date"],$msggg);
		$msggg = str_replace("%%accounting_year%%",$get_reeeee["clr_accounting_year"],$msggg);
		$msggg = str_replace("%%total_members%%",$get_reeeee["clr_total_members"],$msggg);
		$msggg = str_replace("%%total_deposite%%", $get_reeeee["clr_total_deposite"]*$multipy_currency,$msggg);
		$msggg = str_replace("%%gain_percentage%%",$get_reeeee["clr_gain_percentage"],$msggg);
		$msggg = str_replace("%%total_gain%%",$get_reeeee["clr_total_gain"]*$multipy_currency,$msggg);
		$msggg = str_replace("%%payment_date%%",$get_reeeee["clr_payment_date"],$msggg);			

echo $htmll = "<style>
table, td, th {  
  border: 1px solid #ddd;
  text-align: left;
}

table {
  border-collapse: collapse;
  width: 100%;
}

th, td {
  padding: 10px;
  width:50%;
}
.msggg
{
	font-size: 24px;
	font-weight: bold;
	text-align: center;
	float: left;
	width: 100%;
	margin: 15px 0;
}	
</style>";
/*<div class="msggg">Period details</div>
		<table>	
				<tr>
					<td>
						Start date
					</td>
					<td>
						{$clr_start_date}
					</td>
					
				</tr>
				<tr>
					<td>
						Closing date
					</td>
					<td>
						{$clr_close_date}
					</td>
					
				</tr><tr>
					<td>
						Total Members
					</td>
					<td>
						{$clr_total_members}
					</td>
					
				</tr><tr>
					<td>
						Total Invested Value
					</td>
					<td>
						{$c1}
					</td>
					
				</tr><tr>
					<td>
						Gain Percentage
					</td>
					<td>
						{$clr_gain_percentage}
					</td>
					
				</tr><tr>
					<td>
						Total Gain Amount
					</td>
					<td>
						{$c2}
					</td>
					
				</tr><tr>
					<td>
						Payment Date
					</td>
					<td>
						{$clr_payment_date}
					</td>
					
				</tr>
		</table>*/
$htmll .= <<<PPP
{$msggg}
PPP;

$file_path = __DIR__."/pdfsss/";
$file_name = rand(0,1000000);
$file_extension = ".pdf";
	while(file_exists($file_path.$file_name.$file_extension))
	{
		$i++;
		$file_name = rand(0,1000000);
	}
$full_file_name =  $file_name.$file_extension;

			require_once __DIR__ . '/vendor/autoload.php';
			$mpdf = new \Mpdf\Mpdf();
			$mpdf->WriteHTML($htmll);
			$mpdf->Output($file_path.$full_file_name,'F');

			
			$wpdb->update($clr_jdata,array("clr_report_file"=>$full_file_name),array("clr_id"=>$_POST['record_id']));
			$_SESSION["up_msg"] = "Pdf saved.";
			wp_redirect(admin_url("admin.php?page=clj-all-records")); die();
	}	
	
	if(isset($_GET['action']) &&  $_GET['action'] == "del" && filter_input(INPUT_GET,'id', FILTER_VALIDATE_INT))
	{
		$wpdb->delete($clr_jdata,array("clr_id"=>$_GET['id']));
		$del_msg = "Record deleted successfully.";
	}
	
	if(isset($_GET['action']) &&  $_GET['action'] == "delete_pdf" && filter_input(INPUT_GET,'id', FILTER_VALIDATE_INT))
	{
		$get_row = $wpdb->get_row("Select * from $clr_jdata where clr_id = ".$_GET['id'],ARRAY_A);			
		$old_file= $get_row['clr_report_file'];
		unlink(__DIR__."/pdfsss/".$old_file);
		
		$wpdb->update($clr_jdata,array("clr_report_file"=>""),array("clr_id"=>$_GET['id']));
		$del_msg = "Pdf deleted successfully.";
	}

	$all_data = $wpdb->get_results("select * from $clr_jdata order by clr_id desc", ARRAY_A);	
	$total_rows  = $wpdb->num_rows;
	$get_clj_settings = get_option("clj_settings");
	$convert_rate = $get_clj_settings['currency_val'];
	?>
  
<style>
.cl_admin_popup_container
{
	position:fixed;
	height:100%;
	width:100%;
	left:0;
	right:0;
	bottom:0;
	top:0;
	background:rgba(0,0,0,0.5);
	z-index:-1;
		
	opacity:0;	
	transition:all 0.5s linear;
}
.cl_admin_popup_content
{
	background:#fff;
	margin:0 auto;
	width:900px;
	max-width:80%;
	position:fixed;
	top:0%;
	left:0%;
	transform:translate(0%,0%) scale(0.5);
	transform-origin:right;
	border-radius:10px;
	box-sizing:border-box;
	box-shadow:1px 1px 20px rgba(0,0,0,0.5);
	
	opacity:0;	
	transition:all 0.5s 0.1s linear;
}

.cl_admin_popup_header,.cl_admin_popup_body,.cl_admin_popup_footer
{
	padding:15px 20px;
}
.cl_admin_popup_body {
    max-height: 60vh;
    overflow-y: auto;
}

body.open_popup
{
	overflow:hidden;
}
body.open_popup .cl_admin_popup_container.show_popup_container
{	
	z-index:500000;
	opacity:1;
}
body.open_popup .cl_admin_popup_container.show_popup_container .cl_admin_popup_content
{
	opacity:1;
	top:50%;	
	left:50%;
	transform: translate(-50%,-50%) scale(1);
}


body.close_popup .cl_admin_popup_container.show_popup_container
{	
	transition:all 0.5s 0.5s linear;
	z-index:-1;
	opacity:0;
}
body.close_popup .cl_admin_popup_container.show_popup_container .cl_admin_popup_content
{
	transition:all 0.5s linear;
	opacity:0;
	top:0;	
	left:0;
	transform: translate(0%,0%) scale(0.5);
}
.cl_admin_popup_header
{
	display:flex;
	justify-content: space-between;
	border-bottom:1px solid #CCC;
}
.cl_admin_popup_header h3
{
	margin:0;
	padding:0;
	
}	
.cl_admin_popup_header .close_popupp
{
	cursor:pointer;
	font-size:18px;
}	
.cl_admin_popup_footer
{
	border-top:1px solid #CCC;	
	display:flex;
	justify-content: space-between;
}
</style>


<form method="post">
    <div class="cl_admin_popup_container" id="pop1">
    	<div class="cl_admin_popup_content">
        
        	<div class="cl_admin_popup_header">
                <h3>Generate Pdf</h3>
                <a class="close_popupp" data-destination="#pop1">&times;</a>
            </div>
            
            <div class="cl_admin_popup_body">
            	Use below Keywords in pdf body:<br> %%gain_text%%<br>%%start_date%%<br>%%close_date%%<br>%%accounting_year%%<br>%%total_members%%<br>%%total_deposite%%<br>%%gain_percentage%%<br>%%total_gain%%<br>%%payment_date%%<br><br>
                <textarea id="generate_pdf" name="generate_pdf"></textarea>
            </div>
            
            <div class="cl_admin_popup_footer">
            	<button type="submit" name="gen_pdf" class="button button-primary">
                	Generate Pdf
                </button>
            	<a class="close_popupp button button-primary" data-destination="#pop1">
                	Close
                </a>
                <input type="hidden" name="record_id" id="record_id" value="">
            </div>
            
        </div>
    </div>
</form>  


<form method="post">
    <div class="cl_admin_popup_container" id="pop2">
    	<div class="cl_admin_popup_content">
        
        	<div class="cl_admin_popup_header">
                <h3>Send Email</h3>
                <a class="close_popupp" data-destination="#pop2">&times;</a>
            </div>
            
            <div class="cl_admin_popup_body">
            	Use below Keywords in pdf body:<br> %%user_name%%<br>%%gain_text%%<br>%%start_date%%<br>%%close_date%%<br>%%accounting_year%%<br>%%total_members%%<br>%%total_deposite%%<br>%%gain_percentage%%<br>%%total_gain%%<br>%%payment_date%%<br><br>
                <textarea id="send_email_body" name="send_email_body"><?php echo $get_clj_settings['send_report_body'];?></textarea>
            </div>
            
            <div class="cl_admin_popup_footer">
            	<button type="submit" name="send_email_to_users" class="button button-primary">
                	Send Email
                </button>
            	<a class="close_popupp button button-primary" data-destination="#pop2">
                	Close
                </a>
                <input type="hidden" name="send_email_id" id="send_email_id" value="">
            </div>
            
        </div>
    </div>
</form>  

 
    <script>
	jQuery(document).on("click",".show_popup", function(e){
		
		var dest = jQuery(this).data("destination");
		jQuery(dest).addClass("show_popup_container");
		
			jQuery("body").addClass("open_popup");
			jQuery("body").removeClass("close_popup");
	});
		
	jQuery(document).on("click",".close_popupp", function(e){
		
		var dest = jQuery(this).data("destination");
		jQuery(dest).removeClass("show_popup_container");
				
			jQuery("body").addClass("close_popup");
			jQuery("body").removeClass("open_popup");
	});
		
	jQuery(document).click(function(event) {
		$target = jQuery(event.target);
		if(!$target.closest('.cl_admin_popup_content').length && !$target.closest('.show_popup').length && jQuery('.cl_admin_popup_container').hasClass("show_popup_container"))
		{
			jQuery(".cl_admin_popup_container").removeClass("show_popup_container");
			jQuery("body").addClass("close_popup");
			jQuery("body").removeClass("open_popup");
		}
	});
	
	jQuery(document).ready(function() {
			tinyMCE.init({
				mode : "none",
				width: "100%",
				height: "300",
			});
			tinyMCE.execCommand('mceAddEditor', false, 'generate_pdf');
			tinyMCE.execCommand('mceAddEditor', false, 'send_email_body');
			
			jQuery(".generate_pdf_open").on("click", function(){
					jQuery("#record_id").val((jQuery(this).data("id")));
				});
			jQuery(".send_email_open").on("click", function(){
					jQuery("#send_email_id").val((jQuery(this).data("id")));
				});	
	});	
	</script>
        
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
    <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <div class="wrap">
    		<h1>
            	All Records
            </h1>
            
     <?php
	 	if(!empty($del_msg))
		{			
            print "<div class='no_recoreds'>".$del_msg."</div>";            
		}	
		if(isset($_SESSION['up_msg']))
		{
			print "<div class='no_recoreds'>".$_SESSION['up_msg']."</div>";   
			unset($_SESSION['up_msg']);
		}
		if(isset($send_email))
		{
			print "<div class='no_recoreds'>".$send_email."</div>";            		
		}
		
	 ?>       
	<table id="myTable" class="display">
    <thead>
        <tr>
            <th>Id</th>
            <th>Start Date</th>
            <th>Closing Date</th>
            <th>Total Members</th>
            <th>Total Invested Value</th>
            <th>Gain Percentage</th>
            <th>Total Gain Amount</th>
            <th>Payment date</th>
            <th>Delete Gain Period</th>
            <th>Send report</th>
        </tr>
    </thead>
    <tbody>
    	
        <?php
		if($total_rows > 0)
		{
			foreach($all_data as $k =>$ddd):
		
			?>
            <tr>
            	<td>
                	<?php echo $k+1;?>
                </td>
                <td>
                	<?php echo $ddd['clr_start_date'];?>
                </td>
                <td>
                	<?php echo $ddd['clr_close_date'];?>
                </td>
                <td>
                	<?php echo $ddd['clr_total_members'];?>
                </td>
                <td>
                	<?php echo $ddd['clr_total_deposite'];?>
                </td>
                <td>
                	<?php echo $ddd['clr_gain_percentage'];?>
                </td>
                <td >
                	<?php echo $ddd['clr_total_gain'];?>
                </td>
                <td >
                	<?php echo $ddd['clr_payment_date'];?>
                </td>
                <td>
                	<a href="<?php echo admin_url("admin.php?page=clj-all-records&action=del&id=".$ddd['clr_id']);?>" class="delete_it">Delete Gain Period</a>
                </td>
                <td>
                
                <a class="send_report show_popup send_email_open" data-id="<?php echo $ddd['clr_id'];?>" data-destination="#pop2" href="javascript:void(0);">Send Report?</a><br>
                
                <?php
					if(!empty($ddd['clr_report_file']))
					{?>
			                <a target="_blank" href="<?php echo plugin_dir_url(__FILE__)."/pdfsss/".$ddd['clr_report_file'];?>" class="send_report">View Pdf</a><br>
                            
                            <a href="<?php echo admin_url("admin.php?page=clj-all-records&action=delete_pdf&id=".$ddd['clr_id']);?>" class="generate_report">Delete Pdf</a>
                    <?php
						
					}	
					else
					{
						?>
                        <a class="generate_report show_popup generate_pdf_open" data-id=<?php echo $ddd['clr_id'];?> data-destination="#pop1" href="javascript:void(0);">Generate PDF?</a>
                        <?php
					}
				?>
                </td>
               
            </tr>
            
            <?php
			endforeach;
		}
		else
		{
			?>
            <tr style="text-align:center;">
            	<td colspan="10">
                	No Records Found.
                </td>
            </tr>
            <?php
				
		}
		  ?>
    </tbody>
</table>
    </div>
    <script>
	jQuery(document).ready( function ($) {
    $('#myTable').DataTable();
} );
	</script>
<?php	
}

function cl_set_email_content_type(){
    return "text/html";
}
add_filter( 'wp_mail_content_type','cl_set_email_content_type' );

function chart_left_cb($atts)
{
	
	if(is_admin())
	{
		return;	
	}
	ob_start();
	$atts = shortcode_atts( array(
		'bg' => 'rgba(0,0,0,0.7)'		
	), $atts );	
	global $rummmm;
	global $supplier_id;

	$get_clj_settings = get_option("clj_settings");
 	$account_number = $get_clj_settings['deposite_journal'];
	$get_all_accounting_years = refine_get_request("accounting-years",array(),"exract_accounting_years_values");
	$only_years = array_column($get_all_accounting_years,"year");	
	rsort($only_years);
	
	
	foreach($only_years as $year)
	{
		$get_entries = refine_get_request("accounts/".$account_number."/accounting-years/".$year."/entries",array(),"particular_supplier_data");
	}
	
		global $wpdb;
		$clr_jdata = $wpdb->prefix."clr_jdata";	
		$supplier_id = get_user_meta(get_current_user_id(),"user_supplier_id",true);

		if($supplier_id === false || $supplier_id == '')
		{
					return "No data Found";
		}
		
		
	if(!empty($rummmm))
	{

		$refined_array = array_filter($rummmm, function ($var) {
			global $supplier_id;			
			return ($var['sid'] == $supplier_id);
	});
	

	usort($refined_array, 'date_compare');
	//print_r($refined_array); exit;
	$amount_array  = array_column($refined_array,"amount");
	
	$all_time_profit = array_sum($amount_array);
	
	$htmllll = "";
			
			foreach($refined_array as $dd)
			{
				$amount_sum = array_sum($amount_array);
				array_shift($amount_array);
				
				$pdf_seelct = $wpdb->get_row("SELECT * From $clr_jdata where clr_close_date = '".$dd['date']."' and clr_gain_text = '".$dd['text']."'",ARRAY_A);
				
				$pdffff = (is_file(__DIR__.'/pdfsss/'.$pdf_seelct['clr_report_file'])) ? '<a href="'.plugin_dir_url(__FILE__).'/pdfsss/'.$pdf_seelct['clr_report_file'].'" target="_blank"><img width=10 src="'.plugin_dir_url(__FILE__).'/img/pdf_img.png"></a>' : '';
				


				$amount_format_value = number_format($dd['amount'],2,",",".");
				$account_format_value = number_format($amount_sum,2,",",".");


				if(strlen($amount_format_value) > 13)
				{
					$amount_format_value = substr($amount_format_value , 0,13)."-";
				}
				if(strlen($account_format_value) > 13)
				{
					$account_format_value = substr($account_format_value , 0,13)."-";
				}


				$htmllll .= "<div class='clr_row_container'>
								
								<div class='clr_row'>".$dd['date']."</div>
								
								<div class='clr_row'>".$dd['text']."</div>
								
								<div class='clr_row' style='text-align:right;'>".$amount_format_value."</div>
								
								<div class='clr_row' style='text-align:right;'>".$account_format_value."<span style='margin-left:5px;'>".$pdffff."</span></div>
								
								<div class='clr_row' style='display:none;'>".$pdffff."</div>
							</div>";
			}
			
		
		$sum_of_gain = $wpdb->get_row(
			"SELECT  sum(clr_gain_percentage) as total_gain_percentage from $clr_jdata order by clr_id desc LIMIT 0 , 12"
		, ARRAY_A);

		$total_gain_percentage = 0;
		$total_gain_percentage = $sum_of_gain["total_gain_percentage"];

		echo "<div class='clr_left_container'>";
		echo "<div class='clr_left_title'>Gain Percent Last 12 Months  <span>{$total_gain_percentage}</span></div>";
		echo "<div class='clr_table_container'>";
		echo "<div class='clr_scrollling'>
		
			 		<div class='clr_row_title_container'>
							<div class='clr_row_title'>Date</div>
							<div class='clr_row_title'>Post</div>
							<div class='clr_row_title'>Amount</div>
							<div class='clr_row_title'>Account</div>
							<div class='clr_row_title' style='display:none;'>Report</div>
					</div>
					
					$htmllll
					
			  </div>
			  </div>";
		echo "</div>";
		
		$euro_currency_rate = $get_clj_settings['currency_val'];
		$show_all_time_profit = number_format(($all_time_profit/$euro_currency_rate),"0",".",".");
		
		
		$this_year_array = array_filter($refined_array, function ($var) {
    					return ( date('Y', strtotime($var['date'])) == date("Y"));
		});
		$current_yaear_amout_array = array_column($this_year_array,"amount");
		$current_yaear_amout = array_sum($current_yaear_amout_array);
		$show_current_yaear_amout = number_format(($current_yaear_amout/$euro_currency_rate),"0",".",".");
		
		$last_entry = number_format(($refined_array[0]['amount']/$euro_currency_rate),"0",".",".");
	}
	else
	{?>
		<div class="clr_no_res_fount">
            	No result found.
        </div>
	<?php } ?>
    
    <script>
		jQuery(document).ready(function(){
				jQuery(".all_time_profit_show p").html('<?php echo $show_all_time_profit;?>');
				jQuery(".year_to_date_show p").html('<?php echo $show_current_yaear_amout;?>');
				jQuery(".last_period_show p").html('<?php echo $last_entry;?>');
			});
	</script>
	<style>
		.clr_left_container
		{
			background:<?php echo $atts['bg'];?>;
		}	
		.clr_scrollling
		{
			max-height:500px;
			overflow-y:auto;
			overflow-x:hidden;						
		}	
		.clr_left_title {
			display: flex;
			justify-content: center;
			color: #fff;
			font-size: 20px;
			padding: 10px;
			background: #e86c00;
			margin-bottom: 3px;
			font-weight:700;
			letter-spacing:0.5px;
			justify-content:space-between;
			align-items:center;
		}
		 
		.clr_row_title_container{
			display: flex;
			flex-wrap: wrap;
			font-size: 15px;
			color: #fff;
			justify-content: space-between;
			
			position: -webkit-sticky; /* Safari */
			position: sticky;
			top: 0;
			z-index:5;
		}
		.clr_row_container
		{
			 display: flex;
			flex-wrap: wrap;
			font-size: 12px;
			color: #fff;
			justify-content: space-between;   
		}
		.clr_row_title,.clr_row
		{
			flex:1;
		}
		.clr_row
		{
			padding:3px 5px;
		}	
		.clr_row_title {
			padding: 10px 5px;
			background-color: #999;
		}
		.clr_row_title {
			border-right: 1px solid black;
			text-align: center;
		}
		.clr_row_title:last-child
		{
			border-right: none;
		}	
	</style>
	<?php
	return ob_get_clean();		
}
add_shortcode("chart_left_panel","chart_left_cb");

function chart_right_cb($atts)
{
	ob_start();
	$atts = shortcode_atts( array(
		'bg' => 'rgba(0,0,0,0.7)'		
	), $atts );
	$id=rand();
	global $wpdb;
		$clr_jdata = $wpdb->prefix."clr_jdata";	
		$all_latest_db_entries = $wpdb->get_results(" SELECT * From (Select * from $clr_jdata order by clr_id DESC LIMIT 0,53) as asssyst order by clr_close_date ASC ",ARRAY_A);
		if(!empty($all_latest_db_entries))
		{
			$close_date_array = array_column($all_latest_db_entries,"clr_close_date");
$gain_data = array_column($all_latest_db_entries,"clr_gain_percentage");//print_r(json_encode($gain_data,JSON_NUMERIC_CHECK )); die();
		}
		else
		{
		}
		?>     
    <style>       
	.chart_container
	{
		background:<?php echo $atts['bg'];?>;
		padding:10px;
	}	
	</style>
    <link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url(__FILE__)."/css/";?>style.css">
	<script src="<?php echo plugin_dir_url(__FILE__)."/js/";?>Chart.min.js"></script>
	<script src="<?php echo plugin_dir_url(__FILE__)."/js/";?>utils.js"></script>
	<script src="<?php echo plugin_dir_url(__FILE__)."/js/";?>analyser.js"></script>
    <canvas class="chart_container" id="<?php echo $id;?>" style="width:100%;height:520px;"></canvas>
<script>
var ctx = document.getElementById('<?php echo $id;?>').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'line',	
    data: {
       xLabels: <?php echo json_encode($close_date_array);?>,
	   		
        datasets: [{			
			radius:2,
			hoverRadius:2,
			borderWidth: 3,
			borderColor:"#e86c00",
			backgroundColor: "#e86c00",			
			fill: false, 
			label: 'Gain Percentage',
            data: <?php echo json_encode($gain_data,JSON_NUMERIC_CHECK);?>,
        }]
    },
   options : {	
    legend: {
		display:false
   },
	   responsive: true,
			maintainAspectRatio: false,
			spanGaps: false,
			elements: {
				line: {
					tension: 0.000001
				}
			},
			plugins: {
				filler: {
					propagate: false
				}
			},
			scales: {
				xAxes: [
				{
					gridLines: 
					{
						display: false,
						zeroLineColor:"#999",
						color:"#999",
					},
					ticks: 
					{                   
						 fontColor: "#fff",  
						 borderColor:"#999",
						 autoSkip: false
                	}
               
            	}
			],				
				yAxes: [
				{
					gridLines: 
					{
                   		 display: false,
						 zeroLineColor:"#999",
						 color:"#999",
                	},
                ticks: {
                    beginAtZero: true,
					fontColor: "#fff",
					borderColor:"#999",
					autoSkip: false,
                    callback: function(value, index, values) {						
                        return value + ' %';
                    }
                }
            }]
			
			}
		}
});
</script>
<?php
	return ob_get_clean();
}	
add_shortcode("chart_right_panel","chart_right_cb");

function all_time_profit_cb()
{
	ob_start();
	$id=rand();
	
	$get_clj_settings = get_option("clj_settings");
	$euro_currency_rate = $get_clj_settings['currency_val'];
	
	global $wpdb;
	$clr_jdata = $wpdb->prefix."clr_jdata";	
			
			$get_gain_query = $wpdb->get_results("select * from $clr_jdata order by clr_id DESC",ARRAY_A);
			$total_gain = array_column($get_gain_query,"clr_total_gain");			
			$total_gain_sum = array_sum($total_gain);
			
			echo "&euro;".number_format(($total_gain_sum/$euro_currency_rate),"0",".",".");
					
	return ob_get_clean();
		
}
//add_shortcode("all_time_profit","all_time_profit_cb");

function all_time_profit_percentage_cb()
{
	ob_start();
	$id=rand();
	global $wpdb;
	$clr_jdata = $wpdb->prefix."clr_jdata";	
			
			$get_gain_query = $wpdb->get_results("select * from $clr_jdata order by clr_id DESC",ARRAY_A);
			
			
			
			$clr_gain_percentage = array_column($get_gain_query,"clr_gain_percentage");			
			$clr_gain_percentage_sum = array_sum($clr_gain_percentage);
			
			echo number_format($clr_gain_percentage_sum,"2",",",",")."%";
					
	return ob_get_clean();
		
}
//add_shortcode("all_time_profit_percentage","all_time_profit_percentage_cb");
function cl_start_code_execute()
{
	ob_start();
}	
add_action("init","cl_start_code_execute");
function year_to_date_cb()
{
	ob_start();	
	$current_year= date("Y");
	
	$get_clj_settings = get_option("clj_settings");
	$euro_currency_rate = $get_clj_settings['currency_val'];
	
	global $wpdb;
	$clr_jdata = $wpdb->prefix."clr_jdata";	
			
			$get_gain_query = $wpdb->get_results("select * from $clr_jdata where  YEAR(clr_close_date) = $current_year",ARRAY_A);
			$total_gain = array_column($get_gain_query,"clr_total_gain");			
			$total_gain_sum = array_sum($total_gain);
			
			echo "&euro;".number_format(($total_gain_sum/$euro_currency_rate),"0",".",".");
					
	return ob_get_clean();
		
}
//add_shortcode("year_to_date","year_to_date_cb");

function year_to_date_percentage_cb()
{
	ob_start();
	$id=rand();
	global $wpdb;
	$current_year= date("Y");
	$clr_jdata = $wpdb->prefix."clr_jdata";	
			
			$get_gain_query = $wpdb->get_results("select * from $clr_jdata where  YEAR(clr_close_date) = $current_year",ARRAY_A);
			
			$clr_gain_percentage = array_column($get_gain_query,"clr_gain_percentage");			
			$clr_gain_percentage_sum = array_sum($clr_gain_percentage);
			
			echo number_format($clr_gain_percentage_sum,"2",",",",")."%";
					
	return ob_get_clean();
		
}
//add_shortcode("year_to_date_percentage","year_to_date_percentage_cb");

function last_period_cb()
{
	ob_start();	
	
	$get_clj_settings = get_option("clj_settings");
	$euro_currency_rate = $get_clj_settings['currency_val'];
	
	global $wpdb;
	$clr_jdata = $wpdb->prefix."clr_jdata";	
			
			$get_gain_query = $wpdb->get_results("select * from $clr_jdata order by clr_id DESC LIMIT 0,1",ARRAY_A);
			$total_gain = array_column($get_gain_query,"clr_total_gain");			
			$total_gain_sum = array_sum($total_gain);
			
			echo "&euro;".number_format(($total_gain_sum/$euro_currency_rate),"0",".",".");
					
	return ob_get_clean();
		
}
//add_shortcode("last_period","last_period_cb");

function last_period_percentage_cb()
{
	ob_start();
	$id=rand();
	global $wpdb;
	$clr_jdata = $wpdb->prefix."clr_jdata";	
			
			$get_gain_query = $wpdb->get_results("select * from $clr_jdata order by clr_id DESC LIMIT 0,1",ARRAY_A);
			
			$total_gain = array_column($get_gain_query,"clr_gain_percentage");			
			$total_gain_sum = array_sum($total_gain);
			
			echo number_format($total_gain_sum,"2",",",",")."%";
					
	return ob_get_clean();
		
}
//add_shortcode("last_period_percentage","last_period_percentage_cb");

function total_invested_to_date_cb()
{
	ob_start();	
	
	$get_clj_settings = get_option("clj_settings");
	$euro_currency_rate = $get_clj_settings['currency_val'];
	
	global $wpdb;
	$clr_jdata = $wpdb->prefix."clr_jdata";	
			
			$get_gain_query = $wpdb->get_results("select * from $clr_jdata order by clr_id DESC LIMIT 0,1",ARRAY_A);
			$clr_total_deposite = array_column($get_gain_query,"clr_total_deposite");
			$clr_total_deposite_sum = array_sum($clr_total_deposite);
			
			echo "&euro;".number_format(($clr_total_deposite_sum/$euro_currency_rate),"0",".",".");
			
	return ob_get_clean();
}
//add_shortcode("total_invested_to_date","total_invested_to_date_cb");

function cl_include_scripts()
{	
	global $paml;
	if(class_exists("PAML_Class"))
	{
		//$oobb = new PAML_Class;
		$plugin_version =  $paml->plugin_version;
	}
	else
	{
		$plugin_version = "1.1.2";
	}
			
	wp_dequeue_script( 'paml-script' );
    wp_deregister_script('paml-script');	
	wp_enqueue_script( 'paml-script', plugin_dir_url(__FILE__) . 'js/modal-login.js', array( 'jquery' ), $plugin_version, true );
	
	switch ( $paml->get( 'login_redirect' ) ) {
			case 'home':
				$login_url = home_url();
				break;
			case 'custom':
				$login_url = filter_var( $paml->get( 'login_redirect_url' ), FILTER_VALIDATE_URL ) ? $paml->get( 'login_redirect_url' ) : $_SERVER['REQUEST_URI'];
				break;
			case 'current':
			default:
				$login_url = $_SERVER['REQUEST_URI'];
				break;
		}
		$registration_url = filter_var( $paml->get( 'registration_redirect_url' ), FILTER_VALIDATE_URL ) ? $paml->get( 'registration_redirect_url' ) : $_SERVER['REQUEST_URI'];
		
	if ( ! is_user_logged_in() ) {
		
			wp_localize_script( 'paml-script', 'modal_login_script', array(
				'ajax'                  => admin_url( 'admin-ajax.php' ),
				'redirecturl'           => $login_url,
				'registration_redirect' => $registration_url,
				'loadingmessage'        => __( 'Checking Credentials...', 'pressapps-modal-login' ),
				'registration_msg' => __('Thank you for registering at youtimizer.com <br/> Your registration has been sent to Youtimizer.com and they will contact you as soon as possible. <br/> Thank you again for your interest! <br/> Youtimizer.com','pressapps-modal-login')
			) );
			// If user is not logged in and google captcha key are assigned, load Google captcha's api
			if($paml->get( 'google_captcha_sitekey' )){
				wp_register_script('google-recaptcha', 'https://www.google.com/recaptcha/api.js');
				wp_enqueue_script('google-recaptcha');
      }
		}

}	
add_action("wp_enqueue_scripts","cl_include_scripts",999);

add_action( 'wp_ajax_nopriv_ajaxlogin_refine', 'paml_ajax_login_refine' ) ;
add_action( 'wp_ajax_ajaxlogin_refine', 'paml_ajax_login_refine' ) ;
function paml_ajax_login_refine()
{
	global $paml;
		// Check our nonce and make sure it's correct.
		if ( is_user_logged_in() ) {
			echo json_encode( array(
				'loggedin' => false,
				'message'  => __( 'You are already logged in', 'pressapps-modal-login' ),
			) );
			die();
		}
		check_ajax_referer( 'ajax-form-nonce', 'security' );

		// Get our form data.
		$data = array();

		// Check that we are submitting the login form
		if ( isset( $_REQUEST['login'] ) ) {

			$data['user_login']    = sanitize_user( $_REQUEST['username'] );
			$data['user_password'] = sanitize_text_field( $_REQUEST['password'] );
			$data['remember']      = ( sanitize_text_field( $_REQUEST['rememberme'] ) == 'TRUE' ) ? true : false;
			$user_login            = wp_signon( $data, is_ssl() );
			
			
			

			// Check the results of our login and provide the needed feedback
			if ( is_wp_error( $user_login ) ) {
				echo json_encode( array(
					'loggedin' => false,
					'message'  => __( 'Wrong Username or Password!', 'pressapps-modal-login' ),
					'redirect_admin' => false
				) );
			} else {
				$redirect_admin = false;
				if(in_array("administrator",$user_login->roles))
				{
						$redirect_admin = admin_url();
				}
				echo json_encode( array(
					'loggedin' => true,
					'message'  => __( 'Login Successful!', 'pressapps-modal-login' ),
					'redirect_admin' => $redirect_admin
				) );
			}
		} 
		die();
}
add_action('after_setup_theme', 'cl_role_setup_callback');
 
function cl_role_setup_callback() {
if (!current_user_can('administrator') && !is_admin()) {
  show_admin_bar(false);
}
}

add_action('wp_logout','auto_redirect_after_logout');
function auto_redirect_after_logout(){
  wp_redirect( home_url() );
  exit();
}

function cl_add_menu_callback($object)
{
    wp_nonce_field(basename(__FILE__), "meta-box-nonce");

    ?>
        <div>
                        

            <label for="meta-box-checkbox">Only logged in user access?</label>
            <?php
                $checkbox_value = get_post_meta($object->ID, "only-logged-in-access", true);

                if($checkbox_value == "")
                {
                    ?>
                        <input name="only-logged-in-access" type="checkbox" value="true">
                    <?php
                }
                else if($checkbox_value == "true")
                {
                    ?>  
                        <input name="only-logged-in-access" type="checkbox" value="true" checked>
                    <?php
                }
            ?>
        </div>
    <?php  
}

function add_custom_meta_box()
{
    add_meta_box("make-restriction-page", "Only logged in user access", "cl_add_menu_callback", "page", "side", "high", null);
}

add_action("add_meta_boxes", "add_custom_meta_box");

function save_custom_meta_box($post_id, $post, $update)
{
    if (!isset($_POST["meta-box-nonce"]) || !wp_verify_nonce($_POST["meta-box-nonce"], basename(__FILE__)))
        return $post_id;

    if(!current_user_can("edit_post", $post_id))
        return $post_id;

    if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
        return $post_id;

    $slug = "page";
    if($slug != $post->post_type)
        return $post_id;

    $meta_box_text_value = "";
    $meta_box_dropdown_value = "";
    $meta_box_checkbox_value = "";

    if(isset($_POST["only-logged-in-access"]))
    {
        $meta_box_text_value = $_POST["only-logged-in-access"];
    }   
    update_post_meta($post_id, "only-logged-in-access", $meta_box_text_value);    
}

add_action("save_post", "save_custom_meta_box", 10, 3);

function cl_redirect_callback()
{
	if(is_page())
	{		
		$access =  get_post_meta(get_the_ID(),"only-logged-in-access",true);
		if($access == true && !is_user_logged_in())
		{
			wp_redirect(home_url()); die();
		}		
	}
}
add_action("template_redirect","cl_redirect_callback");

//Phase second started
function refine_get_request($url_param,$sname_sid=array(),$cb_fn)
{
	$get_clj_settings = get_option("clj_settings");
	
	$get_url = "https://restapi.e-conomic.com/".$url_param;
	$args = array(
    'timeout'     => 15,
    'redirection' => 10,
    'httpversion' => '1.0',
    'headers'     => array(
		'X-AppSecretToken'=> isset($get_clj_settings['secret_token'])?$get_clj_settings['secret_token']:"",
        'X-AgreementGrantToken'=> isset($get_clj_settings['grant_token'])?$get_clj_settings['grant_token']:"",
        'Content-Type' => "application/json"
		),
    'sslverify'   => false,
	);
	
	$next = false;
	
	do{		
			$response = wp_remote_get($get_url,$args);
			$response_code = wp_remote_retrieve_response_code($response);
			$response_body = wp_remote_retrieve_body($response);	
			$response_array = json_decode($response_body,true);
			$collection_response_array = $response_array['collection'];
			$request_response = array_map($cb_fn,$collection_response_array);
			$sname_sid = array_merge($sname_sid,$request_response);
			
			if(isset($response_array['pagination']['nextPage']))
			{
				$get_url = $response_array['pagination']['nextPage'];
				$next = true;
			}
			else
			{
				$next = false;
			}	
	
	}
	while($next === true);
	return $sname_sid;
}

function exract_supplier_values($ar)
{
	$truck = array("supplierNumber"=>$ar['supplierNumber'],"name"=>$ar['name']);
	return $truck;
}
function exract_accounting_years_values($ar)
{
	$truck = array("year"=>$ar['year']);
	return $truck;
}

$rummmm = array();
function particular_supplier_data($ar)
{
	global $rummmm;
		
	$loggedin_user_id = get_current_user_id();
	$supplider_id = get_user_meta($loggedin_user_id,"user_supplier_id",true);
	
	//$rummmm[] = array("text"=>$ar["text"],"date"=>$ar['date'],"amount"=>$ar['amount'],"entryType"=>$ar['entryType']);
	$rummmm[] = array("sid"=>$ar['supplier']["supplierNumber"],"text"=>$ar["text"],"date"=>$ar['date'],"amount"=>$ar['amount']);

}

function particular_supplier_data_api_fn($ar)
{
	global $rummmm;	
		$rummmm[] = array("sid"=>$ar['supplier']["supplierNumber"],"text"=>$ar["text"],"date"=>$ar['date'],"amount"=>$ar['amount']);

}

function date_compare($element1, $element2) { 

    $datetime1 = strtotime($element1['date']); 
    $datetime2 = strtotime($element2['date']); 
	
	if($datetime1 == $datetime2) return 0;
	 
	return $datetime1 < $datetime2 ? 1 : -1 ; 
}  

function custom_user_profile_fields($user){		

		$get_clj_settings = get_option("clj_settings");		
		$user_supplier_id = esc_attr( get_the_author_meta( 'user_supplier_id', $user->ID ) );
		
		$suppliers = refine_get_request("suppliers",array(),"exract_supplier_values");
	
  ?>
    <h3>Select Supplier Account</h3>
    <table class="form-table">
        <tr>
            <th><label for="supplier_id">Supplier Account</label></th>
            <td>
               <select name="supplier_id" id="supplier_id">
               			<option value="">Select Supplier Account</option>
                        <?php
						if(!empty($suppliers))
						{
							foreach($suppliers as $sup)
							{
								?>
									<option value="<?php echo $sup['supplierNumber'];?>" <?php if($user_supplier_id ==$sup['supplierNumber'] ){echo "selected='selected'";}?>><?php echo $sup['name'];?></option>
								<?php
								
							}	
						}
						?>
               </select>
            </td>
        </tr>
    </table>
  <?php
}
add_action( 'show_user_profile', 'custom_user_profile_fields' );
add_action( 'edit_user_profile', 'custom_user_profile_fields' );
add_action( "user_new_form", "custom_user_profile_fields" );

function save_custom_user_profile_fields($user_id){
	if(current_user_can('manage_options') && is_admin() && isset($_POST['supplier_id']))
	{
		update_usermeta($user_id, 'user_supplier_id', $_POST['supplier_id']);
	}
}
add_action('user_register', 'save_custom_user_profile_fields');
add_action('profile_update', 'save_custom_user_profile_fields');

function cl_left_table_show_callback()
{
	ob_start();
	global $rummmm;
	$get_clj_settings = get_option("clj_settings");
 	$account_number = $get_clj_settings['deposite_journal'];
	$get_all_accounting_years = refine_get_request("accounting-years",array(),"exract_accounting_years_values");
	$only_years = array_column($get_all_accounting_years,"year");	
	rsort($only_years);
	foreach($only_years as $year)
	{
		$get_entries = refine_get_request("accounts/".$account_number."/accounting-years/".$year."/entries",array(),"particular_supplier_data");
	}
	
	if(!empty($rummmm))
	{
		?>
        <table>
            <tr>
                <th>
                    Date
                </th>
                <th>
                    Post
                </th>
                <th>
                    Amount
                </th>
                <th>
                    Account
                </th>
            </tr>
        <?php
		usort($rummmm, 'date_compare');
		//echo "<pre>";print_r($rummmm); die();
		
		$amount_array  = array_column($rummmm,"amount");
		
		foreach($rummmm as $dd)
		{			
			$amount_sum = array_sum($amount_array);
			array_shift($amount_array);
			?>
            <tr>            
            	<td>
                	<?php echo $dd['date']; ?>
                </td>
                
                <td>
                	<?php echo $dd['text']; ?>
                </td>
                
                <td>
                	<?php echo $dd['amount']; ?>
                </td>
                                               
                <td>
                	<?php echo $amount_sum; ?>
                </td>
                
            </tr>
            <?php	
		}
		?>
         </table>   
    <?php 
	}	
	else
	{
		
	}	
	
	return ob_get_clean();
}	
//add_shortcode("left_handed_boatshow","cl_left_table_show_callback");

//For changes on chart page estimate will be


/*document.getElementById('file_upload_do').onchange = function(e) {
	jQuery("#remove_file").remove();	
var show_btn = "<a id='remove_file'>Remove?</a>";
jQuery("#sdsdsds").append(show_btn );
}
jQuery(document).on("click","#remove_file",function(){
document.getElementById("file_upload_do").value = "";
jQuery("#remove_file").remove();	
});*/

class Pr_App extends WP_REST_Controller
{
	function pr_reg_routes()
	{
		$version = '1';
    	$namespace = 'chart/v' . $version;
   		$base = 'login';
		
		register_rest_route( $namespace, '/'.$base, array(
    		'methods'=> WP_REST_Server::CREATABLE,
		    'callback' => array($this,'pr_process_login'),
			'args' => array()
 		));
						
		register_rest_route( $namespace, '/send_batting_app', array(
    		'methods'=> WP_REST_Server::CREATABLE,
		    'callback' => array($this,'pr_process_batting'),
			'args' => array()
 		));
		
		
		register_rest_route( $namespace, '/get_app_footer', array(
    		'methods'=> WP_REST_Server::READABLE,
		    'callback' => array($this,'get_app_footer'),
			'args' => array()
 		));
		
		
		register_rest_route(
			$namespace,"/signup",array(
				'methods'=> WP_REST_Server::CREATABLE,
				'callback' => array( $this, 'create_user' ),
				'args' => $this->get_endpoint_args_for_item_schema( true ),
		));
		
		register_rest_route($namespace,"/forgot_password",array(
				'methods' => WP_REST_Server::CREATABLE,
				"callback" => array($this,"forgot_password"),
				"args"=>$this->get_endpoint_args_for_item_schema(true),				
		));
		
		register_rest_route( $namespace, '/get_table_data/(?P<id>[\d]+)', array(
    		'methods'  => WP_REST_Server::READABLE,
		    'callback' => array($this,'get_table_data_cb'),
			'args'     => array(
								'id' => array(
        										'validate_callback' => 'is_numeric'
      										),
								'context'  => array(
												'default' => 'view',
											)
        						)
		 ));
		 
		register_rest_route( $namespace, '/gain_all_years/(?P<id>[\d]+)', array(
    		'methods'  => WP_REST_Server::READABLE,
		    'callback' => array($this,'get_gain_all_years'),
			'args'     => array(
								'id' => array(
        										'validate_callback' => 'is_numeric'
      										),
								'context'  => array(
												'default' => 'view',
											)
        						)
		 ));
		 
		register_rest_route( $namespace, '/user_info/(?P<id>[\d]+)', array(
    		'methods'  => WP_REST_Server::READABLE,
		    'callback' => array($this,'get_user_info'),
			'args'     => array(
								'id' => array(
        										'validate_callback' => 'is_numeric'
      										),
								'context'  => array(
												'default' => 'view',
											)
        						)
		 ));
		 
		register_rest_route( $namespace, '/default_value', array(
    		'methods'  => WP_REST_Server::READABLE,
		    'callback' => array($this,'get_default_value'),
			'args'     => array(
								'context'  => array(
												'default' => 'view',
											)
        						)
 		));
		
		register_rest_route( $namespace, '/chart_data/(?P<id>[\d]+)', array(
    		'methods'  => WP_REST_Server::READABLE,
		    'callback' => array($this,'get_chart_data'),
			'args'     => array(
										'id' => array(
        										'validate_callback' => 'is_numeric'
      										),
								
								'context'  => array(
												'default' => 'view',
											)
        						)
		 ));
		
		register_rest_route( 
			$namespace, '/chart_year_data/', array(
				array( 
					'methods'   => WP_REST_Server::READABLE, 
					'callback'  => array( $this, 'get_chart_year_data'), 
				),
			)  
		);        
		register_rest_route(
			$namespace, '/chart_year_data/(?P<id>[\d]+)/(?P<year>[\w]+)', array(
				'args'   => array(
					'id' => array(
						'validate_callback' => 'interger'
					  ),
					'year' => array(
							'validate_callback' => 'interger'
						),
				),
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback' => array( $this, 'get_chart_year_data' ),                  
					'args'                => array(
						'context' => $this->get_context_param( array( 'default' => 'view' ) ),
					),
				)
			)
		);
		
		register_rest_route( 
			$namespace, '/gain_table_year_data/', array(
				array( 
					'methods'   => WP_REST_Server::READABLE, 
					'callback'  => array( $this, 'get_gain_table_year_data'), 
				),
			)  
		);        
		register_rest_route(
			$namespace, '/gain_table_year_data/(?P<id>[\d]+)/(?P<year>[\w]+)', array(
				'args'   => array(
					'id' => array(
						'validate_callback' => 'interger'
					  ),
					'year' => array(
							'validate_callback' => 'interger'
						),
				),
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback' => array( $this, 'get_gain_table_year_data' ),                  
					'args'                => array(
						'context' => $this->get_context_param( array( 'default' => 'view' ) ),
					),
				)
			)
		);

		register_rest_route( $namespace, '/club_all_years', array(
			'methods'  => WP_REST_Server::READABLE,
			'callback' => array($this,'get_club_all_years_data'),
			'args'     => array(),
		));
		 
	}
	
	public function pr_process_login($request)
	{
		$username = $request->get_param('username');
		$password = $request->get_param('password');
		$auth = wp_authenticate($username,$password);
		if( is_wp_error( $auth ) ) {			
			return new WP_REST_Response(array("code"=>"no_user","msg"=>"Invalid login details.","status"=>404),404);			
		}
		else
		{
			return new WP_REST_Response(array("code"=>"user_found","data"=>array("uid"=>$auth->ID),"status"=>200),200);			
		}	
	}
	
	public function pr_process_batting($request)
	{
			$fname = $request->get_param("fname");
			$email = $request->get_param("email");
			$lname = $request->get_param("lname");
			$text = $request->get_param("text");
			
			$headers[] = 'Content-Type: text/html; charset=UTF-8';
			$headers[] = 'From: Betting Tip from Website <web@youtimizer.com>';
			$headers[] = 'Reply-To: '.$fname.' '.$lname.'. <'.$email.'>';
			
			$email_message ="<html><head><style>table{color:inherit;}</style></head><body><table><tr><td>First Name:</td><td>".$fname."</td></tr><tr><td>Last Name:</td><td>".$lname."</td></tr><tr><td>Email:</td><td>".$email."</td></tr><tr><td>Message:</td><td>".$text."</td></tr></table></body></html>";
			
			$mail = wp_mail("cmachholdt@gmail.com","Tip from member, Youtimizer",$email_message,$headers);
			//$mail = wp_mail("test.cloudweblabs@gmail.com","Tip from member, Youtimizer",$email_message,$headers);
			
			if($mail)
			{
				return new 	WP_REST_Response(array("code"=>"mail_sent","msg"=>"Email Sent Successfully.","status"=>200),200); 
			}	
			else
			{
				return new 	WP_REST_Response(array("code"=>"mail_not_sent","msg"=>"Error while sending email.","status"=>404),404); 
			}
	}		
	function get_app_footer($request)
	{
		return new WP_REST_Response(array("code"=>"address_found","msg"=>"<center>Youtimizer<br>Flat 3, 75 Wigmore St,<br>London W1U 1QB<br>United Kingdom</center>","status"=>200),200);
	}	
	
	public function get_gain_table_year_data($request)
	{
		
		global $supplier_id;
		$uid = $request->get_param('id');
		$param_year = $request->get_param("year");
		
		$user = get_userdata($uid);
		
		if ( $user === false )
		{
			return new WP_REST_Response(array("code"=>"invalid_user","msg"=>"Invalid user id.","status"=>404),404);					
		}
		
		$supplier_id = get_user_meta($uid,"user_supplier_id",true);

		$short_class = new JournalShorcode();
		$only_years = $short_class->get_all_years();
		$all_yearwise_entries = $short_class->get_all_year_wise_entries_array($only_years , $supplier_id);

		$newar = array();
		foreach ($all_yearwise_entries as $key => $value) {
		$newar = array_merge($newar , $value);
		}

		$row_array = array();
						
		if($supplier_id === false || $supplier_id == '')
		{
			return new WP_REST_Response(array("code"=>"supplier_not_found","msg"=>"No supplier assigned.","status"=>404),404);										
		}
		
		else
		{
			foreach ($only_years as $key => $value) {
            
				$current_year_data  =  $all_yearwise_entries[$value];
	
				if(!empty($current_year_data))
				{
					foreach ($current_year_data as $key => $value) {
	
						if(date('Y', strtotime($value['date'])) == (string)$param_year) {
	
							$total_amount_value = array_sum(array_column($newar , "amount"));
							array_shift($newar);
		
							$account_calculation = $total_amount_value;
		
							$amount_format_value = number_format($value['amount'],2,",",".");
									$account_format_value = number_format($account_calculation,2,",",".");
		
							if(strlen($amount_format_value) > 13)
							{
								$amount_format_value = substr($amount_format_value , 0,13)."-";
							}
							if(strlen($account_format_value) > 13)
							{
								$account_format_value = substr($account_format_value , 0,13)."-";
							}  
		
							$row_array[] = array("date"=>$value["date"],"text"=>$value["text"],"amount"=>$amount_format_value,"account"=>$account_format_value,"pdf"=>'');
						}
	
						// printf('<tr><td>%1$s</td><td>%2$s</td><td>%3$s</td><td>%4$s</td></tr>' , $value["date"], $value["text"], $amount_format_value, $account_format_value);
					}
					// echo "</tbody></table>";
				}
	
					 
			} 
			// global $rummmm;
			// $get_clj_settings = get_option("clj_settings");
			// $account_number = $get_clj_settings['deposite_journal'];
			// $get_all_accounting_years = refine_get_request("accounting-years",array(),"exract_accounting_years_values");
			// $only_years = array_column($get_all_accounting_years,"year");	
			// rsort($only_years);
			// foreach($only_years as $year)
			// {
			// 	$get_entries = refine_get_request("accounts/".$account_number."/accounting-years/".$year."/entries",array(),"particular_supplier_data_api_fn");
			// }
			
			// global $wpdb;
			// $clr_jdata = $wpdb->prefix."clr_jdata";								
			// $row_array = array();
			
			// if(!empty($rummmm))
			// { 

			// 	$refined_array = array_filter($rummmm, function ($var) {
			// 		global $supplier_id;
			// 			// return ($var['sid'] == $supplier_id);
			// 			return ($var['sid'] == $supplier_id);
			// });
										
			// usort($refined_array, 'date_compare');

			// $amount_array  = array_column($refined_array,"amount");																					
			// $all_time_profit =  array_sum($amount_array);
			
			// 	foreach($refined_array as $dd)
			// 	{
			// 		$amount_sum = array_sum($amount_array);
			// 		array_shift($amount_array);
					
			// 		$pdf_seelct = $wpdb->get_row("SELECT * From $clr_jdata where clr_close_date = '".$dd['date']."' and clr_gain_text = '".$dd['text']."'",ARRAY_A);
					
			// 		$pdffff = (is_file(__DIR__.'/pdfsss/'.$pdf_seelct['clr_report_file'])) ? plugin_dir_url(__FILE__).'/pdfsss/'.$pdf_seelct['clr_report_file'] : '';
					
					
			// 		$thousand_seperated_account = number_format($amount_sum,2,",",".");
			// 		$thousand_seperated_account2 = number_format($dd['amount'],2,",",".");

			// 			if(strlen($thousand_seperated_account) > 13)
			// 			{
			// 				$thousand_seperated_account = substr($thousand_seperated_account , 0,13)."-";
			// 			}
			// 			if(strlen($thousand_seperated_account2) > 13)
			// 			{
			// 				$thousand_seperated_account2 = substr($thousand_seperated_account2 , 0,13)."-";
			// 			}
					
			// 		//$row_array[] = array("date"=>$dd['date'],"text"=>$dd['text'],"amount"=>$thousand_seperated_account2,"account"=>$thousand_seperated_account,"pdf"=>$pdffff,"clr_entryType"=>$dd['clr_entryType']);
			// 		$row_array[] = array("date"=>$dd['date'],"text"=>$dd['text'],"amount"=>$thousand_seperated_account2,"account"=>$thousand_seperated_account,"pdf"=>$pdffff);
					
			// 	}
				
			// 	// $tmp = array();
			// 	$row_array = array_filter($row_array, function ($var) use($param_year) {
			// 		return ( date('Y', strtotime($var['date'])) == (string)$param_year);
			// 	});
			// 	$row_array = array_values($row_array);
			// 	// foreach ($year_table_data as $)
			// 	// return $year_table_data;
				
			// 	$euro_currency_rate = $get_clj_settings['currency_val'];
			// 	$show_all_time_profit = number_format(($all_time_profit/$euro_currency_rate),"0",".",".");
				
				
			// 	$this_year_array = array_filter($refined_array, function ($var) use($param_year) {
			// 					return ( date('Y', strtotime($var['date'])) == (string)$param_year);
			// 	});
			// 	$current_yaear_amout_array = array_column($this_year_array,"amount");
			// 	$current_yaear_amout = array_sum($current_yaear_amout_array);
			// 	$show_current_yaear_amout = number_format(($current_yaear_amout/$euro_currency_rate),"0",".",".");

			// 	$last_entry = number_format(($refined_array[0]['amount']/$euro_currency_rate),"0",".",".");


			}
				
			$top_array = array("code"=>"data_found","data"=>array('all_time_profit'=>array("label"=>"All Time Profit","value"=>'1'),'year_to_date'=>array("label"=>"Year To Date","value"=>'2000'),'last_period'=>array("label"=>"Last Period","value"=>'1'),'table_title'=>'Gain Percent','table_labels'=>array("Date","Post","Amount","Account","Report"),'table_data'=>$row_array),"status"=>200);
			// $top_array = array("code"=>"data_found","data"=>array('all_time_profit'=>array("label"=>"All Time Profit","value"=>$show_all_time_profit),'year_to_date'=>array("label"=>"Year To Date","value"=>$show_current_yaear_amout),'last_period'=>array("label"=>"Last Period","value"=>$last_entry),'table_title'=>'Gain Percent','table_labels'=>array("Date","Post","Amount","Account","Report"),'table_data'=>$row_array),"status"=>200);
			
		// }

		return new WP_REST_Response($top_array,200);
	}
	
	public function get_user_info($request)
	{
		global $supplier_id;
		$uid = $request->get_param('id');
		
		$user = get_userdata($uid);
		
		// return $user;
		if ( $user === false )
		{
			return new WP_REST_Response(array("code"=>"invalid_user","msg"=>"Invalid user id.","status"=>404),404);					
		}
		
		return new WP_REST_Response(array("code" => "user_found","data" => $user,"status" => 200), 200);
	}
	
	public function get_table_data_cb($request)
	{
		global $supplier_id;
		$uid = $request->get_param('id');
		// return $uid;
		
		$user = get_userdata($uid);
		
		if ( $user === false )
		{
			return new WP_REST_Response(array("code"=>"invalid_user","msg"=>"Invalid user id.","status"=>404),404);					
		}
		
		$supplier_id = get_user_meta($uid,"user_supplier_id",true);
						
		if($supplier_id === false || $supplier_id == '')
		{
			return new WP_REST_Response(array("code"=>"supplier_not_found","msg"=>"No supplier assigned.","status"=>404),404);										
		}
		
		else
		{
			global $rummmm;
			$get_clj_settings = get_option("clj_settings");
			$account_number = $get_clj_settings['deposite_journal'];
			$get_all_accounting_years = refine_get_request("accounting-years",array(),"exract_accounting_years_values");
			$only_years = array_column($get_all_accounting_years,"year");	
			rsort($only_years);
			foreach($only_years as $year)
			{
				$get_entries = refine_get_request("accounts/".$account_number."/accounting-years/".$year."/entries",array(),"particular_supplier_data_api_fn");
			}
			
			global $wpdb;
			$clr_jdata = $wpdb->prefix."clr_jdata";								
			$row_array = array();
				
			if(!empty($rummmm))
			{ 
				$refined_array = array_filter($rummmm, function ($var) {
					global $supplier_id;
						return ($var['sid'] == $supplier_id);
			});
										
			usort($refined_array, 'date_compare');
			$amount_array  = array_column($refined_array,"amount");																					
			$all_time_profit =  array_sum($amount_array);
			
				foreach($refined_array as $dd)
				{
					$amount_sum = array_sum($amount_array);
					array_shift($amount_array);
					
					$pdf_seelct = $wpdb->get_row("SELECT * From $clr_jdata where clr_close_date = '".$dd['date']."' and clr_gain_text = '".$dd['text']."'",ARRAY_A);
					
					$pdffff = (is_file(__DIR__.'/pdfsss/'.$pdf_seelct['clr_report_file'])) ? plugin_dir_url(__FILE__).'/pdfsss/'.$pdf_seelct['clr_report_file'] : '';
					
					
					$thousand_seperated_account = number_format($amount_sum,2,",",".");
					$thousand_seperated_account2 = number_format($dd['amount'],2,",",".");

						if(strlen($thousand_seperated_account) > 13)
						{
							$thousand_seperated_account = substr($thousand_seperated_account , 0,13)."-";
						}
						if(strlen($thousand_seperated_account2) > 13)
						{
							$thousand_seperated_account2 = substr($thousand_seperated_account2 , 0,13)."-";
						}
					
					//$row_array[] = array("date"=>$dd['date'],"text"=>$dd['text'],"amount"=>$thousand_seperated_account2,"account"=>$thousand_seperated_account,"pdf"=>$pdffff,"clr_entryType"=>$dd['clr_entryType']);
					$row_array[] = array("date"=>$dd['date'],"text"=>$dd['text'],"amount"=>$thousand_seperated_account2,"account"=>$thousand_seperated_account,"pdf"=>$pdffff);
					
				}
				
				$euro_currency_rate = $get_clj_settings['currency_val'];
				$show_all_time_profit = number_format(($all_time_profit/$euro_currency_rate),"0",".",".");
				
				
				$this_year_array = array_filter($refined_array, function ($var) {
								return ( date('Y', strtotime($var['date'])) == date("Y"));
				});
				$current_yaear_amout_array = array_column($this_year_array,"amount");
				$current_yaear_amout = array_sum($current_yaear_amout_array);
				$show_current_yaear_amout = number_format(($current_yaear_amout/$euro_currency_rate),"0",".",".");

				$last_entry = number_format(($refined_array[0]['amount']/$euro_currency_rate),"0",".",".");


			}
				
			$top_array = array("code"=>"data_found","data"=>array('all_time_profit'=>array("label"=>"All Time Profit","value"=>$show_all_time_profit),'year_to_date'=>array("label"=>"Year To Date","value"=>$show_current_yaear_amout),'last_period'=>array("label"=>"Last Period","value"=>$last_entry),'table_title'=>'Gain Percent','table_labels'=>array("Date","Post","Amount","Account","Report"),'table_data'=>$row_array),"status"=>200);
			
		}

		return new WP_REST_Response($top_array,200);
	}

	public function get_gain_all_years($request)
	{
		global $supplier_id;
		$uid = $request->get_param('id');
		
		$user = get_userdata($uid);
		
		if ( $user === false )
		{
			return new WP_REST_Response(array("code"=>"invalid_user","msg"=>"Invalid user id.","status"=>404),404);					
		}
		
		$supplier_id = get_user_meta($uid,"user_supplier_id",true);

		$short_class = new JournalShorcode();
		$only_years = $short_class->get_all_years();
		$all_yearwise_entries = $short_class->get_all_year_wise_entries_array($only_years , $supplier_id);

		$gain = array();
		if(!empty($only_years))
		{      
			foreach ($only_years as $key => $value) {
				
				$only_supplierInvoice = array_filter($all_yearwise_entries[$value] ,function($el){
				return  $el['txn_type']=='supplierInvoice';
				});
				$total_value = 0;
				if(!empty($only_supplierInvoice))
				{
				$total_value = number_format(abs(array_sum(array_column($only_supplierInvoice,'amount'))),2,",",".");
				}
				array_push($gain, $total_value);
				// printf('<li><a href="%1$s">%2$s %4$s</a><span>%3$s</span></li>' , "#y_".$value ,$value,$total_value,__("Gain" , "j-data"));
			}
		}
						
				
		$top_array = array("code"=>"data_found","data"=>array('year' => $only_years, 'gain' => $gain),"status"=>200);
			

		return new WP_REST_Response($top_array,200);
	}
	
	public function get_chart_data($request)
	{
		
		$user_id = $request->get_param("id");		
		$user = get_userdata( $user_id );
		if ( $user === false ) {
					return 	new WP_REST_Response(array("code"=>"invalid_user","msg"=>"Invalid user id.","data"=>array("status"=>404)),404);
		}	
		
		global $wpdb;
		$clr_jdata = $wpdb->prefix."clr_jdata";

		$date = strtotime(date("Y-m-d") .' -1 year');
		$date = date('Y-m-d', $date); 
		
		$all_latest_db_entries = $wpdb->get_results(" SELECT * From (Select * from $clr_jdata order by clr_id  DESC LIMIT 0,53) as asssyst WHERE clr_close_date >= '" . $date . "' order by clr_close_date ASC ",ARRAY_A);
		// $all_latest_db_entries = $wpdb->get_results(" SELECT * From (Select * from $clr_jdata order by clr_id  DESC LIMIT 0,53) as asssyst order by clr_close_date ASC ",ARRAY_A);

		if(!empty($all_latest_db_entries))
		{
			$close_date_array = array_column($all_latest_db_entries,"clr_close_date");
			$gain_data = array_column($all_latest_db_entries,"clr_gain_percentage");
			$amount_data = array_column($all_latest_db_entries,"clr_total_gain");

			$total_percent = array();

			foreach($gain_data as $percent) {
				if (count($total_percent) == 0) array_push($total_percent, $percent);
				else {
					$tmp = $total_percent[count($total_percent) - 1] + $percent;
					array_push($total_percent, (string)$tmp);
				}
			}


			return new WP_REST_Response(array("code"=>"chart_found","data"=>array("x-axes"=>$close_date_array,"y-axes"=>$gain_data, "amount" => $amount_data, "total percent" => $total_percent),"status"=>200),200);
		}
		else
		{
			return new WP_REST_Response(array("code"=>"chart_not_found","data"=>array("x-axes"=>array(),"y-axes"=>array(), "amount" => array(), "total percent" => array()),"status"=>200),200);
		}
		
	}

	public function get_default_value($request)
	{
		$month = date('m');
		$dateObj   = DateTime::createFromFormat('!m', $month);
		$monthStr = $dateObj->format('F');
		
		global $wpdb;
		$j_weekly_data = $wpdb->prefix."j_weekly_data";
		$all_weekly_data = $wpdb->get_results(" Select * from $j_weekly_data order by wdate  DESC ");
		
		if(!empty($all_weekly_data)) {
			$i = 0;
			$current_month_percent = 0;
			foreach($all_weekly_data as $data) {
				if($i == 0) $last_date = $data->wdate;
				$last_date = date_format(date_create($last_date), "d-M-Y");
				if(date('m', strtotime($data->wdate)) == $month) $current_month_percent += $data->wgain;
				$i++;
			}
		}
		

		$clr_jdata = $wpdb->prefix."clr_jdata";

		$date = strtotime(date("Y-m-d") .' -1 year');
		$date = date('Y-m-d', $date); 
		
		$all_latest_db_entries = $wpdb->get_results(" SELECT * From (Select * from $clr_jdata order by clr_id  DESC LIMIT 0,53) as asssyst WHERE clr_close_date >= '" . $date . "' order by clr_close_date ASC ",ARRAY_A);
		if(!empty($all_latest_db_entries))
		{
			$gain_data = array_column($all_latest_db_entries,"clr_gain_percentage");
			$gain_data = array_sum($gain_data) + $current_month_percent;

			$current_month_percent = number_format($current_month_percent, 2);
			$gain_data = number_format($gain_data, 2);
			return new WP_REST_Response(array("code"=>"chart_found","data"=>array("current_month" => $monthStr, "last_date" => $last_date, "current_month_percent" => $current_month_percent, "gain_data" => $gain_data),"status"=>200),200);
		}
		else
		{
			return new WP_REST_Response(array("code"=>"chart_found","data"=>array("current_month" => array(), "last_date" => array(), "current_month_percent" => array(), "gain_data" => array()),"status"=>200),200);
		}
		
	}

	public function get_chart_year_data($request)
	{
		$user_id = $request->get_param("id");		
		$user = get_userdata( $user_id );
		if ( $user === false ) {
			return 	new WP_REST_Response(array("code"=>"invalid_user","msg"=>"Invalid user id.","data"=>array("status"=>404)),404);
		}	
		$year = $request->get_param("year");
		
		global $wpdb;
		$clr_jdata = $wpdb->prefix."clr_jdata";

		$date = strtotime(date("Y-m-d") .' -1 year');
		$date = date('Y-m-d', $date); 
		
		$all_latest_db_entries = $wpdb->get_results(" SELECT * From (Select * from $clr_jdata order by clr_id  DESC LIMIT 0,53) as asssyst WHERE `clr_close_date` BETWEEN '$year-01-01' AND '$year-12-31' order by clr_close_date ASC ",ARRAY_A);

		if(!empty($all_latest_db_entries))
		{
			$close_date_array = array_column($all_latest_db_entries,"clr_close_date");
			$gain_data = array_column($all_latest_db_entries,"clr_gain_percentage");
			$amount_data = array_column($all_latest_db_entries,"clr_total_gain");
			$total_percent = array();

			foreach($gain_data as $percent) {
				if (count($total_percent) == 0) array_push($total_percent, $percent);
				else {
					$tmp = $total_percent[count($total_percent) - 1] + $percent;
					array_push($total_percent, (string)$tmp);
				}
			}


			return new WP_REST_Response(array("code"=>"chart_found","data"=>array("x-axes"=>$close_date_array,"y-axes"=>$gain_data, "amount" => $amount_data, "total percent" => $total_percent),"status"=>200),200);
		}
		else
		{
			return new WP_REST_Response(array("code"=>"chart_not_found","data"=>array("x-axes"=>array(),"y-axes"=>array(), "amount" => array(), "total percent" => array()),"status"=>200),200);
		}
		
	}

	public function get_club_all_years_data($request)
	{
		global $wpdb;
		$j_years = $wpdb->prefix."j_years";	
		$all_j_years_data = $wpdb->get_results("SELECT yname From $j_years", ARRAY_A);
		$all_years = array_column($all_j_years_data, "yname");
		rsort($all_years);
		$all_performance = array();
		if(!empty($all_years))
		{
			$clr_jdata = $wpdb->prefix."clr_jdata";
			foreach ($all_years as $year) {
				$clr_gain_percentage = $wpdb->get_results("SELECT SUM(clr_gain_percentage) From $clr_jdata WHERE `clr_close_date` BETWEEN '$year-01-01' AND '$year-12-31'", ARRAY_A);
				$performance = floor(array_column($clr_gain_percentage, "SUM(clr_gain_percentage)")[0] * 100) / 100;
				// $performance = floor(array_column($clr_total_gain, "SUM(clr_total_gain)")[0] / array_column($clr_total_deposite, "SUM(clr_total_deposite)")[0] * 10000) / 100;
				array_push($all_performance, strval($performance));
			}

			return new WP_REST_Response(array("code" => "year_found", "data" => array("year"=>$all_years,"performance"=>$all_performance),"status"=>200),200);
		}
		else
		{
			return new WP_REST_Response(array("code" => "year_not_found", "data" => array("year" => array(), "performance" => array()), "status" => 200), 200);
		}
		
	}
	
	public function create_user($request)
	{		 
		$username = $request->get_param('username');
		$password = $request->get_param('password');
		$cpassword = $request->get_param('cpassword');
		 
	    $fname = $request->get_param("fname");
	    $lname = $request->get_param("lname");
	    $email = $request->get_param("email");
		
		if($password != $cpassword)
		{
			return new WP_REST_Response(array("code"=>"password_not_matched","msg"=>"Password not matched.","status"=>400),400);

		}
		 
		$inserting_user = wp_insert_user(array("user_login"=>$username,"user_email"=>$email,"user_pass"=>$password,"first_name"=>$fname,"last_name"=>$lane,"role"=>"subscriber"));							
		
			if( is_wp_error( $inserting_user ))
			{
				$error_code =  $inserting_user->get_error_code();
				$error_msg =  $inserting_user->get_error_message();
				return new WP_REST_Response(array("code"=>$error_code,"msg"=>$error_msg,"status"=>400),400);			
			}
			else
			{
				return new WP_REST_Response(array("code"=>$error_code,"msg"=>"Thank you for registering at youtimizer.com<br>
Your registration has been sent to Youtimizer.com and <br>they will contact you as soon as possible.<br>Thank you again for your interest!<br>Youtimizer.com.","status"=>201),201);
			}
	}
	
	public function forgot_password($request)
	{
		$posted_email = $request->get_param('email');
						
				if ( empty( $posted_email ) || ! is_string( $posted_email ) )
				{
					return new 	WP_REST_Response(array("code"=>"invalid_username","msg"=>"Enter a username or email address.","status"=>404),404);
				} 
				elseif ( strpos( $posted_email, '@' ) ) 
				{
					$user_data = get_user_by( 'email', trim( wp_unslash( $posted_email ) ) );
					if ( empty( $user_data ) ) {
						return new 	WP_REST_Response(array("code"=>"invalid_username","msg"=>"There is no account with that username or email address.","status"=>404),404);
					}
				} 
				else 
				{
					$login     = trim( $posted_email );
					$user_data = get_user_by( 'login', $login );
				}
							
			if ( ! $user_data ) {
				
				return new 	WP_REST_Response(array("code"=>"invalid_username","msg"=>"There is no account with that username or email address.","status"=>404),404);
				
			}
				
	$user_login = $user_data->user_login;
	$user_email = $user_data->user_email;
	$key        = get_password_reset_key( $user_data );

	if ( is_wp_error( $key ) ) {
		return $key;
	}

	if ( is_multisite() ) {
		$site_name = get_network()->site_name;
	} else {		
		$site_name = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
	}
	
	$message = __( 'Someone has requested a password reset for the following account:' ) . "\r\n\r\n";
	$message .= sprintf( __( 'Site Name: %s' ), $site_name ) . "\r\n\r\n";
	$message .= sprintf( __( 'Username: %s' ), $user_login ) . "\r\n\r\n";
	$message .= __( 'If this was a mistake, just ignore this email and nothing will happen.' ) . "\r\n\r\n";
	$message .= __( 'To reset your password, visit the following address:' ) . "\r\n\r\n";
	$message .= '(' . network_site_url( "wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user_login ), 'login' ) . ")\r\n";

	$title = sprintf( __( '[%s] Password Reset' ), $site_name );	

	if ( $message && ! wp_mail( $user_email, wp_specialchars_decode( $title ), $message ) ) 
		{
			return new 	WP_REST_Response(array("code"=>"email_senr","msg"=> __( 'The email could not be sent.' ) . "<br />\n" . __( 'Possible reason: your host may have disabled the mail() function.' ),"status"=>404),404);
						
		}
		else
		{
			return new 	WP_REST_Response(array("code"=>"email_senr","msg"=>"Please check your email with password rest link.","status"=>200),200);
		}
	}
}
function cl_rest_initial_callback()
{
	$ddd = new Pr_App;
	$ddd->pr_reg_routes();
}
add_action("rest_api_init","cl_rest_initial_callback");


function cl_forgot_email_callback($message, $key, $user_login, $user_data)
{
	$message = __( 'Someone has requested a password reset for the following account:' ) . "\r\n\r\n";
	/* translators: %s: site name */
	$message .= sprintf( __( 'Site Name: %s'), $site_name ) . "\r\n\r\n";
	/* translators: %s: user login */
	$message .= sprintf( __( 'Username: %s'), $user_login ) . "\r\n\r\n";
	$message .= __( 'If this was a mistake, just ignore this email and nothing will happen.' ) . "\r\n\r\n";
	$message .= __( 'To reset your password, visit the following address:' ) . "\r\n\r\n";
	$message .= '(' . network_site_url( "wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user_login ), 'login' ) . ")\r\n";
	
	return $message;
}	
add_filter( 'retrieve_password_message', "cl_forgot_email_callback",10,4);


/*================================================*/
// Phase 2 code starts from here now
/*====*/

if(!defined("JOURNAL_PLUGIN_FILE_PATH"))
{
    //Here we are defining main plugin file. Which will be used by different hooks later.
	define("JOURNAL_PLUGIN_FILE_PATH",__FILE__);
}
if ( ! class_exists( 'ExtendedFunctions', false ) ) {
    require_once(dirname( JOURNAL_PLUGIN_FILE_PATH )."/classes/extendedfunctions.php");
    ExtendedFunctions::instance();
}