<?php


    $query_new = "
    Select SUM(IF( days < 20, chamados, 0 )  ) AS menor20, SUM(IF( days >= 20 && days < 41, chamados, 0 )  ) AS maiorigual20 , SUM(IF( days >= 41, chamados, 0 )  ) AS maiorigual41,categoria
    from 
    (SELECT count( id ) AS chamados , DATEDIFF( solvedate, date ) AS days, itilcategories_id as categoria
    FROM glpi_tickets 
    WHERE solvedate IS NOT NULL AND is_deleted = 0
    AND glpi_tickets.itilcategories_id IN (189,190,191,197) 
    AND glpi_tickets.date ".$datas."    
    ".$entidade."
    GROUP BY days, itilcategories_id) as tabela
    group by categoria";

    $result_new = $DB->query($query_new) or die('erro');
    $array_days = [];
    $array_days[189]['menor20'] = 0;
    $array_days[189]['maiorigual20'] = 0;
    $array_days[189]['maiorigual41'] = 0;
    $array_days[190]['menor20'] = 0;
    $array_days[190]['maiorigual20'] = 0;
    $array_days[190]['maiorigual41'] = 0;
    $array_days[191]['menor20'] = 0;
    $array_days[191]['maiorigual20'] = 0;
    $array_days[191]['maiorigual41'] = 0;
    $array_days[197]['menor20'] = 0;
    $array_days[197]['maiorigual20'] = 0;
    $array_days[197]['maiorigual41'] = 0;

    while ($row_result_new = $DB->fetch_assoc($result_new)) 
    {
        $array_days[$row_result_new['categoria']]['menor20'] = $row_result_new['menor20'];
        $array_days[$row_result_new['categoria']]['maiorigual20'] = $row_result_new['maiorigual20'];
        $array_days[$row_result_new['categoria']]['maiorigual41'] = $row_result_new['maiorigual41'];
    }


    echo "<script type='text/javascript'>

            $(function () {
                    $('#graftime').highcharts({
                        chart: {
                            type: 'column',
                                height: 450,
                            plotBorderColor: '#ffffff',
                            plotBorderWidth: 0
                        },
                        title: {
                            //text: '" .__('Open Tickets Age','dashboard')."'
                            text: 'Tempo de Solução por Categoria'
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
                            y: 10,
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
