<?php
defined('ABSPATH') || exit;

if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}
class JournalYearsList extends WP_List_Table
{
    /** Class constructor */
    public function __construct()
    {
        parent::__construct([
            'singular' => __('Year', 'j-data'), //singular name of the listed records
            'plural'   => __('Years', 'j-data'), //plural name of the listed records
            'ajax'     => false //does this table support ajax?
        ]);                
    }
    public function get_redirect_url()
    {
        return admin_url("admin.php?page=j-years");
    }
    public static function get_database_table()
    {
        global $wpdb;
        return $wpdb->prefix.'j_years';
    }

    public function get_weekly_url()
    {
        return admin_url("admin.php?page=j-weekly-gain");
    }
    /**
     * Retrieve customers data from the database
     *
     * @param int $per_page
     * @param int $page_number
     *
     * @return mixed
     */
    public static function get_years($per_page = 5, $page_number = 1)
    {
       global $wpdb;
        $year_table =  self::get_database_table();
        $sql = "SELECT * FROM $year_table";

        if (isset($_REQUEST["s"])) {
            $sql .= " where yname LIKE '%" . $_REQUEST["s"] . "%' ";
        }

        if (!empty($_REQUEST['orderby'])) {
            $sql .= ' ORDER BY ' . esc_sql($_REQUEST['orderby']);
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

        $year_table =  self::get_database_table();

        $wpdb->delete(
            $year_table,
            ['yid' => $id],
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

        $year_table =  self::get_database_table();

        $sql = "SELECT COUNT(*) FROM $year_table";

        if (isset($_REQUEST["s"])) {
            $sql .= " where yname LIKE '%" . $_REQUEST["s"] . "%' ";
        }

        return $wpdb->get_var($sql);
    }


    /** Text displayed when no customer data is available */
    public function no_items()
    {
        _e('No Years avaliable.', 'j-data');
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
            case 'cname':
            case 'caddress':
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
    function column_yname($item)
    {
        $title = '<strong>' . $item['yname'] . '</strong>';
        return $title;
    }

    function column_action($item)
    {
        $nonce = wp_create_nonce('edit-delete-year');

        return sprintf("<a href='%1\$s'>Edit</a> | <a href='%2\$s' onclick='%3\$s'>Delete</a>", $this->get_redirect_url()."&action=update_year&id=" . $item["yid"], $this->get_redirect_url()."&action=delete&id=" . $item["yid"] . "&cnonce=" . $nonce , 'return confirm("Want to delete?");');
    }

    function column_viewentries($item)
    {
        //return $item['yname'];
        return sprintf('<a href="%1$s">%2$s</a>' ,$this->get_weekly_url()."&s=".$item["yname"] ,__("View Gain Entry for ".$item["yname"] , "j-data"));
    }
    function column_addentry($item)
    {
        return sprintf('<a href="%1$s">%2$s</a>' ,$this->get_weekly_url()."&action=add_gain&yid=".$item["yid"] ,__("Add Gain Entry for ". $item["yname"] , "j-data"));
    }

    /**
     *  Associative array of columns
     *
     * @return array
     */
    function get_columns()
    {
        $columns = array(
            'cb'      => '<input type="checkbox" />',
            'yname'    => __('Year', 'j-data'),
            'viewentries' => __('View Gain Notification', 'j-data'),
            'addentry' => __('Add Gain Notification', 'j-data'),
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
            'yname' => array('yname', true)
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

        $this->items = self::get_years($per_page, $current_page);
    }

    public function process_bulk_action()
    {
        //Detect when a bulk action is being triggered...

        if ('delete' === $this->current_action()) {

            // In our file that handles the request, verify the nonce.
            $nonce = esc_attr($_REQUEST['cnonce']);

            if (!wp_verify_nonce($nonce, "edit-delete-year")) {
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
    global $wpdb;
    $years_obj = new JournalYearsList();
    $tbl_j_years = $years_obj::get_database_table();

//Start session
    //session_start();
//Saving values inside database.

if((isset($_POST["add_year"]) && $_POST["add_year"] == "Add Year" ) || (isset($_POST["update_year"]) && $_POST["update_year"] == "Update Year"))
    {
        if (!filter_input(INPUT_POST, "journal_year", FILTER_VALIDATE_INT)) {
            die("Invalid values");
        }
        $year = sanitize_text_field($_POST["journal_year"]);

        $error = true;
        if($_POST["add_year"])
        {
            $wpdb->insert($tbl_j_years , array("yname" => $year ));
            if($wpdb->insert_id > 0)
            {
                $error = false;
                $msg= "added";
            }            
        }
        else if($_POST["update_year"])
        {
            $wpdb->update($tbl_j_years , array("yname" => $year ),array("yid" => $_GET['id']));
            $error = false;
            $msg= "updated";
        }
        
        if(!$error)
        {            
            $_SESSION["msg"] = $msg;
            wp_redirect($years_obj->get_redirect_url());die();
        }
        else
        {
            die("Error....");
        }
    }


//Show data in UI.
    
if(isset($_GET["action"]) && ($_GET["action"] == "add_year" || $_GET["action"] == "update_year"))
{
    $res = array();
    if($_GET["action"] == "update_year")
    {
        //Get record from database
        $res = $wpdb->get_row("SELECT * from $tbl_j_years where yid = {$_GET['id']}" , ARRAY_A);        
    }
    
    $button_name = $_GET["action"] == "add_year" ? "add_year" : "update_year";
    $button_value = $_GET["action"] == "add_year" ? "Add Year" : "Update Year";

    $year_val = isset($res['yname']) ? $res['yname'] : "";
    ?>

    <h1 id="add-new-user"><?php echo $button_label; ?></h1>

    <form method="post" id="createuser">
        <table class="form-table">
        <tr class="form-field form-required">
		    <th scope="row">
                <label for="journal_year">Enter Year<span class="description">(required)</span></label>
            </th>
		    <td>
                <input oninvalid="this.setCustomValidity('Year should have 4 digits.')" onchange="this.setCustomValidity('')"  name="journal_year" pattern="[0-9]{4}" type="text" id="journal_year" value="<?php echo $year_val;?>" required>
            </td>
	    </tr>
        </table>
        <?php submit_button($button_value,'primary',$button_name);?>
    </form>

    <?php
}
else
{
?>
                <h1 class="wp-heading-inline"><?php esc_html_e( "All Records", 'j-data' );?></h1>

                <a href="<?php echo esc_url( $years_obj->get_redirect_url() ."&action=add_year")?>" class="page-title-action"><?php esc_html_e( "Add New Year", "j-data" );?></a>


<?php
if(isset($_SESSION["msg"]) && $_SESSION["msg"]=="updated")
{
    printf('<div class="updated notice"><p> %s</p></div>' , esc_html__( "Year Updated Successfully!", 'j-data' ));
}
if(isset($_SESSION["msg"]) && $_SESSION["msg"]=="deleted")
{
    printf('<div class="updated notice"><p> %s</p></div>' , esc_html__( "Year Deleted Successfully!", 'j-data' ));
}
if(isset($_SESSION["msg"]) && $_SESSION["msg"]=="added")
{
    printf('<div class="updated notice"><p> %s</p></div>' , esc_html__( "Year Added Successfully!", 'j-data' ));
}
unset($_SESSION["msg"]);
?>

                <form method="get">
                    <input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>" />
                            <?php                                
                                $years_obj->prepare_items();
                                $years_obj->search_box('Search', 'search');
                                $years_obj->display();
                            ?>
                </form>
<?php
}
?>
</div>