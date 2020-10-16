<?php 
//$get_all_years
//$get_all_weekly_data
//$get_all_table_data
//$weekly_year_wise_data
//$get_all_table_year_wise_data

$newar = array();

// foreach ($all_yearwise_entries as $key => $value) {
//   $newar = array_merge($newar , $value);
// }


$get_all_years = array_column($get_all_years , "yname");
$get_all_table_data_total_gain = array_column($get_all_table_data , "clr_gain_percentage");
?>

<div id="tabs-gain-deposite" class="youtimizer-tab-content" style="opacity:0;">
  <ul>
    <?php if(!empty($get_all_years))
    {   
        $month = date('m');
		$dateObj   = DateTime::createFromFormat('!m', $month);
        $monthStr = $dateObj->format('F');

        $current_month_percent = array_filter($get_all_weekly_data, function ($var) {
            return ( date('Y', strtotime($var["wdate"])) == $month);
        });
        $current_month_percent = array_sum($current_month_percent);
        $current_month_percent = number_format($current_month_percent, 2);

        $last_date = $get_all_weekly_data[0]['wdate'];

        $date = strtotime(date("Y-m-d") .' -1 year');
        $date = date('Y-m-d', $date); 
        $gain_data = array_filter($get_all_table_data, function ($var) use($date) {
            return ( strtotime($var["clr_close_date"]) >= strtotime($date));
        });
        $gain_data = array_column($gain_data,"clr_gain_percentage");
        $gain_data = array_sum($gain_data) + $current_month_percent;
        $gain_data = number_format($gain_data, 2);
        
        printf('<div style="
                    background-color: #e86c00;
                    color: white;
                    text-align: center;
                ">
                <p style="padding: 5px;margin: 0;font-size:20px;">%1$s %2$s</p>
                <p style="padding-top: 5px;margin: 0;"><span style="font-size:40px;">%3$s</span><span>%4$s</span></p>
                <p style="padding-bottom: 5px;margin: 0;font-size:14px;">%5$s %6$s</p>
                <p style="padding-top: 5px;margin: 0;font-size:18px;">%7$s</p>
                <p style="padding-bottom: 10px;margin: 0;"><span style="font-size:24px;">%8$s</span><span>%4$s</span></p>
            </div>', 
            __("ROI in" , "j-data") , $monthStr, $current_month_percent, __("%" , "j-data"), __("Last Updated" , "j-data"), $last_date, __("ROI last 12 Months" , "j-data"), $gain_data);
       
          foreach ($get_all_years as $key => $value) {
            $chart_dates = array_filter($get_all_table_data, function ($var) use($value) {
                return ( date('Y', strtotime($var["clr_close_date"])) == $value);
            });
            $chart_year_dates = array_reverse(array_column($chart_dates , "clr_close_date"));
            $chart_year_percentage = array_reverse(array_column($chart_dates , "clr_gain_percentage"));
            $total_value = array_sum($chart_year_percentage);

            printf('<li><a href="%1$s">%2$s %4$s</a><span>%3$s</span></li>' , "#y_".$value ,$value,$total_value,__("Performance" , "j-data"));
          }
    }?>    
  </ul>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>

  <?php if(!empty($get_all_years))
    {      
          foreach ($get_all_years as $key => $value) {
            ?>
                <div id="<?php echo "y_".$value;?>">
                
                  <?php
                      $current_year_table_data  =  $get_all_table_year_wise_data[$value];
                      $current_year_week_data = $weekly_year_wise_data[$value];

                      if(!empty($current_year_table_data) && !empty($current_year_week_data))
                      {
                        //Chart draw here now.....
                        $all_current_year_week_dates = array_column($current_year_week_data,"wdate");
                        $all_current_year_week_percentage = array_column($current_year_week_data,"wgain");

                        $chart_dates = array_filter($get_all_table_data, function ($var) use($value) {
                            return ( date('Y', strtotime($var["clr_close_date"])) == $value);
                        });
                        $chart_year_dates = array_reverse(array_column($chart_dates , "clr_close_date"));
                        $chart_year_percentage = array_reverse(array_column($chart_dates , "clr_gain_percentage"));
                    ?>

<!-- style="position: relative;width:100%;height:300px;"-->
            <div class="chart-container">
                 <canvas id="myChart<?php echo "y_".$value;?>" style="width:100%;height:300px;"></canvas>
            </div>

            <script>
            var ctx = document.getElementById('myChart<?php echo "y_".$value;?>').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: <?php echo json_encode($chart_year_dates)?>,
                    datasets: [{
                        pointStyle:"circle",
                        label: 'Gain Percentage',
                        data: <?php echo json_encode($chart_year_percentage)?>,
                        borderColor: "#e86c00",
                        backgroundColor: '#e86c00',
                        borderWidth: 3,
                        fill: false
                    }]
                },

                options: {
                        onResize:function(){
                        //console.log("Chart Resized");
                    },
                    elements: {
                        // point:{pointStyle:"triangle", backgroundColor:"#e86c00" , borderWidth:3},
                        line: {
                            tension: 0.000001,
                            // backgroundColor: '#e86c00',
                            // borderColor: "#e86c00",
                            // pointStyle:"circle",
                            // borderColor:"#e86c00"
                        },
                        
                    },
                    legend: {
                        display: false,
                        labels: {
                            fontColor: '#999'
                        }
                    },
                    tooltips: {
                        // backgroundColor:'red',
                        // cornerRadius:0
                    },
                    scales: {                                
                                xAxes: [{
                                    scaleLabel:{
                                        fontColor:"#fff",
                                        labelString: '',
                                        display: true
                                        
                                    },
                                    ticks: {
                                        fontColor:"#fff",
                                        callback:function(el){
                                            return el ; 
                                        }
                                    }
                                }],
                                yAxes: [{
                                    scaleLabel:{
                                        fontColor:"#fff",
                                        labelString: '',
                                        display: true
                                        
                                    },
                                    ticks: {
                                        beginAtZero: true,
                                        fontColor:"#fff",  
                                        //stepSize: 0.5,
                                        callback: function(value, index, values) {
                                                    return `${value} %`;
                                        }                                    
                                    }
                                }]
                            }
                }
            });            
            </script>
                        <?php
                        // $twelve_month = array_sum(array_column($current_year_table_data,'clr_gain_percentage'));
                        
                        // printf('<div class="clud-performance-container"><div class="past-twelve-months"><div>%s</div><div>%s</div></div><table class="youtimizer-data-table performance-page"><thead><tr><th>%s</th><th>%s</th><th>%s</th></tr></thead><tbody>' ,esc_html__( 'Gain Percent Last 12 Months', 'j-data' ) , $twelve_month,esc_html__( 'Date', 'j-data' ),esc_html__( 'Amount', 'j-data' ),esc_html__( 'Account', 'j-data' ));                        
                        printf('<div class="clud-performance-container" style="padding-top: 10px"><table class="youtimizer-data-table performance-page"><thead><tr><th>%s</th><th>%s</th><th>%s</th></tr></thead><tbody>' ,esc_html__( 'Date', 'j-data' ),esc_html__( 'Amount', 'j-data' ),esc_html__( 'Account', 'j-data' ));                        
                                
                        $cumulated_sum = array_sum(array_column($current_year_table_data, "clr_gain_percentage"));
                        
                        foreach ($current_year_table_data as $key => $value) {
                            if ($key != 0)  $cumulated_sum -=  $current_year_table_data[$key - 1]["clr_gain_percentage"] ;                        

                            printf('<tr><td>%1$s</td><td>%2$s</td><td>%3$s</td></tr>' , $value["clr_close_date"], $value['clr_gain_percentage'], $cumulated_sum);

                          }
                        
                           echo "</tbody></table></div>";
                      }

                  ?>
                </div>
            <?php
          } 
    }?>
</div>

<script>
  jQuery(document).ready(function(){
    jQuery( "#tabs-gain-deposite" ).tabs().addClass( "ui-tabs-vertical ui-helper-clearfix" );
    jQuery( "#tabs-gain-deposite li" ).removeClass( "ui-corner-top" ).addClass( "ui-corner-left" );
  });
  jQuery(window).load(function(){
    jQuery("#tabs-gain-deposite").css("opacity","1");
  });  
  </script>