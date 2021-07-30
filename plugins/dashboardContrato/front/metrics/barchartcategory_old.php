<?php 
include ('conn_bd.php');
$query  = sprintf("select glpi.glpi_itilcategories.name as Categoria,
count(glpi.glpi_tickets.itilcategories_id) as Quantidade 
FROM glpi.glpi_tickets
inner join glpi.glpi_itilcategories
on glpi.glpi_tickets.itilcategories_id = glpi.glpi_itilcategories.id
WHERE
	month(date) = month(now()) AND glpi_tickets.is_deleted = '0' 
    group by glpi.glpi_itilcategories.name
    order by count(glpi.glpi_tickets.itilcategories_id) DESC
    limit 4;");
    
$result = $mysqli->query($query);
    
    $data = array();
    foreach ($result as $row){
        $data[] = $row;
    }
$result -> close();

$mysqli-> close();

function utf8ize($d) {
    if (is_array($d)) {
        foreach ($d as $k => $v) {
            $d[$k] = utf8ize($v);
        }
    } else if (is_string ($d)) {
        return utf8_encode($d);
    }
    return $d;
}


print json_encode(utf8ize($data));
?>
