<?php

$status = "(5,6)";

$query1 = "
SELECT COUNT(glpi_tickets.id) as tick
FROM glpi_tickets
WHERE glpi_tickets.is_deleted = 0 
AND glpi_tickets.itilcategories_id = 189
" . $entidade . "";


$result1 = $DB->query($query1) or die('erro');
$aditivo = $DB->fetch_assoc($result1);

$query2 = "
SELECT COUNT(glpi_tickets.id) as tick
FROM glpi_tickets
WHERE glpi_tickets.is_deleted = 0 
AND glpi_tickets.itilcategories_id = 190
" . $entidade . "";



$result2 = $DB->query($query2) or die('erro');
$cotacao = $DB->fetch_assoc($result2);


$query3 = "
SELECT COUNT(glpi_tickets.id) as tick
FROM glpi_tickets
WHERE glpi_tickets.is_deleted = 0 
AND glpi_tickets.itilcategories_id = 191
" . $entidade . "";



$result3 = $DB->query($query3) or die('erro');
$dispensa = $DB->fetch_assoc($result3);


$query4 = "
SELECT COUNT(glpi_tickets.id) as tick
FROM glpi_tickets
WHERE glpi_tickets.is_deleted = 0 
AND glpi_tickets.itilcategories_id = 197
" . $entidade . "";



$result4 = $DB->query($query4) or die('erro');
$distrato = $DB->fetch_assoc($result4);



//echo "teste <br>";


echo "
<script type='text/javascript'>

$(function () {     
                
        // Build the chart
        $('#graf_tipo').highcharts({
            chart: {
                type: 'column',
                height: 450,
                plotBorderColor: '#ffffff',
                plotBorderWidth: 0
            },
            title: {                
                text: 'Chamados por Status'                
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
            categories: ['Aditivo', 'Cotação', 'Dispensa', 'Distrato'],
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
                    cursor: '',
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
                        name: 'Aditivo',
                        y: " . $aditivo['tick'] . ",   
                        selected: false,
                        
                    },
                    {
                        name: 'Cotação',
                        y: " . $cotacao['tick'] . ",   
                        selected: false,
                        
                    },
                    {
                        name: 'Dispensa',
                        y: " . $dispensa['tick'] . ",   
                        selected: false,
                        
                    },
                    {
                        name: 'Distrato',
                        y: " . $distrato['tick'] . ",   
                        selected: false,
                        
                    }

                    ";

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

	
?>
