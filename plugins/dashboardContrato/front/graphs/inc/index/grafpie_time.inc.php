<?php

$querydays = "
SELECT count( id ) AS chamados , DATEDIFF( solvedate, date ) AS days
FROM glpi_tickets
WHERE solvedate IS NOT NULL
AND is_deleted = 0
GROUP BY days ";
		
$resultdays = $DB->query($querydays) or die('erro');

$arr_keys = array();
$arr_days = array();

while ($row_result = $DB->fetch_assoc($resultdays)) { 
	$v_row_result = $row_result['days'];
	$arr_days[$v_row_result] = 0;						
}

$conta = count($arr_days);

if( $conta < 7) {
	for($i=$conta; $i < 7; $i++) {		
		$arr_days[$i] = 0;			
	}	
}

$query2 = "
SELECT count( id ) AS chamados , DATEDIFF( solvedate, date ) AS days
FROM glpi_tickets
WHERE solvedate IS NOT NULL
AND is_deleted = 0
AND DATE_FORMAT( date, '%Y' ) IN (".$years.")     
".$entidade."
GROUP BY days ";

$result2 = $DB->query($query2) or die('erro');

$arr_keys = array();

while ($row_result = $DB->fetch_assoc($result2)) {
	$v_row_result = $row_result['days'];
	$arr_keys[$v_row_result] = $row_result['chamados'];
}

$arr_tick = array_merge($arr_keys,$arr_days);
	
$days = array_keys($arr_tick);
$keys = array_keys($arr_tick);

$arr_more8 = array_slice($arr_keys,8);
$more8 = array_sum($arr_more8);

$quant2 = array_values($arr_tick);

array_push($quant2,$more8);

$conta_q = count($quant2)-1;

echo "
<script type='text/javascript'>

$(function () {		
    	   		
		// Build the chart
        $('#graf9').highcharts({
            chart: {
            type: 'column',
            plotBorderColor: '#ffffff',
            plotBorderWidth: 0
            },              
            title: {
                text: 'Tempo de Solução dos Chamados'
            },

            tooltip: {
        	    pointFormat: '{series.name}: <b>{point.y}</b>'
            },
            plotOptions: {    
                showInLegend: true,        
                series: {
                groupPadding: 0.08
                }
            },

            credits: {
                enabled: false
            },
            xAxis:{
                min:1,
                categories:['','']

            },
            labels: {
                overflow: 'justify'
            },
            stackLabels: {
            enabled: true,
            y:0,
            },
            yAxis:{
                title:{
                    text:''
                }             
            },
            series: [                
                {     
                    name: '< 1 Dia',
                    data: ['< 1 " .__('day','dashboard')."',  ".$quant2[0]."],
                    dataLabels: {
                        enabled: true,
                        style: {
                            fontSize: '10px',
                            fontFamily: 'Roboto, sans-serif'
                        }
                    }
                },
                {
                    name: '1 Dia',
                    data: ['1 " .__('days','dashboard')."',  ".$quant2[1]." ],
                    color: 'cyan',
                    dataLabels: {
                        enabled: true,
                        style: {
                            fontSize: '10px',
                            fontFamily: 'Roboto, sans-serif'
                        }
                    }
                },
                {
                    name: '2 Dias',
                    data: ['2 " .__('days','dashboard')."', ".$quant2[2]." ],
                    dataLabels: {
                        enabled: true,
                        style: {
                            fontSize: '10px',
                            fontFamily: 'Roboto, sans-serif'
                        }
                    }
                },
                {
                    name: '3 Dias',
                    data: ['3 " .__('days','dashboard')."', ".$quant2[3]." ],
                    dataLabels: {
                        enabled: true,
                        style: {
                            fontSize: '10px',
                            fontFamily: 'Roboto, sans-serif'
                        }
                    }
                },
                {
                    name: '4 Dias',
                    data: ['4 " .__('days','dashboard')."', ".$quant2[4]." ],
                    dataLabels: {
                        enabled: true,
                        style: {
                            fontSize: '10px',
                            fontFamily: 'Roboto, sans-serif'
                        }
                    }
                },
                {
                    name: '5 Dias',
                    data: ['5 " .__('days','dashboard')."', ".$quant2[5]." ],
                    dataLabels: {
                        enabled: true,
                        style: {
                            fontSize: '10px',
                            fontFamily: 'Roboto, sans-serif'
                        }
                    }
                },
                {
                    name: '6 Dias',
                    data: ['6 " .__('days','dashboard')."', ".$quant2[6]." ],
                    dataLabels: {
                        enabled: true,
                        style: {
                            fontSize: '10px',
                            fontFamily: 'Roboto, sans-serif'
                        }
                    }
                },
                {
                    name: '7 Dias',
                    data: ['7 " .__('days','dashboard')."', ".$quant2[7]." ],
                    dataLabels: {
                        enabled: true,
                        style: {
                            fontSize: '10px',
                            fontFamily: 'Roboto, sans-serif'
                        }
                    }
                },
                {
                    name: '8 Dias',
                    data: ['8+" .__('days','dashboard')."', ".$quant2[$conta_q]." ],
                    dataLabels: {
                        enabled: true,
                        style: {
                            fontSize: '10px',
                            fontFamily: 'Roboto, sans-serif'
                        }
                    }
                }
            ]
        });
    });
		</script>"; 
		?>
