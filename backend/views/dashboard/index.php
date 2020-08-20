<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use lo\widgets\modal\ModalAjax;
use yii\widgets\Pjax;
use app\models\Reports; 



?>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="https://code.highcharts.com/highcharts.src.js"></script>
 <script src="http://code.highcharts.com/modules/exporting.js"></script>
 <script src="https://code.highcharts.com/highcharts-3d.js"></script>


<div class="content-header">
<div class = "header-section">
	<h1>
		<i class="gi gi-pie_chart"></i>
		Dashboard 
	</h1>
</div>
</div>


    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
<?php Pjax::begin([

  ]);
?>
<div class="row text-center align center" style="margin-left:20px ">
                            <div class="col-sm-2 ">
                              
                                <a href="javascript:void(0)" class="widget widget-hover-effect2">
                                    <div class="widget-extra themed-background-success">
                                    
                                        <h5 class="widget-content-light"><strong><b>Active</strong> Jobs</b></h5>
                                        
                                    </div>
                                    <div class="widget-extra-full"><span class="h2 animation-expandOpen" id="activejobs"></span></div>
                                </a>
                            </div>

                             <div class="col-sm-3">
                                <a href="javascript:void(0)" class="widget widget-hover-effect2">
                                    <div class="widget-extra themed-background-warning">
                                    	<h5 class="widget-content-light"><strong><b>Completed </strong> Jobs</b></h5>
                                        
                                    </div>
                                    <div class="widget-extra-full"><span class="h2 animation-expandOpen" id="completejobs"></span></div>
                                </a>
                            </div>
                             <div class="col-sm-3">
                                <a href="javascript:void(0)" class="widget widget-hover-effect2">
                                    <div class="widget-extra themed-background-warning">
                                    
                                        <h5 class="widget-content-light"><strong><b>Canceld </strong> Jobs</b></h5>
                                        
                                    </div>
                                    <div class="widget-extra-full"><span class="h2 animation-expandOpen" id="cancledjobs"></span></div>
                                </a>
                            </div>
                             <div class="col-sm-3">
                                <a href="javascript:void(0)" class="widget widget-hover-effect2">
                                    <div class="widget-extra themed-background-warning">
                                    
                                        <h5 class="widget-content-light"><strong><b>Total</strong> Sales</b></h5>
                                        
                                    </div>
                                    <div class="widget-extra-full"><span class="h2 animation-expandOpen" id="sales"></span></div>
                                </a>
                            </div>
                          </div>


<div class="block full">
  <div class="block-title">
  <h5>
    <strong>Previous Month Sales</strong>
</h5>
</div>

<div class="highcharts-container " >
  <div id="line-chart"></div>
  </div>
</div>


<?php

Pjax::end(); ?>
<script type="text/javascript">
$(document).ready(function(){
 $('#line-chart').show();
             //var options, chart;  
             //create a variable so we can pass the value dynamically
  var chartype = 'line';

  //On page load call the function setDynamicChart
  setDynamicChart(chartype);

  //jQuery part - On Click call the function setDynamicChart(dynval) and pass the chart type
  $('#change').change(function(){
    //get the value from 'a' tag
    var chartype = $(this).val();
    setDynamicChart(chartype);
  });

             function setDynamicChart(chartype){ 
             $.getJSON("/dashboard/graph", function(json) {
                json_data = json;
                    chart = new Highcharts.Chart({
                        chart: {
                            renderTo: 'line-chart',
                            type: chartype
                            
                        },
                        title: {
                            text: 'Sales For the Month'
                            
                        },
                        yAxis: {
                          title: {
                              text: 'Amount in Ksh'
                          }
                      },
                       

                      
                       xAxis: {
                          type: 'datetime',
                          crosshair: true,
                       title: {
                              text: 'Date'
                          },
                    },

                                         
                        tooltip: {
                            formatter: function() {
                                    return '<b>'+ this.series.name +'</b><br/>'+
                                    Highcharts.dateFormat('%e - %b - %Y',
                                          new Date(this.x))
                                     + ' date, ' + this.y + ' Ksh.';
                            }
                        },
                        legend: {
                            layout: 'vertical',
                            align: 'right',
                            verticalAlign: 'top',
                            x: -10,
                            y: 120,
                            borderWidth: 0
                        },
                        series: json
                    });
                });


     }  
       
       
         
                          

  });


$(document).ready(function(){
    $.ajax({
      url: '/dashboard/activejobs',
      type:'get',
      success: function(data){
        $('#activejobs').html(data);
      }
    })
})
$(document).ready(function(){
    $.ajax({
      url: '/dashboard/completejobs',
      type:'get',
      success: function(data){
        $('#completejobs').html(data);
      }
    })
})
$(document).ready(function(){
    $.ajax({
      url: '/dashboard/sales',
      type:'get',
      success: function(data){
        $('#sales').html(data);
      }
    })
})
$(document).ready(function(){
    $.ajax({
      url: '/dashboard/cancledjobs',
      type:'get',
      success: function(data){
        $('#cancledjobs').html(data);
      }
    })
})


</script>