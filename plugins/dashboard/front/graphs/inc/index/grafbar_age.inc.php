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

//ADITIVOS
//semana
$sql_s = "
SELECT DATE_FORMAT(date, '%Y-%m-%d') as data, SUM(case when glpi_itilcategories.id = 189 then 1 else 0 end) as conta
FROM glpi_tickets
INNER JOIN glpi_itilcategories ON glpi_itilcategories.id = glpi_tickets.itilcategories_id
WHERE glpi_tickets.is_deleted = 0
AND glpi_tickets.date BETWEEN '" . $dataf_s ." 00:00:00' AND '".$datai_s." 23:59:59'
AND DATE_FORMAT( date, '%Y' ) IN (".$years.")  
". $entidade ."
AND status NOT IN (5,6) ";

$query_s = $DB->query($sql_s);

$week = $DB->result($query_s,0,'conta');


//quinzena
$sql_q = "
SELECT DATE_FORMAT(date, '%Y-%m-%d') as data, SUM(case when glpi_itilcategories.id = 189 then 1 else 0 end) as conta
FROM glpi_tickets
INNER JOIN glpi_itilcategories ON glpi_itilcategories.id = glpi_tickets.itilcategories_id
WHERE glpi_tickets.is_deleted = 0
AND glpi_tickets.date BETWEEN '" . $dataf_q ." 00:00:00' AND '".$datai_q." 23:59:59'
AND DATE_FORMAT( date, '%Y' ) IN (".$years.")  
". $entidade ."
AND status NOT IN (5,6) ";

$query_q = $DB->query($sql_q);

$quinz = $DB->result($query_q,0,'conta');

//mes
$sql_m = "
SELECT DATE_FORMAT(date, '%Y-%m-%d') as data, SUM(case when glpi_itilcategories.id = 189 then 1 else 0 end) as conta
FROM glpi_tickets
INNER JOIN glpi_itilcategories ON glpi_itilcategories.id = glpi_tickets.itilcategories_id
WHERE glpi_tickets.is_deleted = 0
AND glpi_tickets.date BETWEEN '" . $dataf_m ." 00:00:00' AND '".$datai_m." 23:59:59'
AND DATE_FORMAT( date, '%Y' ) IN (".$years.")  
". $entidade ."
AND status NOT IN (5,6) ";

$query_m = $DB->query($sql_m);

$month = $DB->result($query_m,0,'conta');
// print_r($month);exit;
// > 30 e <60
$sql_m1 = "
SELECT DATE_FORMAT(date, '%Y-%m-%d') as data, SUM(case when glpi_itilcategories.id = 189 then 1 else 0 end) as conta
FROM glpi_tickets
INNER JOIN glpi_itilcategories ON glpi_itilcategories.id = glpi_tickets.itilcategories_id
WHERE glpi_tickets.is_deleted = 0
AND glpi_tickets.date BETWEEN '" . $dataf_m1 ." 00:00:00' AND '".$datai_m1." 23:59:59'
AND DATE_FORMAT( date, '%Y' ) IN (".$years.")  
". $entidade ."
AND status NOT IN (5,6) ";

$query_m1 = $DB->query($sql_m1);

$month1 = $DB->result($query_m1,0,'conta');

// > 60
$sql_m2 = "
SELECT DATE_FORMAT(date, '%b-%d') as data, COUNT(id) as conta
FROM glpi_tickets
WHERE glpi_tickets.is_deleted = 0
AND glpi_tickets.date BETWEEN '" . $dataf_m2 ." 00:00:00' AND '".$datai_m2." 23:59:59'
AND DATE_FORMAT( date, '%Y' ) IN (".$years.")  
". $entidade ."
AND status NOT IN (5,6) ";

$query_m2 = $DB->query($sql_m2);

$month2 = $DB->result($query_m2,0,'conta');

//INCIDENTS
//semana
$sql_si = "
SELECT DATE_FORMAT(date, '%Y-%m-%d') as data, SUM(case when glpi_itilcategories.id = 190 then 1 else 0 end) as conta
FROM glpi_tickets
INNER JOIN glpi_itilcategories ON glpi_itilcategories.id = glpi_tickets.itilcategories_id
WHERE glpi_tickets.is_deleted = 0
AND glpi_tickets.date BETWEEN '" . $dataf_s ." 00:00:00' AND '".$datai_s." 23:59:59'
AND DATE_FORMAT( date, '%Y' ) IN (".$years.")  
". $entidade ."
AND status NOT IN (5,6) ";

$query_si = $DB->query($sql_si);

$weeki = $DB->result($query_si,0,'conta');

//quinzena
$sql_qi = "
SELECT DATE_FORMAT(date, '%Y-%m-%d') as data, SUM(case when glpi_itilcategories.id = 190 then 1 else 0 end) as conta
FROM glpi_tickets
INNER JOIN glpi_itilcategories ON glpi_itilcategories.id = glpi_tickets.itilcategories_id
WHERE glpi_tickets.is_deleted = 0
AND glpi_tickets.date BETWEEN '" . $dataf_q ." 00:00:00' AND '".$datai_q." 23:59:59'
AND DATE_FORMAT( date, '%Y' ) IN (".$years.")  
". $entidade ."
AND status NOT IN (5,6) ";

$query_qi = $DB->query($sql_qi);

$quinzi = $DB->result($query_qi,0,'conta');

//mes
$sql_mi = "
SELECT DATE_FORMAT(date, '%Y-%m-%d') as data, SUM(case when glpi_itilcategories.id = 190 then 1 else 0 end) as conta
FROM glpi_tickets
INNER JOIN glpi_itilcategories ON glpi_itilcategories.id = glpi_tickets.itilcategories_id
WHERE glpi_tickets.is_deleted = 0
AND glpi_tickets.date BETWEEN '" . $dataf_m ." 00:00:00' AND '".$datai_m." 23:59:59'
AND DATE_FORMAT( date, '%Y' ) IN (".$years.")  
". $entidade ."
AND status NOT IN (5,6) ";

$query_mi = $DB->query($sql_mi);

$monthi = $DB->result($query_mi,0,'conta');

// > 30 e <60
$sql_m1i = "
SELECT DATE_FORMAT(date, '%Y-%m-%d') as data, SUM(case when glpi_itilcategories.id = 190 then 1 else 0 end) as conta
FROM glpi_tickets
INNER JOIN glpi_itilcategories ON glpi_itilcategories.id = glpi_tickets.itilcategories_id
WHERE glpi_tickets.is_deleted = 0
AND glpi_tickets.date BETWEEN '" . $dataf_m1 ." 00:00:00' AND '".$datai_m1." 23:59:59'
AND DATE_FORMAT( date, '%Y' ) IN (".$years.")  
". $entidade ."
AND status NOT IN (5,6) ";

$query_m1i = $DB->query($sql_m1i);
$month1i = $DB->result($query_m1i,0,'conta');


// > 60
$sql_m2i = "
SELECT DATE_FORMAT(date, '%Y-%m-%d') as data, SUM(case when glpi_itilcategories.id = 190 then 1 else 0 end) as conta
FROM glpi_tickets
INNER JOIN glpi_itilcategories ON glpi_itilcategories.id = glpi_tickets.itilcategories_id
WHERE glpi_tickets.is_deleted = 0
AND glpi_tickets.date BETWEEN '" . $dataf_m2 ." 00:00:00' AND '".$datai_m2." 23:59:59'
AND DATE_FORMAT( date, '%Y' ) IN (".$years.")  
". $entidade ."
AND status NOT IN (5,6) ";

$query_m2i = $DB->query($sql_m2i);

$month2i = $DB->result($query_m2i,0,'conta');


//dispensa
//semana
$sql_six = "
SELECT DATE_FORMAT(date, '%Y-%m-%d') as data, SUM(case when glpi_itilcategories.id = 191 then 1 else 0 end) as conta
FROM glpi_tickets
INNER JOIN glpi_itilcategories ON glpi_itilcategories.id = glpi_tickets.itilcategories_id
WHERE glpi_tickets.is_deleted = 0
AND glpi_tickets.date BETWEEN '" . $dataf_s ." 00:00:00' AND '".$datai_s." 23:59:59'
AND DATE_FORMAT( date, '%Y' ) IN (".$years.")  
". $entidade ."
AND status NOT IN (5,6) ";

$query_six = $DB->query($sql_six);

$weekix = $DB->result($query_six,0,'conta');

//quinzena
$sql_qix = "
SELECT DATE_FORMAT(date, '%Y-%m-%d') as data, SUM(case when glpi_itilcategories.id = 191 then 1 else 0 end) as conta
FROM glpi_tickets
INNER JOIN glpi_itilcategories ON glpi_itilcategories.id = glpi_tickets.itilcategories_id
WHERE glpi_tickets.is_deleted = 0
AND glpi_tickets.date BETWEEN '" . $dataf_q ." 00:00:00' AND '".$datai_q." 23:59:59'
AND DATE_FORMAT( date, '%Y' ) IN (".$years.")  
". $entidade ."
AND status NOT IN (5,6) ";

$query_qix = $DB->query($sql_qix);

$quinzix = $DB->result($query_qix,0,'conta');

//mes
$sql_mix = "
SELECT DATE_FORMAT(date, '%Y-%m-%d') as data, SUM(case when glpi_itilcategories.id = 191 then 1 else 0 end) as conta
FROM glpi_tickets
INNER JOIN glpi_itilcategories ON glpi_itilcategories.id = glpi_tickets.itilcategories_id
WHERE glpi_tickets.is_deleted = 0
AND glpi_tickets.date BETWEEN '" . $dataf_m ." 00:00:00' AND '".$datai_m." 23:59:59'
AND DATE_FORMAT( date, '%Y' ) IN (".$years.")  
". $entidade ."
AND status NOT IN (5,6) ";

$query_mix = $DB->query($sql_mix);

$monthix = $DB->result($query_mix,0,'conta');

// > 30 e <60
$sql_m1ix = "
SELECT DATE_FORMAT(date, '%Y-%m-%d') as data, SUM(case when glpi_itilcategories.id = 191 then 1 else 0 end) as conta
FROM glpi_tickets
INNER JOIN glpi_itilcategories ON glpi_itilcategories.id = glpi_tickets.itilcategories_id
WHERE glpi_tickets.is_deleted = 0
AND glpi_tickets.date BETWEEN '" . $dataf_m1 ." 00:00:00' AND '".$datai_m1." 23:59:59'
AND DATE_FORMAT( date, '%Y' ) IN (".$years.")  
". $entidade ."
AND status NOT IN (5,6) ";

$query_m1ix = $DB->query($sql_m1ix);

$month1ix = $DB->result($query_m1ix,0,'conta');


// > 60
$sql_m2ix = "
SELECT DATE_FORMAT(date, '%Y-%m-%d') as data, SUM(case when glpi_itilcategories.id = 191 then 1 else 0 end) as conta
FROM glpi_tickets
INNER JOIN glpi_itilcategories ON glpi_itilcategories.id = glpi_tickets.itilcategories_id
WHERE glpi_tickets.is_deleted = 0
AND glpi_tickets.date BETWEEN '" . $dataf_m2 ." 00:00:00' AND '".$datai_m2." 23:59:59'
AND DATE_FORMAT( date, '%Y' ) IN (".$years.")  
". $entidade ."
AND status NOT IN (5,6) ";

$query_m2ix = $DB->query($sql_m2ix);

$month2ix = $DB->result($query_m2ix,0,'conta');

//distrato
//semana
$sql_siz = "
SELECT DATE_FORMAT(date, '%Y-%m-%d') as data, SUM(case when glpi_itilcategories.id = 197 then 1 else 0 end) as conta
FROM glpi_tickets
INNER JOIN glpi_itilcategories ON glpi_itilcategories.id = glpi_tickets.itilcategories_id
WHERE glpi_tickets.is_deleted = 0
AND glpi_tickets.date BETWEEN '" . $dataf_s ." 00:00:00' AND '".$datai_s." 23:59:59'
AND DATE_FORMAT( date, '%Y' ) IN (".$years.")  
". $entidade ."
AND status NOT IN (5,6) ";

$query_siz = $DB->query($sql_siz);

$weekiz = $DB->result($query_siz,0,'conta');

//quinzena
$sql_qiz = "
SELECT DATE_FORMAT(date, '%Y-%m-%d') as data, SUM(case when glpi_itilcategories.id = 197 then 1 else 0 end) as conta
FROM glpi_tickets
INNER JOIN glpi_itilcategories ON glpi_itilcategories.id = glpi_tickets.itilcategories_id
WHERE glpi_tickets.is_deleted = 0
AND glpi_tickets.date BETWEEN '" . $dataf_q ." 00:00:00' AND '".$datai_q." 23:59:59'
AND DATE_FORMAT( date, '%Y' ) IN (".$years.")  
". $entidade ."
AND status NOT IN (5,6) ";

$query_qiz = $DB->query($sql_qiz);

$quinziz = $DB->result($query_qiz,0,'conta');

//mes
$sql_miz = "
SELECT DATE_FORMAT(date, '%Y-%m-%d') as data, SUM(case when glpi_itilcategories.id = 197 then 1 else 0 end) as conta
FROM glpi_tickets
INNER JOIN glpi_itilcategories ON glpi_itilcategories.id = glpi_tickets.itilcategories_id
WHERE glpi_tickets.is_deleted = 0
AND glpi_tickets.date BETWEEN '" . $dataf_m ." 00:00:00' AND '".$datai_m." 23:59:59'
AND DATE_FORMAT( date, '%Y' ) IN (".$years.")  
". $entidade ."
AND status NOT IN (5,6) ";

$query_miz = $DB->query($sql_miz);

$monthiz = $DB->result($query_miz,0,'conta');

// > 30 e <60
$sql_m1iz = "
SELECT DATE_FORMAT(date, '%Y-%m-%d') as data, SUM(case when glpi_itilcategories.id = 197 then 1 else 0 end) as conta
FROM glpi_tickets
INNER JOIN glpi_itilcategories ON glpi_itilcategories.id = glpi_tickets.itilcategories_id
WHERE glpi_tickets.is_deleted = 0
AND glpi_tickets.date BETWEEN '" . $dataf_m1 ." 00:00:00' AND '".$datai_m1." 23:59:59'
AND DATE_FORMAT( date, '%Y' ) IN (".$years.")  
". $entidade ."
AND status NOT IN (5,6) ";

$query_m1iz = $DB->query($sql_m1iz);

$month1iz = $DB->result($query_m1iz,0,'conta');


// > 60
$sql_m2iz = "
SELECT DATE_FORMAT(date, '%Y-%m-%d') as data, SUM(case when glpi_itilcategories.id = 197 then 1 else 0 end) as conta
FROM glpi_tickets
INNER JOIN glpi_itilcategories ON glpi_itilcategories.id = glpi_tickets.itilcategories_id
WHERE glpi_tickets.is_deleted = 0
AND glpi_tickets.date BETWEEN '" . $dataf_m2 ." 00:00:00' AND '".$datai_m2." 23:59:59'
AND DATE_FORMAT( date, '%Y' ) IN (".$years.")  
". $entidade ."
AND status NOT IN (5,6) ";

$query_m2iz = $DB->query($sql_m2iz);

$month2iz = $DB->result($query_m2iz,0,'conta');

$week = $week ? $week : 0;
$weeki = $weeki ? $weeki : 0;
$weekix = $weekix ? $weekix : 0;
$weekiz = $weekiz ? $weekiz : 0;

$quinz = $quinz ? $quinz : 0;
$quinzi = $quinzi ? $quinzi : 0;
$quinzix = $quinzix ? $quinzix : 0;
$quinziz = $quinziz ? $quinziz : 0;

$month = $month ? $month : 0;
$monthi = $monthi ? $monthi : 0;
$monthix = $monthix ? $monthix : 0;
$monthiz = $monthiz ? $monthiz : 0;

$month1 = $month1 ? $month1 : 0;
$month1i = $month1i ? $month1i : 0;
$month1ix = $month1ix ? $month1ix : 0;
$month1iz = $month1iz ? $month1iz : 0;

$month2 = $month2 ? $month2 : 0;
$month2i = $month2i ? $month2i : 0;
$month2ix = $month2ix ? $month2ix : 0;
$month2iz = $month2iz ? $month2iz : 0;


echo "<script type='text/javascript'>

$(function () {
        $('#graf8').highcharts({
            chart: {
                type: 'column',
					 height: 330,
                plotBorderColor: '#ffffff',
            	 plotBorderWidth: 0
            },
            title: {
                //text: '" .__('Open Tickets Age','dashboard')."'
                text: ''
            },

            xAxis: {
                categories: [ '1-7','7-15','15-30','> 30','> 60' ],
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
	            y: -10,
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
             cursor: 'pointer',
				 colorByPoint: false, 
             point: {
                    events: {
                        click: function () {
                            location.href = this.options.url;
                        }
                    }
                }
            }
        
            },
            series: [
            	{
                name: 'Aditivo',
                data: [{ y:$week, url:'reports/rel_data.php?con=1&stat=open&date1=".$dataf_s."&date2=".$datai_s."' }, 
                {y:$quinz, url:'reports/rel_data.php?con=1&stat=open&date1=".$dataf_q."&date2=".$datai_q."'}, 
                {y:$month, url:'reports/rel_data.php?con=1&stat=open&date1=".$dataf_m."&date2=".$datai_m."'},
                {y:$month1, url:'reports/rel_data.php?con=1&stat=open&date1=".$dataf_m1."&date2=".$datai_m1."'}, 
                {y:$month2, url:'reports/rel_data.php?con=1&stat=open&date1=".$dataf_m2."&date2=".$datai_m2."' }]},
 				{
                name: 'Cotação',
                data: [{ y:$weeki, url:'reports/rel_data.php?con=1&stat=open&date1=".$dataf_s."&date2=".$datai_s."'}, 
                {y:$quinzi, url:'reports/rel_data.php?con=1&stat=open&date1=".$dataf_q."&date2=".$datai_q."'}, 
                {y:$monthi, url:'reports/rel_data.php?con=1&stat=open&date1=".$dataf_m."&date2=".$datai_m."'}, 
                {y:$month1i, url:'reports/rel_data.php?con=1&stat=open&date1=".$dataf_m1."&date2=".$datai_m1."'},
                {y:$month2i, url:'reports/rel_data.php?con=1&stat=open&date1=".$dataf_m2."&date2=".$datai_m2."'}]},
                 {
                name: 'Dispensa',
                data: [{ y:$weekix, url:'reports/rel_data.php?con=1&stat=open&date1=".$dataf_s."&date2=".$datai_s."'}, 
                {y:$quinzix, url:'reports/rel_data.php?con=1&stat=open&date1=".$dataf_q."&date2=".$datai_q."'}, 
                {y:$monthix, url:'reports/rel_data.php?con=1&stat=open&date1=".$dataf_m."&date2=".$datai_m."'}, 
                {y:$month1ix, url:'reports/rel_data.php?con=1&stat=open&date1=".$dataf_m1."&date2=".$datai_m1."'},
                {y:$month2ix, url:'reports/rel_data.php?con=1&stat=open&date1=".$dataf_m2."&date2=".$datai_m2."'}]},
                {                
                    name: 'Distrato',
                    data: [{ y:$weekiz, url:'reports/rel_data.php?con=1&stat=open&date1=".$dataf_s."&date2=".$datai_s."'}, 
                    {y:$quinziz, url:'reports/rel_data.php?con=1&stat=open&date1=".$dataf_q."&date2=".$datai_q."'}, 
                    {y:$monthiz, url:'reports/rel_data.php?con=1&stat=open&date1=".$dataf_m."&date2=".$datai_m."'}, 
                    {y:$month1iz, url:'reports/rel_data.php?con=1&stat=open&date1=".$dataf_m1."&date2=".$datai_m1."'},
                    {y:$month2iz, url:'reports/rel_data.php?con=1&stat=open&date1=".$dataf_m2."&date2=".$datai_m2."'}],
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
