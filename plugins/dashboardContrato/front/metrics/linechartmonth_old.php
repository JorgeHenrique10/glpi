<?php 
include ('conn_bd.php');
$query  = sprintf("select CASE
WHEN month(date)= '1' THEN 'JAN'
WHEN month(date) =  '2' THEN 'FEV'
WHEN month(date) = '3' THEN 'MAR'
WHEN month(date) = '4' THEN 'ABR'
WHEN month(date) = '5' THEN 'MAI'
WHEN month(date) = '6' THEN 'JUN'
WHEN month(date) = '7' THEN 'JUL'
WHEN month(date) = '8' THEN 'AGO'
WHEN month(date) = '9' THEN 'SET'
WHEN month(date) = '10' THEN 'OUT'
WHEN month(date) = '11' THEN 'NOV'
WHEN month(date) = '12' THEN 'DEZ'
END as Mes, count(month(date)) as Quantidade_Chamados
from glpi_tickets
where year(date) = year(now())
group by month(date)");
    
$result_month = $mysqli->query($query);
    
    $data_5 = array();
    foreach ($result_month as $row_5){
        $data_5[] = $row_5;
    }
$result_month -> close();

$mysqli-> close();

print json_encode($data_5);
?>
