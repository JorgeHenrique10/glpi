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
		//Calculo Leadtime
		$query_stat_lead_time = "
		SELECT
		SUM(case when glpi_tickets_status.status_cod = 1 then glpi_tickets_status.data_cons else 0 end) AS new,
		SUM(case when glpi_tickets_status.status_cod = 2 then glpi_tickets_status.data_cons else 0 end) AS assig,
		SUM(case when glpi_tickets_status.status_cod = 3 then glpi_tickets_status.data_cons else 0 end) AS plan,
		SUM(case when glpi_tickets_status.status_cod = 4 then glpi_tickets_status.data_cons else 0 end) AS pend,
		SUM(case when glpi_tickets_status.status_cod = 5 then glpi_tickets_status.data_cons else 0 end) AS solve,
		SUM(case when glpi_tickets_status.status_cod = 6 then glpi_tickets_status.data_cons else 0 end) AS close,
		SUM(case when glpi_tickets_status.status_cod = 12 then glpi_tickets_status.data_cons else 0 end) AS qualificacao,
		SUM(case when glpi_tickets_status.status_cod = 23 then glpi_tickets_status.data_cons else 0 end) AS atribuido,
		SUM(case when glpi_tickets_status.status_cod = 13 then glpi_tickets_status.data_cons else 0 end) AS validacao_tr,
		SUM(case when glpi_tickets_status.status_cod = 14 then glpi_tickets_status.data_cons else 0 end) AS publicacao,
		SUM(case when glpi_tickets_status.status_cod = 15 then glpi_tickets_status.data_cons else 0 end) AS parecer_habilitacao,
		SUM(case when glpi_tickets_status.status_cod = 16 then glpi_tickets_status.data_cons else 0 end) AS validacao_tecnica,
		SUM(case when glpi_tickets_status.status_cod = 17 then glpi_tickets_status.data_cons else 0 end) AS resultados,
		SUM(case when glpi_tickets_status.status_cod = 18 then glpi_tickets_status.data_cons else 0 end) AS homologacao,
		SUM(case when glpi_tickets_status.status_cod = 19 then glpi_tickets_status.data_cons else 0 end) AS juridico,
		SUM(case when glpi_tickets_status.status_cod = 20 then glpi_tickets_status.data_cons else 0 end) AS validacao_interna,
		SUM(case when glpi_tickets_status.status_cod = 21 then glpi_tickets_status.data_cons else 0 end) AS envio_contrato,
		SUM(case when glpi_tickets_status.status_cod = 22 then glpi_tickets_status.data_cons else 0 end) AS formalizacao,
		SUM(case when glpi_tickets_status.status_cod = 24 then glpi_tickets_status.data_cons else 0 end) AS pendente_unidade,
		SUM(case when glpi_tickets_status.status_cod = 25 then glpi_tickets_status.data_cons else 0 end) AS publicacao_errata,
		SUM(case when glpi_tickets_status.status_cod = 26 then glpi_tickets_status.data_cons else 0 end) AS prorrogacao,
		SUM(case when glpi_tickets_status.status_cod = 27 then glpi_tickets_status.data_cons else 0 end) AS diligencia,
		SUM(case when glpi_tickets_status.status_cod = 28 then glpi_tickets_status.data_cons else 0 end) AS recurso,

		count( IF(glpi_tickets_status.status_cod=1, glpi_tickets_status.id, NULL)  ) AS new_count,
		count( IF(glpi_tickets_status.status_cod=2, glpi_tickets_status.id, NULL)  ) AS assig_count,
		count( IF(glpi_tickets_status.status_cod=3, glpi_tickets_status.id, NULL)  ) AS plan_count,
		count( IF(glpi_tickets_status.status_cod=4, glpi_tickets_status.id, NULL)  ) AS pend_count,
		count( IF(glpi_tickets_status.status_cod=5, glpi_tickets_status.id, NULL)  ) AS solve_count,
		count( IF(glpi_tickets_status.status_cod=6, glpi_tickets_status.id, NULL)  ) AS close_count,
		count( IF(glpi_tickets_status.status_cod=12, glpi_tickets_status.id, NULL) ) AS qualificacao_count,
		count( IF(glpi_tickets_status.status_cod=23, glpi_tickets_status.id, NULL) ) AS atribuido_count,
		count( IF(glpi_tickets_status.status_cod=13, glpi_tickets_status.id, NULL) ) AS validacao_tr_count,
		count( IF(glpi_tickets_status.status_cod=14, glpi_tickets_status.id, NULL) ) AS publicacao_count,
		count( IF(glpi_tickets_status.status_cod=15, glpi_tickets_status.id, NULL) ) AS parecer_habilitacao_count,
		count( IF(glpi_tickets_status.status_cod=16, glpi_tickets_status.id, NULL) ) AS validacao_tecnica_count,
		count( IF(glpi_tickets_status.status_cod=17, glpi_tickets_status.id, NULL) ) AS resultados_count,
		count( IF(glpi_tickets_status.status_cod=18, glpi_tickets_status.id, NULL) ) AS homologacao_count,
		count( IF(glpi_tickets_status.status_cod=19, glpi_tickets_status.id, NULL) ) AS juridico_count,
		count( IF(glpi_tickets_status.status_cod=20, glpi_tickets_status.id, NULL) ) AS validacao_interna_count,
		count( IF(glpi_tickets_status.status_cod=21, glpi_tickets_status.id, NULL) ) AS envio_contrato_count,
		count( IF(glpi_tickets_status.status_cod=22, glpi_tickets_status.id, NULL) ) AS formalizacao_count,
		count( IF(glpi_tickets_status.status_cod=24, glpi_tickets_status.id, NULL) ) AS pendente_unidade_count,
		count( IF(glpi_tickets_status.status_cod=25, glpi_tickets_status.id, NULL) ) AS publicacao_errata_count,
		count( IF(glpi_tickets_status.status_cod=26, glpi_tickets_status.id, NULL) ) AS prorrogacao_count,
		count( IF(glpi_tickets_status.status_cod=27, glpi_tickets_status.id, NULL) ) AS diligencia_count,
		count( IF(glpi_tickets_status.status_cod=28, glpi_tickets_status.id, NULL) ) AS recurso_count

		FROM glpi_tickets_status
		INNER JOIN glpi_tickets on glpi_tickets.id = glpi_tickets_status.ticket_id
		INNER JOIN glpi_itilcategories on glpi_tickets.itilcategories_id = glpi_itilcategories.id
		WHERE glpi_tickets.is_deleted = '0'
		AND glpi_tickets_status.data_fim is not null
		AND glpi_itilcategories.id = 190
		AND glpi_tickets.solvedate " . $sel_date . "			
		" . $entidade . "";
		
		$result_stat_lead_time = $DB->query($query_stat_lead_time);

		$query_contratos = "SELECT id, entities_id FROM glpi_entities WHERE id in (" . $sel_ent . ")";

		$result_contratos = $DB->query($query_contratos);
		$sel_ent_contratos = $result_contratos->fetch_all();
	
		$mostrar = false;
		foreach ($sel_ent_contratos as $item) {
			if ($item[0] == 17 || $item[1] == 17) {
				$mostrar = true;
			}
		}
		

		$new_lead = number_format((($DB->result($result_stat_lead_time, 0, 'new') + 0) / ($DB->result($result_stat_lead_time, 0, 'new_count') + 0)), 2, '.', ' ');
		$assig_lead = number_format((($DB->result($result_stat_lead_time, 0, 'assig') + 0) / ($DB->result($result_stat_lead_time, 0, 'assig_count') + 0)), 2, '.', ' ');
		$plan_lead = number_format((($DB->result($result_stat_lead_time, 0, 'plan') + 0) / ($DB->result($result_stat_lead_time, 0, 'plan_count') + 0)), 2, '.', ' ');
		$pend_lead = number_format((($DB->result($result_stat_lead_time, 0, 'pend') + 0) / ($DB->result($result_stat_lead_time, 0, 'pend_count') + 0)), 2, '.', ' ');
		$solve_lead = number_format((($DB->result($result_stat_lead_time, 0, 'solve') + 0) / ($DB->result($result_stat_lead_time, 0, 'solve_count') + 0)), 2, '.', ' ');
		$close_lead = number_format((($DB->result($result_stat_lead_time, 0, 'close') + 0) / ($DB->result($result_stat_lead_time, 0, 'close_count') + 0)), 2, '.', ' ');
		$atribuido_lead = number_format((($DB->result($result_stat_lead_time, 0, 'atribuido') + 0) / ($DB->result($result_stat_lead_time, 0, 'atribuido_count') + 0)), 2, '.', ' ');
		$validacao_tr_lead = number_format((($DB->result($result_stat_lead_time, 0, 'validacao_tr') + 0) / ($DB->result($result_stat_lead_time, 0, 'validacao_tr_count') + 0)), 2, '.', ' ');
		$publicacao_lead = number_format((($DB->result($result_stat_lead_time, 0, 'publicacao') + 0) / ($DB->result($result_stat_lead_time, 0, 'publicacao_count') + 0)), 2, '.', ' ');
		$parecer_habilitacao_lead = number_format((($DB->result($result_stat_lead_time, 0, 'parecer_habilitacao') + 0) / ($DB->result($result_stat_lead_time, 0, 'parecer_habilitacao_count') + 0)), 2, '.', ' ');
		$validacao_tecnica_lead = number_format((($DB->result($result_stat_lead_time, 0, 'validacao_tecnica') + 0) / ($DB->result($result_stat_lead_time, 0, 'validacao_tecnica_count') + 0)), 2, '.', ' ');
		$resultados_lead = number_format((($DB->result($result_stat_lead_time, 0, 'resultados') + 0) / ($DB->result($result_stat_lead_time, 0, 'resultados_count') + 0)), 2, '.', ' ');
		$homologacao_lead = number_format((($DB->result($result_stat_lead_time, 0, 'homologacao') + 0) / ($DB->result($result_stat_lead_time, 0, 'homologacao_count') + 0)), 2, '.', ' ');
		$juridico_lead = number_format((($DB->result($result_stat_lead_time, 0, 'juridico') + 0) / ($DB->result($result_stat_lead_time, 0, 'juridico_count') + 0)), 2, '.', ' ');
		$validacao_interna_lead = number_format((($DB->result($result_stat_lead_time, 0, 'validacao_interna') + 0) / ($DB->result($result_stat_lead_time, 0, 'validacao_interna_count') + 0)), 2, '.', ' ');
		$envio_contrato_lead = number_format((($DB->result($result_stat_lead_time, 0, 'envio_contrato') + 0) / ($DB->result($result_stat_lead_time, 0, 'envio_contrato_count') + 0)), 2, '.', ' ');
		$formalizacao_lead = number_format((($DB->result($result_stat_lead_time, 0, 'formalizacao') + 0) / ($DB->result($result_stat_lead_time, 0, 'formalizacao_count') + 0)), 2, '.', ' ');
		$pendente_unidade_lead = number_format((($DB->result($result_stat_lead_time, 0, 'pendente_unidade') + 0) / ($DB->result($result_stat_lead_time, 0, 'pendente_unidade_count') + 0)), 2, '.', ' ');
		$publicacao_errata_lead = number_format((($DB->result($result_stat_lead_time, 0, 'publicacao_errata') + 0) / ($DB->result($result_stat_lead_time, 0, 'publicacao_errata_count') + 0)), 2, '.', ' ');
		$prorrogacao_lead = number_format((($DB->result($result_stat_lead_time, 0, 'prorrogacao') + 0) / ($DB->result($result_stat_lead_time, 0, 'prorrogacao_count') + 0)), 2, '.', ' ');
		$diligencia_lead = number_format((($DB->result($result_stat_lead_time, 0, 'diligencia') + 0) / ($DB->result($result_stat_lead_time, 0, 'diligencia_count') + 0)), 2, '.', ' ');
		$recurso_lead = number_format((($DB->result($result_stat_lead_time, 0, 'recurso') + 0) / ($DB->result($result_stat_lead_time, 0, 'recurso_count') + 0)), 2, '.', ' ');

		//RETIRAR "NAN"
		$new_lead != 'nan' ? $new_lead : $new_lead = 0;
		$assig_lead != 'nan' ? $assig_lead : $assig_lead = 0;
		$plan_lead != 'nan' ? $plan_lead : $plan_lead = 0;
		$pend_lead != 'nan' ? $pend_lead : $pend_lead = 0;
		$solve_lead != 'nan' ? $solve_lead : $solve_lead = 0;
		$close_lead != 'nan' ? $close_lead : $close_lead = 0;
		$atribuido_lead != 'nan' ? $atribuido_lead : $atribuido_lead = 0;
		$validacao_tr_lead != 'nan' ? $validacao_tr_lead : $validacao_tr_lead = 0;
		$publicacao_lead != 'nan' ? $publicacao_lead : $publicacao_lead = 0;
		$parecer_habilitacao_lead != 'nan' ? $parecer_habilitacao_lead : $parecer_habilitacao_lead = 0;
		$validacao_tecnica_lead != 'nan' ? $validacao_tecnica_lead : $validacao_tecnica_lead = 0;
		$resultados_lead != 'nan' ? $resultados_lead : $resultados_lead = 0;
		$homologacao_lead != 'nan' ? $homologacao_lead : $homologacao_lead = 0;
		$juridico_lead != 'nan' ? $juridico_lead : $juridico_lead = 0;
		$validacao_interna_lead != 'nan' ? $validacao_interna_lead : $validacao_interna_lead = 0;
		$envio_contrato_lead != 'nan' ? $envio_contrato_lead : $envio_contrato_lead = 0;
		$formalizacao_lead != 'nan' ? $formalizacao_lead : $formalizacao_lead = 0;
		$pendente_unidade_lead != 'nan' ? $pendente_unidade_lead : $pendente_unidade_lead = 0;
		$publicacao_errata_lead != 'nan' ? $publicacao_errata_lead : $publicacao_errata_lead = 0;
		$prorrogacao_lead != 'nan' ? $prorrogacao_lead : $prorrogacao_lead = 0;
		$diligencia_lead != 'nan' ? $diligencia_lead : $diligencia_lead = 0;
		$recurso_lead != 'nan' ? $recurso_lead : $recurso_lead = 0;
		$aditivos_renovados != 'nan' ? $aditivos_renovados : $aditivos_renovados = 0;
		$aditivos_dias != 'nan' ? $aditivos_dias : $aditivos_dias = 0;

        // print_r(Ticket::getStatus($grf2[1]));exit;
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
            categories: ['Novo','Processando','Planejamento','Pendente','Solucionado','Fechado','Validação TR', 'Publicação','Parecer Habilitação','Validação Técnica','Resultados','Homologação','Jurídico','Validação Interna', 'Envio de Contrato','Atribuído','Formalização','Pendente Unidade','Publicação Errata','Prorrogação', 'Diligência','Recurso'],
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
                        name: 'Novo',
                        y: " . $new_lead . ",   
                        url:'reports/rel_tickets.php?con=1&stat=" . 1 . "',                                    
                        selected: false,
                        
                    },
                    {
                        name: 'Processando',
                        y: " .$assig_lead.", 
                        url:'reports/rel_tickets.php?con=1&stat=". 2 . "' 
                    },
                    { 
                        name: 'Planejamento',
                        y: " .$plan_lead.", 
                        url:'reports/rel_tickets.php?con=1&stat=". 3 . "' 
                    },
                    { 
                        name: 'Pendente',
                        y: " .$pend_lead.", 
                        url:'reports/rel_tickets.php?con=1&stat=". 4 . "' 
                    },
                    { 
                        name: 'Solucionado',
                        y: " .$solve_lead.", 
                        url:'reports/rel_tickets.php?con=1&stat=". 5 . "' 
                    },
                    { 
                        name: 'Fechado',
                        y: " .$close_lead.", 
                        url:'reports/rel_tickets.php?con=1&stat=". 6 . "' 
                    },
                    { 
                        name: 'Validação TR',
                        y: " .$validacao_tr_lead.", 
                        url:'reports/rel_tickets.php?con=1&stat=". 13 . "' 
                    },
                    { 
                        name: 'Publicação',
                        y: " .$publicacao_lead.", 
                        url:'reports/rel_tickets.php?con=1&stat=". 14 . "' 
                    },

                    { 
                        name: 'Parecer Habilitação',
                        y: " .$parecer_habilitacao_lead.", 
                        url:'reports/rel_tickets.php?con=1&stat=". 15 . "' 
                    },
                    { 
                        name: 'Validação Técnica',
                        y: " .$validacao_tecnica_lead.", 
                        url:'reports/rel_tickets.php?con=1&stat=". 16 . "' 
                    },
                    { 
                        name: 'Resultados',
                        y: " .$resultados_lead.", 
                        url:'reports/rel_tickets.php?con=1&stat=". 17 . "' 
                    },
                    { 
                        name: 'Homologação',
                        y: " .$homologacao_lead.", 
                        url:'reports/rel_tickets.php?con=1&stat=". 18 . "' 
                    },
                    { 
                        name: 'Jurídico',
                        y: " .$juridico_lead.", 
                        url:'reports/rel_tickets.php?con=1&stat=". 19 . "' 
                    },
                    { 
                        name: 'Validação Interna',
                        y: " .$validacao_interna_lead.", 
                        url:'reports/rel_tickets.php?con=1&stat=". 20 . "' 
                    },
                    { 
                        name: 'Envio de Contrato',
                        y: " .$envio_contrato_lead.", 
                        url:'reports/rel_tickets.php?con=1&stat=". 21 . "' 
                    },
                    { 
                        name: 'Atribuído',
                        y: " .$atribuido_lead.", 
                        url:'reports/rel_tickets.php?con=1&stat=". 23 . "' 
                    },
                    { 
                        name: 'Formalização',
                        y: " .$formalizacao_lead.", 
                        url:'reports/rel_tickets.php?con=1&stat=". 22 . "' 
                    },
                    { 
                        name: 'Pendente Unidade',
                        y: " .$pendente_unidade_lead.", 
                        url:'reports/rel_tickets.php?con=1&stat=". 24 . "' 
                    },
                    { 
                        name: 'Publicação Errata',
                        y: " .$publicacao_errata_lead.", 
                        url:'reports/rel_tickets.php?con=1&stat=". 25 . "' 
                    },
                    { 
                        name: 'Prorrogação',
                        y: " .$prorrogacao_lead.", 
                        url:'reports/rel_tickets.php?con=1&stat=". 26 . "' 
                    },
                    { 
                        name: 'Diligência',
                        y: " .$diligencia_lead.", 
                        url:'reports/rel_tickets.php?con=1&stat=". 27 . "' 
                    },
                    { 
                        name: 'Recurso',
                        y: " .$recurso_lead.", 
                        url:'reports/rel_tickets.php?con=1&stat=". 28 . "' 
                    }
                ],
                    ";




echo " 
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
