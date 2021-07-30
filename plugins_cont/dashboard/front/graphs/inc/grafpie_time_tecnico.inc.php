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

$query_new = "
Select count(IF( days < 20, chamados, null )  ) AS menor20, count(IF( days >= 20 && days < 41, chamados, null )  ) AS maiorigual20 , count(IF( days >= 41, chamados, null )  ) AS maiorigual41,categoria
from 
(SELECT count( glpi_tickets.id ) AS chamados , DATEDIFF( solvedate, date ) AS days, itilcategories_id as categoria
FROM glpi_tickets, glpi_tickets_users
WHERE solvedate IS NOT NULL AND is_deleted = 0
AND glpi_tickets_users.type = 2
AND glpi_tickets_users.users_id = ".$id_tec."
AND glpi_tickets.itilcategories_id IN (189,190,191,197) 
AND glpi_tickets.date ".$datas."  
".$entidade_age."
GROUP BY days, itilcategories_id) as tabela
group by categoria";

//print_r($query_new);exit;

$result_new = $DB->query($query_new) or die('erro');
$array_days = [];

while ($row_result_new = $DB->fetch_assoc($result_new)) 
{
    $array_days[$row_result_new['categoria']]['menor20'] = $row_result_new['menor20'];
    $array_days[$row_result_new['categoria']]['maiorigual20'] = $row_result_new['maiorigual20'];
    $array_days[$row_result_new['categoria']]['maiorigual41'] = $row_result_new['maiorigual41'];
}

echo "<script type='text/javascript'>

        $(function () {
                $('#graf_time1').highcharts({
                    chart: {
                        type: 'column',
                            height: 450,
                        plotBorderColor: '#ffffff',
                        plotBorderWidth: 0
                    },
                    title: {
                        text: '" .__('Open Tickets Age','dashboard')."'
                        //text: ''
                    },

                    xAxis: {
                        categories: [ '0-20', '21-40', '> 41' ],
                        labels: {
                            text: '',
                            align: 'center',
                            style: {
                                //fontSize: '11px',
                                //fontFamily: 'Verdana, sans-serif'
                                
                            },
                            overflow: 'justify'
                            },
        //                     crosshair:true,
                            title: {
                                    text: '" .__('days','dashboard')."',
                                align: 'middle'
                                    }
                            },
                    yAxis: {
                        min: 0,
                        title: {
                                text: '',
                            align: 'middle'
                        },
                        labels: {
                            overflow: 'justify'
                        },
                        stackLabels: {
                        enabled: true,
                        y:-15,
                        style: {
                            //fontWeight: 'bold',
                            //color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                        }
                    }
                    },

                tooltip: {
                    formatter: function () {
                        return '<b>' + this.x + '</b><br/>' +
                            this.series.name + ': ' + this.y + '<br/>' +
                            'Total: ' + this.point.stackTotal;
                    }
                },
                    legend: {
                        layout: 'horizontal',
                        align: 'left',
                        x: 20,
                        y: 30,
                        verticalAlign: 'top',
                        floating: true,
                    adjustChartSize: true,
                        borderWidth: 0	
                },
                credits: {
                    enabled: false
                    },
                plotOptions: {
                    column: {
                        stacking: 'normal',
                        dataLabels: {
                            enabled: true,
                            color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
                            style: {
                                textShadow: '0 0 3px black'
                            }
                        },
                        borderWidth: 2,
                            borderColor: '#fff',
                            shadow:true,
                            showInLegend: true,
                    },
                    series: {
                    cursor: '',
                        colorByPoint: false, 
                    point: {
                            events: {
                          
                            }
                        }
                    }
                
                    },
                    series: [
                        {
                        name: 'Aditivo',
                        data: [
                        {y:" . $array_days[189]["menor20"] ."},
                        {y:" . $array_days[189]["maiorigual20"] ."},
                        {y:" . $array_days[189]["maiorigual41"] ."}]},
                        {
                        name: 'Cotação',
                        data: [
                        {y:" . $array_days[190]["menor20"] ."},
                        {y:" . $array_days[190]["maiorigual20"] ."},
                        {y:" . $array_days[190]["maiorigual41"] ."}]},
                        {
                        name: 'Dispensa',
                        data: [
                        {y:" . $array_days[191]["menor20"] ."},
                        {y:" . $array_days[191]["maiorigual20"] ."},
                        {y:" . $array_days[191]["maiorigual41"] ."}]},
                        {                
                        name: 'Distrato',
                        data: [
                        {y:" . $array_days[197]["menor20"] ."},
                        {y:" . $array_days[197]["maiorigual20"] ."},
                        {y:" . $array_days[197]["maiorigual41"] ."}],
                        dataLabels: {
                            enabled: false,
                            style: {
                                fontSize: '11px',
                                fontFamily: 'Verdana, sans-serif'
                            }
                        }

                    }]
                });
            });

	</script>";

		?>
