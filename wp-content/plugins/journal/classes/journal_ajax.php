<?php 
defined( 'ABSPATH' ) || exit;
class JournalAjaxRequests
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
        add_action( "wp_ajax_nopriv_j_update_user_profile", array($this , "j_update_user_profile") );
        add_action( "wp_ajax_j_update_user_profile", array($this , "j_update_user_profile") );        
    }
    public function j_update_user_profile()
    {
        if ( ! check_ajax_referer( 'j-up-profile', 'security', false ) ) {
            wp_send_json_error( 'Invalid security token sent.' , 403);
            wp_die();
          }
       
          $user_id = get_current_user_id();
          if($user_id > 0)
          {
            $email = $_POST['user_email_value'];
            $weekly_mail = isset($_POST['weekly_mail']) ? 1 : 0;;
            $weekly_push_notification = isset($_POST['weekly_push_notification']) ? 1 : 0;;
            $monthly_mail = isset($_POST['monthly_mail']) ? 1 : 0;
            $user_data = wp_update_user( array( 'ID' => $user_id, 'user_email' => $email ) );
            
            update_user_meta( $user_id, "weekly_mail", $weekly_mail );
            update_user_meta( $user_id, "weekly_push_notification", $weekly_push_notification );
            update_user_meta( $user_id, "monthly_mail", $monthly_mail );
            echo wp_send_json(array("success"=> true , "msg" => "Updated Successfully."));
        }
        wp_die();
    }
}
?>