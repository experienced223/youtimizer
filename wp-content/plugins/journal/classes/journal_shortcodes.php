<?php
defined( 'ABSPATH' ) || exit;
final class JournalShorcode extends JournalApiClass{

    protected static $_instance = null;
    public static function init() {
       
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }        
        return self::$_instance;
    }

    public function __construct()
    {
        parent::__construct();
        add_shortcode( "j_user_profile", array($this , "j_user_profile_cb") );
        add_shortcode( "j_gain_and_deposite_table", array($this , "j_gain_and_deposite_table_cb") );
        add_shortcode( "j_club_performance_chart_and_table", array($this , "j_club_performance_chart_and_table_cb") );

        //To update transient we have coded this below lines...
        if(isset($_GET["updateDb"])  && $_GET["updateDb"] == true)
        {
            if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
            {
                $url = "https://";
            }
            else
            {
                $url = "http://";
            }
           $get_all_years = $this->get_all_years(true);
           $all_years_entries  = $this->get_all_year_wise_entries_array($get_all_years , 0 , true);
           wp_redirect( $url.$_SERVER['HTTP_HOST'].parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)); die();
        }
    }

    //Get all years here
    public function get_all_years($reset = false)
    {
            //Setting up wordpress transiant to cache this api request....
                    //Checking transiant
                    $only_years = get_transient( 'transiants_j_all_year_values' );
                    if($only_years === false  || $reset == true)
                    {
                        
                            //Making api request from parent class to get all years
                                $get_all_accounting_years = $this->make_api_request("accounting-years?sort=-year");
                                $only_years = array_column($get_all_accounting_years,"year");
                            //Sorting the years
                                //rsort($only_years);
                            //Storing arrays in transient
                                set_transient('transiants_j_all_year_values',$only_years,60*HOUR_IN_SECONDS);
                    }
                    return $only_years;
    }

    //Get Year wise entries for particular supplier...
    public function get_all_year_wise_entries_array($only_years , $user_supplier_id = 0 , $reset = false)
    {
                    //Getting Setting values from parent class 
                    $get_clj_settings = $this->get_clj_settings;
                    $account_number = $get_clj_settings['deposite_journal'];
                    $all_yearwise_entries = array();

                    foreach($only_years as $year)
                    {
                        //Setting up wordpress transiant to cache this api request....
                        //Checking transiant
                        $year_entries = get_transient( 'transiants_records_of_year_'.$year );
                        
                        if($year_entries === false || $reset == true)
                        {                        
                                //Making api request to get all supplier data for this years from parent class
                                $year_entries = $this->make_api_request("accounts/".$account_number."/accounting-years/".$year."/entries?sort=-date");
                                //Storing arrays in transient                                
                                set_transient('transiants_records_of_year_'.$year,$year_entries,60*HOUR_IN_SECONDS);
                        }
                                                
                        //Filter entries based on supplier ID here...
                        if($user_supplier_id > 0)
                        {
                            $all_yearwise_entries[$year] = $this->get_entries_of_supplier($year_entries , $user_supplier_id);
                        }
                        
                    }
                    return $all_yearwise_entries;
    }

    //Get entries of assigned supplier...
    public function get_entries_of_supplier($get_entries , $user_supplier_id)
    {
        if(!empty($get_entries))
        {
            $new_supplier_array = array();
               foreach ($get_entries as $key => $value) {
                   if($value["supplier"]["supplierNumber"] == $user_supplier_id)
                   {
                        $new_supplier_array[] = array("text" => $value['text'] , "date" => $value['date'] ,"amount" => $value['amount'] , "txn_type" => $value["entryType"]);
                   }
               }            
            return $new_supplier_array;
        }
        
        return array();
    }

    
    public function club_performance_year_wise_data($years = array(),$weekly_data = array())
    {
        
        $year_wise_data = array();
        if(!empty($years))
        {
            foreach($years as $y)
            {
                $year_wise_data[$y["yname"]] = array_filter($weekly_data , function($v) use ($y){return $v["yname"] == $y["yname"];});
            }
        }
        return ($year_wise_data);
    }
    public function club_performance_year_wise_data_table($years = array(),$table_data = array())
    {        
        $year_wise_data = array();
        if(!empty($years))
        {
            foreach($years as $y)
            {
                $year_wise_data[$y["yname"]] = array_filter($table_data , function($v) use ($y){return $y["yname"] == date('Y', strtotime($v['clr_close_date']));});
            }
        }        
        return ($year_wise_data);
    }

    

    // USER PROFILE PAGE
    function j_user_profile_cb()
    {
        if(is_admin())
        {
            return;
        }
        ob_start();
            wp_enqueue_style('j-front-css-common');
            wp_enqueue_script('j-front-js-common');

            $str_error = sprintf('<div class="unauthorized-error">%s</div>' , esc_html__( 'Your user login, are not connected to anYoutimizer Account, please contact Youtimizer.com if you have or want an Youtimizer Account', 'j-data' ));

            if(is_user_logged_in())
            {
                  $user_id = get_current_user_id();
                  $user_supplier_id = get_user_meta( $user_id, "user_supplier_id", true );
                  $weekly_mail = get_user_meta( $user_id, "weekly_mail", true );
                  $weekly_push_notification = get_user_meta( $user_id, "weekly_push_notification", true );
                  $monthly_mail = get_user_meta( $user_id, "monthly_mail", true );
                  if($user_supplier_id && $user_supplier_id >0 )
                  {
                    $user_info = get_userdata($user_id);
                    $user_email = ($user_info->user_email);

                    //You can override this template by placing it inside your theme/templates/userprofile.php folder.
                    if ( false === include(locate_template( 'templates/userprofile.php')) )
                    {
                        include(JOURNAL_PLUGIN_PATH.'/templates/userprofile.php');
                    }
                  }
                  else {
                      echo $str_error;
                  }
            }
            else {
                echo $str_error;
            }            
        return ob_get_clean();
    }

    // GAIN AND DEPOSITES PAGE
    function j_gain_and_deposite_table_cb()
    {
        if(is_admin())
        {
            return;
        }
        ob_start();
        wp_enqueue_script('jquery-ui-tabs');
        wp_enqueue_style('j-front-tabs');

        wp_enqueue_style('j-front-css-common');        
        wp_enqueue_script('j-front-js-common');                

        $str_error = sprintf('<div class="unauthorized-error">%s</div>' , esc_html__( 'Your user login, are not connected to anYoutimizer Account, please contact Youtimizer.com if you have or want an Youtimizer Account', 'j-data' ));

            if(is_user_logged_in())
            {
                  $user_id = get_current_user_id();
                  $user_supplier_id = get_user_meta( $user_id, "user_supplier_id", true );
                  if($user_supplier_id && $user_supplier_id > 0 )
                  {
                    $only_years = $this->get_all_years();
                    $all_yearwise_entries = $this->get_all_year_wise_entries_array($only_years , $user_supplier_id);
                    //You can override this template by placing it inside your theme/templates/loop-mycustomposttype.php folder.
                    if ( false === include(locate_template( 'templates/loop-mycustomposttype.php')) )
                    {
                        include(JOURNAL_PLUGIN_PATH.'/templates/loop-mycustomposttype.php');
                    }
                  }
                  else {
                      echo $str_error;
                  }
            }
            else {
                echo $str_error;
            }
            
        return ob_get_clean();
    }

    // CLUB PERFORMANCE PAGE    
    function j_club_performance_chart_and_table_cb()
    {
        if(is_admin())
        {
            return;
        }
        ob_start();        

        if(is_user_logged_in())
            {
                wp_enqueue_script('jquery-ui-tabs');
                wp_enqueue_style('j-front-tabs');

                wp_enqueue_style('j-front-css-common');
                wp_enqueue_script('j-front-js-common');
                
                global $wpdb;
                $j_years = $wpdb->prefix.'j_years';
                $j_weekly_data = $wpdb->prefix.'j_weekly_data';
                $table_data = $wpdb->prefix.'clr_jdata';

                //First getting years from database.
                $get_all_years  = $wpdb-> get_results("SELECT * FROM $j_years order by yname+0 desc", ARRAY_A);
                
                //Second getting all records from weekly datbase.
                $get_all_weekly_data  = $wpdb-> get_results("SELECT * FROM $j_weekly_data w JOIN $j_years y ON w.wyid = y.yid order by w.wdate desc", ARRAY_A);

                //Third getting all records from table database.
                $get_all_table_data  = $wpdb-> get_results("SELECT * FROM $table_data order by clr_close_date desc", ARRAY_A);

                //Per year make array of weekly data
               $weekly_year_wise_data =  $this->club_performance_year_wise_data($get_all_years,$get_all_weekly_data);

               //Per year make array of table data
               $get_all_table_year_wise_data = $this->club_performance_year_wise_data_table($get_all_years,$get_all_table_data);


               //Past 12 month total get
                // $past_12_month_total = $wpdb-> get_row("SELECT SUM(clr_gain_percentage + 0) as total_percentage from $table_data where clr_close_date >= DATE_SUB(NOW(),INTERVAL 1 YEAR)" , ARRAY_A);
                // print_r($past_12_month_total); exit;
               

                //You can override this template by placing it inside your theme/templates/loop-mycustomposttype.php folder.
                if ( false === include(locate_template( 'templates/club-performance.php')) )
                {
                    include(JOURNAL_PLUGIN_PATH.'/templates/club-performance.php');
                }
            }
            
        return ob_get_clean();
    }    
}
?>