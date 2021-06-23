<?php

$querydays = "
SELECT count(glpi_tickets.id) AS chamados , DATEDIFF( glpi_tickets.solvedate, glpi_tickets.date ) AS days
FROM glpi_tickets, glpi_tickets_users
WHERE glpi_tickets.solvedate IS NOT NULL
AND glpi_tickets.is_deleted = 0
AND glpi_tickets.id = glpi_tickets_users.tickets_id
AND glpi_tickets_users.type = 2
GROUP BY days ";
		
$resultdays = $DB->query($querydays) or die('erro');

$arr_keys = array();
$arr_days = array();

while ($row_result = $DB->fetch_assoc($resultdays)) { 
	$v_row_result = $row_result['days'];
	$arr_days[$v_row_result] = 0;						
}

$conta = count($arr_days);

if( $conta < 9) {
	for($i=$conta; $i < 9; $i++) {		
		$arr_days[$i] = 0;			
	}	
}	


$query2 = "
SELECT count(glpi_tickets.id) AS chamados , DATEDIFF( glpi_tickets.solvedate, glpi_tickets.date ) AS days
FROM glpi_tickets, glpi_tickets_users
WHERE glpi_tickets.solvedate IS NOT NULL
AND glpi_tickets.is_deleted = 0
AND glpi_tickets.id = glpi_tickets_users.tickets_id
AND glpi_tickets_users.type = 2
AND glpi_tickets_users.users_id = ".$id_tec."
AND glpi_tickets.date ".$datas."
".$entidade_age."
GROUP BY days ";
		
$result2 = $DB->query($query2) or die('erro');

while ($row_result = $DB->fetch_assoc($result2)){ 	
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
        $('#graf_time1').highcharts({
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
             
                showInLegend: true
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
    //<1 = [0] - 1 [1]
