<?php 
//Variables to use
//$only_years
//$all_yearwise_entries

$newar = array();
foreach ($all_yearwise_entries as $key => $value) {
  $newar = array_merge($newar , $value);
}
?>

<div id="tabs-gain-deposite" class="youtimizer-tab-content" style="opacity:0;">
  <ul>
    <?php if(!empty($only_years))
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
            printf('<li><a href="%1$s">%2$s %4$s</a><span>%3$s</span></li>' , "#y_".$value ,$value,$total_value,__("Gain" , "j-data"));
          }
    }?>    
  </ul>

  <?php if(!empty($only_years))
    {
      $table_heading = sprintf('<table class="youtimizer-data-table"><thead><tr><th>%s</th><th>%s</th><th>%s</th><th>%s</th></tr></thead><tbody>' , esc_html__( 'Date', 'j-data' ),esc_html__( 'Post', 'j-data' ),esc_html__( 'Amount', 'j-data' ),esc_html__( 'Account', 'j-data' ));

          foreach ($only_years as $key => $value) {
            ?>
                <div id="<?php echo "y_".$value;?>">
                  <?php 
                      $current_year_data  =  $all_yearwise_entries[$value];

                      if(!empty($current_year_data))
                      {
                        echo $table_heading;
                    
                        foreach ($current_year_data as $key => $value) {

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

                          printf('<tr><td>%1$s</td><td>%2$s</td><td>%3$s</td><td>%4$s</td></tr>' , $value["date"], $value["text"], $amount_format_value, $account_format_value);
                          }
                        echo "</tbody></table>";
                      }

                  ?>
                </div>
            <?php
          } 
    }?>
</div>

<div class="refresh-data-click">
    <a href="javascript:void(0);" class='site-btn'>
        <?php esc_html_e('Update Data','j-data'); ?>
    </a>
</div>
<script>
  jQuery(document).ready(function(){
    jQuery( "#tabs-gain-deposite" ).tabs().addClass( "ui-tabs-vertical ui-helper-clearfix" );
    jQuery( "#tabs-gain-deposite li" ).removeClass( "ui-corner-top" ).addClass( "ui-corner-left" );
  });
  jQuery(window).load(function(){
    jQuery("#tabs-gain-deposite").css("opacity","1");
  });
  jQuery(".refresh-data-click a").on("click" , function(e){
    e.preventDefault();
    window.location.href = "?updateDb=true";
  });
  </script>