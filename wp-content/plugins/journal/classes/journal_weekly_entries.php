<?php
defined('ABSPATH') || exit;

if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class JournalWeeklyList extends WP_List_Table
{
    /** Class constructor */
    public function __construct()
    {
        parent::__construct([
            'singular' => __('Gain notification', 'j-data'), //singular name of the listed records
            'plural'   => __('Gain notifications', 'j-data'), //plural name of the listed records
            'ajax'     => false //does this table support ajax?
        ]);                
    }
    public function get_redirect_url()
    {
        return admin_url("admin.php?page=j-weekly-gain");
    }
    public static function get_database_table()
    {
        global $wpdb;
        return array($wpdb->prefix.'j_weekly_data' , $wpdb->prefix.'j_years');
    }

    /**
     * Retrieve customers data from the database
     *
     * @param int $per_page
     * @param int $page_number
     *
     * @return mixed
     */
    public static function get_weekly_entries($per_page = 5, $page_number = 1)
    {
        global $wpdb;
        $weekly_table =  self::get_database_table()[0];
        $year_table =  self::get_database_table()[1];

        $sql = "SELECT * FROM $weekly_table w JOIN $year_table y ON w.wyid = y.yid";

        if (isset($_REQUEST["s"])) {
            //$sql .= " where w.wdate LIKE '%" . $_REQUEST["s"] . "%' OR w.wgain  LIKE '%" . $_REQUEST["s"] . "%'  OR  y.yname LIKE '%" . $_REQUEST["s"] . "%' ";
            $sql .= " where  y.yname = '" . $_REQUEST["s"] . "'";
        }

        if (!empty($_REQUEST['orderby'])) {
            if($_REQUEST['orderby'] == 'w.wgain')
            {
                $sql .= ' ORDER BY ' . esc_sql($_REQUEST['orderby']."+0");
            }
            else
            {
                $sql .= ' ORDER BY ' . esc_sql($_REQUEST['orderby']);
            }
            
            $sql .= !empty($_REQUEST['order']) ? ' ' . esc_sql($_REQUEST['order']) : ' ASC';
        } else {
            $sql .= ' ORDER BY yid ASC';
        }

        $sql .= " LIMIT $per_page";
        $sql .= ' OFFSET ' . ($page_number - 1) * $per_page;


        $result = $wpdb->get_results($sql, 'ARRAY_A');

        return $result;
    }

    /**
     * Delete a customer record.
     *
     * @param int $id customer ID
     */
    public static function delete_year($id)
    {
        global $wpdb;

        $weekly_table =  self::get_database_table()[0];

        $wpdb->delete(
            $weekly_table,
            ['wid' => $id],
            ['%d']
        );
    }


    /**
     * Returns the count of records in the database.
     *
     * @return null|string
     */
    public static function record_count()
    {
        global $wpdb;

        $weekly_table =  self::get_database_table()[0];
        $year_table =  self::get_database_table()[1];

        $sql = "SELECT COUNT(*) FROM $weekly_table w JOIN $year_table y ON w.wyid = y.yid";
        

        if (isset($_REQUEST["s"])) {
            //$sql .= " where w.wdate LIKE '%" . $_REQUEST["s"] . "%' OR w.wgain  LIKE '%" . $_REQUEST["s"] . "%'  OR  y.yname LIKE '%" . $_REQUEST["s"] . "%' ";
            $sql .= " where  y.yname = '" . $_REQUEST["s"] . "'";
        }

        return $wpdb->get_var($sql);
    }


    /** Text displayed when no customer data is available */
    public function no_items()
    {
        _e('No Gain notification avaliable.', 'j-data');
    }


    /**
     * Render a column when no column specific method exist.
     *
     * @param array $item
     * @param string $column_name
     *
     * @return mixed
     */
    public function column_default($item, $column_name)
    {
        switch ($column_name) {
            case 'wdate':
            case 'wgain':
            case 'wnotifydate':
                return $item[$column_name];                
            default:
                return print_r($item, true); //Show the whole array for troubleshooting purposes
        }
    }

    /**
     * Render the bulk edit checkbox
     *
     * @param array $item
     *
     * @return string
     */
    function column_cb($item)
    {
        return sprintf(
            '<input type="checkbox" name="bulk-delete[]" value="%s" />',
            $item['yid']
        );
    }


    /**
     * Method for name column
     *
     * @param array $item an array of DB data
     *
     * @return string
     */
    function column_wyname($item)
    {
        $title = '<strong>' . $item['yname'] . '</strong>';
        return $title;
    }

    function column_action($item)
    {
        $nonce = wp_create_nonce('edit-delete-gain');

        return sprintf("<a href='%1\$s'>Edit</a> | <a href='%2\$s' onclick='%3\$s'>Delete</a>", $this->get_redirect_url()."&action=update_gain&id=" . $item["wid"], $this->get_redirect_url()."&action=delete&id=" . $item["wid"] . "&cnonce=" . $nonce , 'return confirm("Want to delete?");');
    }

    function column_send_notification($item)
    {        
        return sprintf('<a data-val="%s" href="javascript:void(0);" class="send-week-notification">%s</a>',$item["wid"],esc_html__("Send Notification" , "j-data"));
    }


    /**
     *  Associative array of columns
     *
     * @return array
     */
    function get_columns()
    {
        $columns = array(
            'cb' => '<input type="checkbox" />',
            'wyname'    => __('Year', 'j-data'),
            'wdate'    => __('Date', 'j-data'),
            'wgain'    => __('Percentage', 'j-data'),
            'send_notification' => __('Send Notification', 'j-data'),
            'wnotifydate'    => __('Notification send date', 'j-data'),
            'action' => __('Action', 'j-data')
        );

        return $columns;
    }

    /**
     * Columns to make sortable.
     *
     * @return array
     */
    public function get_sortable_columns()
    {
        $sortable_columns = array(
            'wyname' => array('y.yname', true),
            'wdate' => array('w.wdate', true),
            'wgain' => array('w.wgain', true)
        );
        return $sortable_columns;
    }

    /**
     * Returns an associative array containing the bulk action
     *
     * @return array
     */
    public function get_bulk_actions()
    {
        $actions = [
            'bulk-delete' => 'Delete'
        ];
        return $actions;
    }

    /**
     * Handles data query and filter, sorting, and pagination.
     */
    public function prepare_items()
    {

        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);

        /** Process bulk action */
        $this->process_bulk_action();

        $per_page     = $this->get_items_per_page('cities_per_page', 20);
        $current_page = $this->get_pagenum();
        $total_items  = self::record_count();

        $this->set_pagination_args([
            'total_items' => $total_items, //WE have to calculate the total number of items
            'per_page'    => $per_page //WE have to determine how many items to show on a page
        ]);

        $this->items = self::get_weekly_entries($per_page, $current_page);
    }

    public function process_bulk_action()
    {
        //Detect when a bulk action is being triggered...

        if ('delete' === $this->current_action()) {

            // In our file that handles the request, verify the nonce.
            $nonce = esc_attr($_REQUEST['cnonce']);

            if (!wp_verify_nonce($nonce, "edit-delete-gain")) {
                die('Go get a life script kiddies');
            } else {
                self::delete_year(absint($_REQUEST['id']));
                $_SESSION["msg"] = 'deleted';
                wp_redirect($this->get_redirect_url());
                exit;
            }
        }

        // If the delete bulk action is triggered
        if ((isset($_REQUEST['action']) && $_REQUEST['action'] == 'bulk-delete')
            || (isset($_REQUEST['action2']) && $_REQUEST['action2'] == 'bulk-delete')
        ) {

            $delete_ids = esc_sql($_REQUEST['bulk-delete']);

            // loop over the array of record IDs and delete them
            foreach ($delete_ids as $id) {
                self::delete_year($id);
            }

            // esc_url_raw() is used to prevent converting ampersand in url to "#038;"
            // add_query_arg() return the current url
            $_SESSION["msg"] = 'deleted';
            wp_redirect($this->get_redirect_url());
            exit;
        }
    }
}
?>
<div class="wrap">

<?php
   $get_clj_settings = get_option("clj_settings");

    global $wpdb;
    $gain_obj = new JournalWeeklyList();
    
    $weekly_table =  $gain_obj::get_database_table()[0];
    $year_table =  $gain_obj::get_database_table()[1];

//Start session
session_start();

//Send Emails to users
if(isset($_POST["send_email_users"]) && $_POST["primary_id"] > 0)
{
    //By default wordpress adds slashes to the post and get request fields. Below function will remove slashes from the values so you will get same values as user inputs from front end.    
    $_POST = array_map( 'stripslashes_deep', $_POST );
    
        $email_subject = $get_clj_settings['send_report_subject'];
		$email_from_name = $get_clj_settings['send_report_from_name'];
        $email_from_email = $get_clj_settings['send_report_from_email'];
        
        //Below function will add p tab for br values from textarea in request.
		$email_message = wpautop($_POST['send_notifaction']);
		
		$get_rowww = $wpdb->get_row("SELECT * from $weekly_table where wid  = ".$_POST['primary_id'],ARRAY_A);
        
        
        //Calculate cummulative values now....
                
        $get_cumulative_sum = $wpdb->get_row("SELECT SUM(wgain) as summ from $weekly_table where wdate  <= '".$get_rowww['wdate']."' and year(wdate) = year('".$get_rowww["wdate"]."')",ARRAY_A);

		$email_message = str_replace("%%date%%",$get_rowww['wdate'],$email_message);
		$email_message = str_replace("%%gain_value%%",$get_rowww['wgain'],$email_message);
		$email_message = str_replace("%%cumulative_value%%",$get_cumulative_sum['summ'],$email_message);
						
				
		$headers[] = 'From: '.$email_from_name.' <'.$email_from_email.'>';
		$attachmentss = array();
		//$attachmentss[] = __DIR__."/pdfsss/".$get_rowww['clr_report_file'];
		
		
			$blogusers = get_users(array('meta_key' => 'weekly_mail',
			'meta_value' => '1',
			'meta_compare' => '='));
			if($blogusers)
			{
				foreach($blogusers as $usr)
				{
					$user_first_name = $usr->display_name;
					$email_message = str_replace("%%user_name%%",$user_first_name,$email_message);					
					wp_mail($usr->user_email,$email_subject,$email_message,$headers,$attachmentss);
                    //wp_mail("test.cloudweblabs@gmail.com",$email_subject,$email_message,$headers,$attachmentss);
                    
				}	
			}
			$wpdb->update($weekly_table , array("wnotifydate"=>date("Y-m-d")) , array("wid" => $_POST['primary_id']));
            $_SESSION["msg"] = 'mail_sent';
            wp_redirect($gain_obj->get_redirect_url());die();
        
}

//Saving values inside database.
if((isset($_POST["add_gain"]) && $_POST["add_gain"] == "Add Gain" ) || (isset($_POST["update_gain"]) && $_POST["update_gain"] == "Update Gain"))
    {
        if (!filter_input(INPUT_POST, "journal_year", FILTER_VALIDATE_INT)) {
            die("Invalid values");
        }

        $year = sanitize_text_field($_POST["journal_year"]);
        $date = $_POST["gain_date"];
        $gain_percentage = $_POST["gain_percentage"];


        $error = true;
        if($_POST["add_gain"])
        {
            $wpdb->insert($weekly_table , array("wyid" => $year ,"wdate" => $date,"wgain" => $gain_percentage));
            if($wpdb->insert_id > 0)
            {
                $error = false;
                $msg= "added";
            }            
        }
        else if($_POST["update_gain"])
        {
            $wpdb->update($weekly_table , array("wyid" => $year ,"wdate" => $date,"wgain" => $gain_percentage),array("wid" => $_GET['id']));
            $error = false;
            $msg= "updated";
        }
        
        if(!$error)
        {            
            $_SESSION["msg"] = $msg;
            wp_redirect($gain_obj->get_redirect_url());die();
        }
        else
        {
            die("Error....");
        }
    }

//Show data in UI.

if(isset($_GET["action"]) && ($_GET["action"] == "add_gain" || $_GET["action"] == "update_gain"))
{
    wp_enqueue_style( 'jquery-ui-datepicker-style' , 'https://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css');
    wp_enqueue_script( 'jquery-ui-datepicker' );

    $existing_years = $wpdb->get_results("SELECT * FROM $year_table order By yid asc" , ARRAY_A);

    if($wpdb->num_rows == 0)
    {
        die("Please first add years..");
    }

    $res = array();
    if($_GET["action"] == "update_gain")
    {
        //Get record from database
        $res = $wpdb->get_row("SELECT * from $weekly_table where wid = {$_GET['id']}" , ARRAY_A);
    }
    
    $button_name = $_GET["action"] == "add_gain" ? "add_gain" : "update_gain";
    $button_value = $_GET["action"] == "add_gain" ? "Add Gain" : "Update Gain";


    $year_val = isset($res['wyid']) ? $res['wyid'] : "";
    $gain_date = isset($res['wdate']) ? $res['wdate'] : "";
    $gain_percentage = isset($res['wgain']) ? $res['wgain'] : "";    
    ?>

    <h1 id="add-new-user"><?php echo $button_label; ?></h1>

    <form method="post" id="createuser">
        <table class="form-table">
            <tr class="form-field form-required">
                <th scope="row">
                    <label for="journal_year">Select Year<span class="description">(required)</span></label>
                </th>
                <td>
                    <select name="journal_year" required="required">
                    <option value="">Select Year</option>
                        <?php
                            foreach ($existing_years as $key => $value) {
                                $seelcted = (($value['yid'] == $year_val )  || ($_GET["yid"] == $value['yid'])) ? "selected='selected'" : "";
print <<<POP
<option value="{$value['yid']}" {$seelcted}>{$value['yname']}
POP;
                                }
                        ?>
                    </select>
                </td>
            </tr>

            <tr class="form-field form-required">
                <th scope="row">
                    <label for="gain_date">Date<span class="description">(required)</span></label>
                </th>
                <td>
                    <input type="text" placeholder="Enter date" name="gain_date" id="gain_date" required value=
                    "<?php echo $gain_date?>">
                </td>
            </tr>

            <tr class="form-field form-required">
                <th scope="row">
                    <label for="gain_percentage">Percentage<span class="description">(required)</span></label>
                </th>
                <td>
                    <input type="text" placeholder="Enter Percentage" name="gain_percentage" id="gain_percentage" required value=
                    "<?php echo $gain_percentage?>">
                </td>
            </tr>

        </table>
        <?php submit_button($button_value,'primary',$button_name);?>
    </form>
    
    <script>
    jQuery(document).ready(function($) {
        $("#gain_date").datepicker({dateFormat: 'yy-mm-dd'});
    });
    </script>
    <?php
}
else
{
?>
                <h1 class="wp-heading-inline"><?php esc_html_e( "All Gain Notification", 'j-data' );?></h1>

                <a href="<?php echo esc_url( $gain_obj->get_redirect_url() ."&action=add_gain")?>" class="page-title-action"><?php esc_html_e( "Add New Gain Notification", "j-data" );?></a>


<?php
if(isset($_SESSION["msg"]) && $_SESSION["msg"]=="updated")
{
    printf('<div class="updated notice"><p> %s</p></div>' , esc_html__( "Gain Updated Successfully!", 'j-data' ));
}
if(isset($_SESSION["msg"]) && $_SESSION["msg"]=="deleted")
{
    printf('<div class="updated notice"><p> %s</p></div>' , esc_html__( "Gain Deleted Successfully!", 'j-data' ));
}
if(isset($_SESSION["msg"]) && $_SESSION["msg"]=="added")
{
    printf('<div class="updated notice"><p> %s</p></div>' , esc_html__( "Gain Added Successfully!", 'j-data' ));
}
if(isset($_SESSION["msg"]) && $_SESSION["msg"]=="mail_sent")
{
    printf('<div class="updated notice"><p> %s</p></div>' , esc_html__( "Email Sent Successfully!", 'j-data' ));
}
unset($_SESSION["msg"]);
?>

                <form method="get">
                    <input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>" />
                            <?php                                
                                $gain_obj->prepare_items();
                                $gain_obj->search_box('Search', 'search');
                                $gain_obj->display();
                            ?>
                </form>

<style>    
.popup-container {    
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.5);
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-align: center;
        -ms-flex-align: center;
            align-items: center;
    -webkit-box-pack: center;
        -ms-flex-pack: center;
            justify-content: center;
    opacity: 0;
    z-index: -1000;
    -webkit-transition:all 0.3s ease-in-out 0.2s;
    -o-transition:all 0.3s ease-in-out 0.2s;
    transition:all 0.3s ease-in-out 0.2s;
}
.popup-container.open-pop{
    opacity: 1;
    z-index: 50000;
    -webkit-transition:all 0.3s ease-in-out;
    -o-transition:all 0.3s ease-in-out;
    transition:all 0.3s ease-in-out;    
}

.popup-content { 
    
    background-color: #fff;    
    width: 50%;
    -webkit-transform:scale(0,0);
        -ms-transform:scale(0,0);
            transform:scale(0,0);    
    -webkit-transition:-webkit-transform 0.3s ease-in-out;    
    transition:-webkit-transform 0.3s ease-in-out;    
    -o-transition:transform 0.3s ease-in-out;    
    transition:transform 0.3s ease-in-out;    
    transition:transform 0.3s ease-in-out, -webkit-transform 0.3s ease-in-out;
    max-height:85vh;
}
.popup-container.open-pop .popup-content
{
    -webkit-transform:scale(1,1);
        -ms-transform:scale(1,1);
            transform:scale(1,1);
    -webkit-transition:-webkit-transform 0.3s ease-in-out 0.1s;
    transition:-webkit-transform 0.3s ease-in-out 0.1s;
    -o-transition:transform 0.3s ease-in-out 0.1s;
    transition:transform 0.3s ease-in-out 0.1s;
    transition:transform 0.3s ease-in-out 0.1s, -webkit-transform 0.3s ease-in-out 0.1s;
    overflow-y:auto;
}
.popup-content > form > *
{
    padding:20px;
}
.popup-container header{
    border-bottom:1px solid #e1e1e1;
}
.popup-container header h3
{
    padding:0;
    margin:0;
}
.popup-container footer
{
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-pack: justify;
        -ms-flex-pack: justify;
            justify-content: space-between;
    border-top:1px solid #e1e1e1;
}
.popup-container footer p.submit
{
    margin:0;
    padding:0;
}
@media (max-width:767px)
{
    .popup-content {
        width:90%;
        margin:30px 0;
    }    
}
</style>
<div class="popup-container">
    <div class="popup-content">
        <form method="post">
            <header>
                <h3>Send Email</h3>
            </header>

            <div class="popup-middle-container">
                <div class="keywords">
                    Use below Keywords in pdf body:
                    <p>%%user_name%%</p> 
                    <p>%%date%%</p>
                    <p>%%gain_value%%</p>
                    <p>%%cumulative_value%%</p>
                </div>
                <div class="textarea-container">
                    <?php wp_editor($get_clj_settings['weekly_notification_body'],"send_notifaction" ,array( "required"=>"required",'editor_height' => 200,'textarea_rows' => 15 , 'wpautop' => true));?>
                </div>
                <input type="hidden" name="primary_id" val="">
            </div>
        
            <footer>
                <?php submit_button("Send Email" , "primary" , "send_email_users");?>
                <a class="button-primary" href="<?php echo admin_url('admin.php'); ?>?page=test_push_notification"><?php echo __("send a notification", FCMDPPLGPN_TRANSLATION);?></a>
                <a href="javascript:void(0);" class="close-popup button-primary">Close</a>
            </footer>
        </form>
    </div>
</div>
<script>
    "use strict";

(function ($) {
  $(document).ready(function () {
    var pid;
    $(".send-week-notification").on("click", function () {
      pid = $(this).data("val");

      if (pid > 0) {
        $("input[name='primary_id']").val(pid);
        $(".popup-container").addClass("open-pop");
      }
    });
    $('.popup-container').on("click", function (e) {
      return $(e.target).hasClass("close-popup") ? $('.popup-container').removeClass("open-pop") : "";
    }); //$("").removeClass("open-pop");
  });
})(jQuery);

</script>
<?php
}
?>
</div>
