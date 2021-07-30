<?php 

$data_inis = date("Y-m-d");  //hoje
$data_fins = date('Y-m-d', strtotime('-5 days'));

$sql_tecd = "
SELECT DATE_FORMAT(date, '%d-%m') as data, COUNT(id) as conta 
FROM glpi_tickets
WHERE glpi_tickets.is_deleted = 0
AND glpi_tickets.date BETWEEN '" . $data_fins ." 00:00:00' AND '".$data_inis." 23:59:59'
GROUP BY data
ORDER BY data ASC ";

$query_tecd = $DB->query($sql_tecd);

$arr_data = array();
while ($row_result = $DB->fetch_assoc($query_tecd)){ 
	$arr_data[] = $row_result['data'];	
} 

$datas = json_encode($arr_data);	
	

//REQUISIÇÕES
$DB->data_seek($query_tecd, 0);

while ($row = $DB->fetch_assoc($query_tecd)) { 
	
	$sql_tec = "
	SELECT DATE_FORMAT(date, '%d-%m') as data, COUNT(id) as conta1, SUM(case when glpi_tickets.type = 2 then 1 else 0 end) AS conta
	FROM glpi_tickets
	WHERE glpi_tickets.is_deleted = 0	
	AND DATE_FORMAT( date, '%d-%m' ) = '".$row['data']."'
	and glpi_tickets.entities_id = 1
	GROUP BY data ";
	
	$query_tec = $DB->query($sql_tec);	
	
	$row_result = $DB->fetch_assoc($query_tec);	
	$v_row_result = $row_result['data'];
	
	if($row_result['conta'] != '') {
		$arr_grfa[$v_row_result] = $row_result['conta'];
	}
	else {
		$arr_grfa[$v_row_result] = 0;
	}	
}

$quanta = array();

if( empty($arr_grfa) ) {
	$quanta = 0;
	$quanta2 = 0;
}
else {		
	$quanta = array_values($arr_grfa) ;
	$quanta2 = implode(',',$quanta);		
}

//INCIDENTES
$DB->data_seek($query_tecd, 0);
while ($row = $DB->fetch_assoc($query_tecd))	{ 

	$sql_teci = "
	SELECT DATE_FORMAT(date, '%d-%m') as data, COUNT(id) as conta1, SUM(case when glpi_tickets.type = 1 then 1 else 0 end) AS conta
	FROM glpi_tickets
	WHERE glpi_tickets.is_deleted = 0
	and glpi_tickets.entities_id = 1	
	AND DATE_FORMAT( date, '%d-%m' ) = '".$row['data']."'
	GROUP BY data ";
		
	$query_teci = $DB->query($sql_teci);
	
	$row_result = $DB->fetch_assoc($query_teci);	
	$v_row_result = $row_result['data'];
	
	if($row_result['conta'] != '') {
		$arr_grfi[$v_row_result] = $row_result['conta'];
	}
	else {
		$arr_grfi[$v_row_result] = 0;
	}	
}	

$quanti = array();

if( empty($arr_grfi) ) {
	$quanti = 0;
	$quanti2 = 0;
}
else {
	$quanti = array_values($arr_grfi);
	$quanti2 = implode(',',$quanti);
}


?>
