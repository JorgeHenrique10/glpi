    <?php

    $datai_s = date("Y-m-d");  //hoje
    $dataf_s = date('Y-m-d', strtotime('-6 days'));

    $datai_q = date('Y-m-d', strtotime('-6 days'));
    $dataf_q = date('Y-m-d', strtotime('-14 days'));

    $datai_m = date('Y-m-d', strtotime('-15 days'));
    $dataf_m = date('Y-m-d', strtotime('-29 days'));

    $datai_m1 = date('Y-m-d', strtotime('-30 days'));
    $dataf_m1 = date('Y-m-d', strtotime('-59 days'));

    $datai_m2 = date('Y-m-d', strtotime('-60 days'));
    $dataf_m2 = date('Y-m-d', strtotime('-365 days'));

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

    $query_new = "
    Select SUM(IF( days < 20, chamados, null )  ) AS menor20, SUM(IF( days >= 20 && days < 41, chamados, null )  ) AS maiorigual20 , SUM(IF( days >= 41, chamados, null )  ) AS maiorigual41,categoria
    from 
    (SELECT count( id ) AS chamados , DATEDIFF( solvedate, date ) AS days, itilcategories_id as categoria
    FROM glpi_tickets 
    WHERE solvedate IS NOT NULL AND is_deleted = 0
    AND glpi_tickets.itilcategories_id IN (189,190,191,197) 
    AND DATE_FORMAT( date, '%Y' ) IN (".$years.")     
    ".$entidade."
    GROUP BY days, itilcategories_id) as tabela
    group by categoria";

    $result_new = $DB->query($query_new) or die('erro');
    $array_days = [];

    while ($row_result_new = $DB->fetch_assoc($result_new)) 
    {
        $array_days[$row_result_new['categoria']]['menor20'] = $row_result_new['menor20'];
        $array_days[$row_result_new['categoria']]['maiorigual20'] = $row_result_new['maiorigual20'];
        $array_days[$row_result_new['categoria']]['maiorigual41'] = $row_result_new['maiorigual41'];
    }

    $query_menor_15 = "
      SELECT glpi_entities.id as unidade_id, glpi_entities.name as unidade, glpi_itilcategories.name as categoria_nome, glpi_tickets.itilcategories_id as categoria, COUNT(glpi_tickets.id) as qtd
      FROM glpi_tickets 
      INNER JOIN glpi_itilcategories ON glpi_itilcategories.id = glpi_tickets.itilcategories_id
      INNER JOIN glpi_entities ON glpi_entities.id = glpi_tickets.entities_id 
      WHERE glpi_tickets.is_deleted = 0 
      AND glpi_tickets.date BETWEEN '" . $dataf_q ." 00:00:00' AND '".$dataf_s." 23:59:59' 
      AND DATE_FORMAT( date, '%Y' ) IN (".$years.")  
      ". $entidade ."
      AND status NOT IN (5,6)
      group by glpi_entities.name, glpi_tickets.itilcategories_id, glpi_itilcategories.name, glpi_entities.id
      order by glpi_entities.comment
    ";

    $query_15_30 = "
      SELECT glpi_entities.id as unidade_id, glpi_entities.name as unidade, glpi_itilcategories.name as categoria_nome, glpi_tickets.itilcategories_id as categoria, COUNT(glpi_tickets.id) as qtd
      FROM glpi_tickets 
      INNER JOIN glpi_itilcategories ON glpi_itilcategories.id = glpi_tickets.itilcategories_id
      INNER JOIN glpi_entities ON glpi_entities.id = glpi_tickets.entities_id 
      WHERE glpi_tickets.is_deleted = 0 
      AND glpi_tickets.date BETWEEN '" . $dataf_m ." 00:00:00' AND '".$dataf_q." 23:59:59' 
      AND DATE_FORMAT( date, '%Y' ) IN (".$years.")  
      ". $entidade ."
      AND status NOT IN (5,6)
      group by glpi_entities.name, glpi_tickets.itilcategories_id, glpi_itilcategories.name, glpi_entities.id
      order by glpi_entities.comment
    ";

    $query_maior_30 = "
      SELECT glpi_entities.id as unidade_id, glpi_entities.name as unidade, glpi_itilcategories.name as categoria_nome, glpi_tickets.itilcategories_id as categoria, COUNT(glpi_tickets.id) as qtd
      FROM glpi_tickets 
      INNER JOIN glpi_itilcategories ON glpi_itilcategories.id = glpi_tickets.itilcategories_id
      INNER JOIN glpi_entities ON glpi_entities.id = glpi_tickets.entities_id 
      WHERE glpi_tickets.is_deleted = 0 
      AND glpi_tickets.date BETWEEN '" . $dataf_m2 ." 00:00:00' AND '".$datai_m1." 23:59:59' 
      AND DATE_FORMAT( date, '%Y' ) IN (".$years.")  
      ". $entidade ."
      AND status NOT IN (5,6)
      group by glpi_entities.name, glpi_tickets.itilcategories_id, glpi_itilcategories.name, glpi_entities.id
      order by glpi_entities.comment
    ";

    //$result_15 = $DB->fetch_assoc($query_menor_15_q);

    //$ids_unidades = implode(',', $_SESSION['glpiactiveentities']);

    $part = substr( $sel_ent, 0,2);

    if($part == '0,')
    {
      $sel_ent = substr( $sel_ent, 2);
    }

    $query_unidades = "
      SELECT name 
      FROM glpi_entities
      WHERE id in ($sel_ent)
      order by glpi_entities.comment;
    ";
    $query_unidades_q = $DB->query($query_unidades);

    $array_15_aditivo = [];
    $array_15_cotacao = [];
    $array_15_dipensa = [];
    $array_15_distrato = [];

    $array_30_aditivo = [];
    $array_30_cotacao = [];
    $array_30_dipensa = [];
    $array_30_distrato = [];

    $array_60_aditivo = [];
    $array_60_cotacao = [];
    $array_60_dipensa = [];
    $array_60_distrato = [];

    $array_unidades = [];

    //print_r($sel_ent);exit;
    $a_string_unidades=[];
    //$inicio = true;

    while ($unidade = $DB->fetch_assoc($query_unidades_q) ) 
    {

      $a_string_unidades[] = $unidade['name'];

      $array_15_aditivo[$unidade['name']] = 0;
      $array_15_cotacao[$unidade['name']] = 0;
      $array_15_dipensa[$unidade['name']] = 0;
      $array_15_distrato[$unidade['name']] = 0;

      $array_30_aditivo[$unidade['name']] = 0;
      $array_30_cotacao[$unidade['name']] = 0;
      $array_30_dipensa[$unidade['name']] = 0;
      $array_30_distrato[$unidade['name']] = 0;

      $array_60_aditivo[$unidade['name']] = 0;
      $array_60_cotacao[$unidade['name']] = 0;
      $array_60_dipensa[$unidade['name']] = 0;
      $array_60_distrato[$unidade['name']] = 0;    
    }

    $query_menor_15_q = $DB->query($query_menor_15);

    while ($objeto = $DB->fetch_assoc($query_menor_15_q) ) 
    {
      switch ($objeto['categoria']) 
      {
        case 189:
          $array_15_aditivo[$objeto['unidade']] = $objeto['qtd'];
          break;
        case 190:
          $array_15_cotacao[$objeto['unidade']] = $objeto['qtd'];
          break;
        case 191:
          $array_15_dipensa[$objeto['unidade']] = $objeto['qtd'];
          break;
        case 197:
          $array_15_distrato[$objeto['unidade']] = $objeto['qtd'];
          break;
      }
      $array_unidades[$objeto['unidade']][15] = $objeto['unidade_id'];
    }

    $query_15_30_q = $DB->query($query_15_30);

    while ($objeto = $DB->fetch_assoc($query_15_30_q) ) 
    {
      switch ($objeto['categoria']) 
      {
        case 189:
          $array_30_aditivo[$objeto['unidade']] = $objeto['qtd'];         
          break;
        case 190:
          $array_30_cotacao[$objeto['unidade']] = $objeto['qtd'];        
          break;
        case 191:
          $array_30_dipensa[$objeto['unidade']] = $objeto['qtd'];        
          break;
        case 197:
          $array_30_distrato[$objeto['unidade']] = $objeto['qtd'];        
          break;
      }
      $array_unidades[$objeto['unidade']][30] = $objeto['unidade_id'];
    }

    $query_maior_30_q = $DB->query($query_maior_30);

    while ($objeto = $DB->fetch_assoc($query_maior_30_q) ) 
    {
      switch ($objeto['categoria']) 
      {
        case 189:
          $array_60_aditivo[$objeto['unidade']] = $objeto['qtd'];; 
          break;
        case 190:
          $array_60_cotacao[$objeto['unidade']] = $objeto['qtd']; 
          break;
        case 191:
          $array_60_dipensa[$objeto['unidade']] = $objeto['qtd']; 
          break;
        case 197:
          $array_60_distrato[$objeto['unidade']] = $objeto['qtd'];
          break;
      }
      $array_unidades[$objeto['unidade']][60] = $objeto['unidade_id'];
    }

    $string_15 ="";
    $string_30 ="";
    $string_60 ="";
    
    $query_unidades_q = $DB->query($query_unidades);
    while ($objeto = $DB->fetch_assoc($query_unidades_q) ) 
    {

      $aditivo_15 = $array_15_aditivo[$objeto['name']] ? $array_15_aditivo[$objeto['name']] : 0;
      $cotacao_15 = $array_15_cotacao[$objeto['name']]  ? $array_15_cotacao[$objeto['name']] : 0;
      $dispensa_15 = $array_15_dipensa[$objeto['name']]  ? $array_15_dipensa[$objeto['name']] : 0;
      $distrato_15 = $array_15_distrato[$objeto['name']]  ? $array_15_distrato[$objeto['name']] : 0;
      $total_15 = $aditivo_15 + $cotacao_15 + $dispensa_15 + $distrato_15;

      $aditivo_30 = $array_30_aditivo[$objeto['name']] ? $array_30_aditivo[$objeto['name']] : 0;
      $cotacao_30 = $array_30_cotacao[$objeto['name']]  ? $array_30_cotacao[$objeto['name']] : 0;
      $dispensa_30 = $array_30_dipensa[$objeto['name']]  ? $array_30_dipensa[$objeto['name']] : 0;
      $distrato_30 = $array_30_distrato[$objeto['name']]  ? $array_30_distrato[$objeto['name']] : 0;
      $total_30 = $aditivo_30 + $cotacao_30 + $dispensa_30 + $distrato_30;

      $aditivo_60 = $array_60_aditivo[$objeto['name']] ? $array_60_aditivo[$objeto['name']] : 0;
      $cotacao_60 = $array_60_cotacao[$objeto['name']]  ? $array_60_cotacao[$objeto['name']] : 0;
      $dispensa_60 = $array_60_dipensa[$objeto['name']]  ? $array_60_dipensa[$objeto['name']] : 0;
      $distrato_60 = $array_60_distrato[$objeto['name']]  ? $array_60_distrato[$objeto['name']] : 0;
      $total_60 = $aditivo_60 + $cotacao_60 + $dispensa_60 + $distrato_60;

      $string_15 .= "{y:  $total_15, aditivo: $aditivo_15, cotacao: $cotacao_15, dispensa: $dispensa_15, distrato: $distrato_15, url:'reports/rel_data.php?entidade=". $array_unidades[$objeto['name']][15] . "&date1=". $dataf_q ."&date2=" . $dataf_s ."&con=1' }, ";

      $string_30 .= "{y:  $total_30, aditivo: $aditivo_30, cotacao: $cotacao_30, dispensa: $dispensa_30, distrato: $distrato_30, url:'reports/rel_data.php?entidade=". $array_unidades[$objeto['name']][30] . "&date1=". $dataf_m ."&date2=" . $dataf_q ."&con=1' }, ";

      $string_60 .= "{y:  $total_60, aditivo: $aditivo_60, cotacao: $cotacao_60, dispensa: $dispensa_60, distrato: $distrato_60, url:'reports/rel_data.php?entidade=". $array_unidades[$objeto['name']][60] . "&date1=". $dataf_m2 ."&date2=" . $datai_m1 ."&con=1' }, ";
    }

    $string_unidades = implode("','", $a_string_unidades);

echo "
<script type='text/javascript'>
    $(function () {
      $('#graf_unidade').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: ''
        },
        xAxis: {
            categories: [ ' $string_unidades ' ],
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Chamados'
            },
            stackLabels: {
                enabled: true,
                style: {
                    fontWeight: 'bold',
                    color: ( // theme
                        Highcharts.defaultOptions.title.style &&
                        Highcharts.defaultOptions.title.style.color
                    ) || 'gray'
                }
            }
        },
        legend: {
            align: 'right',
            x: -30,
            verticalAlign: 'top',
            y: 25,
            floating: true,
            backgroundColor:
                Highcharts.defaultOptions.legend.backgroundColor || 'white',
            borderColor: '#CCC',
            borderWidth: 1,
            shadow: false
        },
        tooltip: {
            headerFormat: '<b>{point.x}</b><br/>',
            pointFormat: '{series.name}: {point.y}<br/> <br/> Aditivo: {point.aditivo} <br/> Cotação: {point.cotacao} <br/>Dispensa: {point.dispensa} <br/> Distrato: {point.distrato} <br/>'
        },
        plotOptions: {
          series: {
            cursor: 'pointer',
            point: {
              events: {
                  click: function () {
                      window.open(this.options.url);
                      //location.href = this.options.url;
                  }
              }
            }
          },
            column: {
                stacking: 'normal',
                dataLabels: {
                    enabled: true
                }
            }
        },
        series: [{
            name: '< 15 dias',
            data: [ $string_15 ]
        }, {
            name: '15 - 30 dias',
            data: [ $string_30 ]
        }, {
            name: '> 30 dias',
            data: [ $string_60 ]
        }]
    });
  });

</script>
";



?>
