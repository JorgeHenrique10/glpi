<?php

$status = "(5,6)";

$query2 = "
SELECT COUNT(glpi_tickets.id) as tick, glpi_tickets.status as stat, glpi_status_time.name as nome
FROM glpi_tickets
INNER JOIN glpi_status_time on (glpi_status_time.cod_status = glpi_tickets.status)
WHERE glpi_tickets.is_deleted = 0 
AND glpi_tickets.status NOT IN " . $status . "  
" . $entidade . "
AND DATE_FORMAT( date, '%Y' ) IN (" . $years . ")      
GROUP BY glpi_tickets.status
ORDER BY ordenador  ASC ";


$result2 = $DB->query($query2) or die('erro');

$arr_grf2 = array();
$arr_grf2_cod = array();

while ($row_result = $DB->fetch_assoc($result2)) {
    $v_row_result = $row_result['nome'];
    $arr_grf2[$v_row_result] = $row_result['tick'];
    $arr_grf2_cod[] = $row_result['stat'];

}

$grf2 = array_keys($arr_grf2);
$quant2 = array_values($arr_grf2);
$conta = count($arr_grf2);
$categorias = implode("','", $grf2);

// print_r($arr_grf2_cod);exit;

echo "
<script type='text/javascript'>

$(function () {		
    	   		
		// Build the chart
        $('#graf_media_status').highcharts({
            chart: {
                type: 'column',
			    height: 330,
                plotBorderColor: '#ffffff',
            	plotBorderWidth: 0
            },
            title: {                
                text: ''                
            },
             legend: {     
                   layout: 'horizontal',
	            align: 'left',
	            x: 5555555555555555,
	            y: -15,
	            verticalAlign: 'top',
	            floating: true,
               adjustChartSize: true,
	            borderWidth: 0             
            },
            xAxis :{
            categories: ['" . $categorias . "'],
            },
            credits: {
                enabled: false
            },
            tooltip: {
        	    pointFormat: '{series.name}: <b>{point.y} </b>'
            },
            yAxis:{
            min: 0,
            title:{
                text: '', 
                align: 'middle'
            },
            labels: {
                overflow: 'justify'
            },
            stackLabels: {
            enabled: true,
            y:-15,
            }
                    },
            plotOptions: {
                series: {
                    cursor: 'pointer',
                        colorByPoint: true, 
                    point: {
                           events: {
                               click: function () {
                                   window.open(this.options.url);
                                   //location.href = this.options.url;
                               }
                           }
                       }
                   }
                  
            },
            series: [{
                            name: '" . __('Tickets', 'dashboard') . "',
                data: [
                    {
                        name: '" . Ticket::getStatus($grf2[0]) . "',
                        y: " . $quant2[0] . ",   
                        url:'reports/rel_tickets.php?con=1&stat=" . $arr_grf2_cod[0] . "',                                    
                        selected: false,
                        
                    },";

for ($i = 1; $i < $conta; $i++) {
    echo '{ 
            name: "' . Ticket::getStatus($grf2[$i]) . '",
            y: ' . $quant2[$i] . ', 
            url:"reports/rel_tickets.php?con=1&stat='. $arr_grf2_cod[$i]. '" 
        }, ';
}

echo " ],
                dataLabels: {
                    enabled: true,
                    style: {
                        fontSize: '10px',
                        fontFamily: 'Roboto, sans-serif'
                    }
                }
            
            
            }]
        });
    });

		</script>";
